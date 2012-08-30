<?php
// ar=account receivables=debiteuren=verkoop
// ap=account payables=crebiteuren=inkoop
// http://en.wikipedia.org/wiki/Specialized_journals

// alles wat sowieso in de factuur moet komen doen we niet in contacts.
// dus bijvoorbeeld de AR of AP chart en ook de default kosten chart bij inkoop
// dit komt dan in een template factuur die we contracten noemen
class Model_Document extends Model_Table {
  public $table='document';
  public $title_field='number';
  function init() {
    parent::init();
    $this->hasOne('Business');
    $this->addField('type')->enum(array('si','so','sq','pi','po','pq','gl'));
    $this->addField('number');
    $this->addField('reference_document_id');
    $this->addField('transdate')->type('date');
    $this->addField('currency')->editable(false);
    $this->addField('notes');
    $this->addField('intnotes');
    $this->hasOne('Contact');
    $this->hasOne('Employee','employee_id')->system(true);
    $this->hasOne('Batch')->system(true);
    $this->addField('bank_matches'); // room to list other references possible for bank recognition (invoice/order/cart)
    $this->addField('approved')->type('boolean')->editable(false);
    $this->hasOne('Chart','chart_against_id'); // for SI (sales invoice) it's AR, for PI it's AP, bank is 
    $this->addField('rule_notax_id'); // rule out some taxes, so US customer should not pay 19%/6%/0% tax
    $this->hasMany('RuleChart','id','rule_notax_id');
    

//    $this->addField('rule_arap_id');
//    $this->hasMany('RuleChart','id','rule_arap_id');


    $this->hasMany('Item');
    $this->hasMany('Ledger');
    $this->hasMany('Transaction');
  }

  function ledger() {
    $this->ref('Ledger')->addCondition('item_id','is not',null)->deleteAll();
    $items=$this->ref('Item');
    foreach($items as $item) {
      $items->ledger();
    }
  }
  

  function import() {
    $this->dsql()->truncate();
   
    $q=$this->api->db2->dsql();
    $q->table('ap')
      ->field($q->expr('1'),'business_id')
      ->field($q->expr('(id-10000)'),'id')
      ->field($q->expr("'pi'"),'type')
      ->field('invnumber',null,'number')
      ->field('ordnumber',null,'reference')
      ->field('transdate')
      ->field('curr',null,'currency')
      ->field($q->expr('(vendor_id-10000)'),'contact_id')
      ->field($q->expr('1'),'employee_id')
      ->field('approved')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }

    $q=$this->api->db2->dsql();
    $q->table('ar')
      ->field($q->expr('1'),'business_id')
      ->field($q->expr('(id-10000)'),'id')
      ->field($q->expr("'si'"),'type')
      ->field('invnumber',null,'number')
      ->field('ordnumber',null,'reference')
      ->field('transdate')
      ->field('curr',null,'currency')
      ->field($q->expr('(customer_id-10000)'),'contact_id')
      ->field($q->expr('1'),'employee_id')
      ->field('approved')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }
    
    $q=$this->api->db2->dsql();
    $q->table('oe')
      ->field($q->expr('1'),'business_id')
      ->field($q->expr('(id-10000)'),'id')
      ->field($q->expr("case when vendor_id>0 then 'po' else 'so' end"),'type')
      ->field('ordnumber',null,'number')
      ->field('transdate')
      ->field('curr',null,'currency')
      ->field($q->expr('(customer_id-10000)'),'contact_id')
      ->field($q->expr('1'),'employee_id')
      ->field($q->expr('1'),'approved')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }

    $q=$this->api->db2->dsql();
    $q->table('gl')
      ->field($q->expr('1'),'business_id')
      ->field($q->expr('(id-10000)'),'id')
      ->field($q->expr("'gl'"),'type')
      ->field('reference',null,'number')
      ->field('transdate')
      ->field('curr',null,'currency')
      ->field($q->expr('1'),'employee_id')
      ->field('approved')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }

  
    
  }



}
