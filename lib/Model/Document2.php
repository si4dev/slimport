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
    $this->hasOne('User')->system(true);
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
  private function sl_import_save($q) {
    foreach($q as $row) {
      $this->unload()->set($row);
      if($row['contact_id']) {
        $contact_id= $this->sl->loadBy('sl_id',$row['contact_id'])->get('contact_id');
        $this->set('contact_id',$contact_id);
      }
      $this->save();
        
      $this->ref('Item')->sl_import($row['sl_id']);
      $this->ref('Ledger')->sl_import($row['sl_id']);
    }
  }

  function sl_import() {
    $this->deleteAll();
    $this->join('sqlledger.document_id','id')->addField('sl_id');
    $user_id=$this->api->auth->model->id;
    
    $q=$this->api->db2->dsql();
    $q->table('ap')
      ->field('id',null,'sl_id')
      ->field($q->expr("'pi'"),'type')
      ->field('invnumber',null,'number')
      ->field('ordnumber',null,'reference')
      ->field('transdate')
      ->field('curr',null,'currency')
      ->field('vendor_id',null,'contact_id')
      ->field($q->expr($user_id),'user_id')
      ->field('approved')
      ;
      
    $this->sl=$this->add('Model_SqlledgerRef')->addCondition('contact_id','', $this->dsql->expr('is not null') );
    
    $this->sl_import_save($q);

    $q=$this->api->db2->dsql();
    $q->table('ar')
      ->field('id',null,'sl_id')
      ->field($q->expr("'si'"),'type')
      ->field('invnumber',null,'number')
      ->field('ordnumber',null,'reference')
      ->field('transdate')
      ->field('curr',null,'currency')
      ->field('customer_id',null,'contact_id')
      ->field($q->expr($user_id),'user_id')
      ->field('approved')
      ;
    $this->sl_import_save($q);
    
    $q=$this->api->db2->dsql();
    $q->table('oe')
      ->field('id',null,'sl_id')
      ->field($q->expr("case when vendor_id>0 then 'po' else 'so' end"),'type')
      ->field('ordnumber',null,'number')
      ->field('transdate')
      ->field('curr',null,'currency')
      ->field('customer_id',null,'contact_id')
      ->field($q->expr($user_id),'user_id')
      ->field($q->expr('1'),'approved')
      ;
    $this->sl_import_save($q);

    $q=$this->api->db2->dsql();
    $q->table('gl')
      ->field('id',null,'sl_id')
      ->field($q->expr("'gl'"),'type')
      ->field('reference',null,'number')
      ->field('transdate')
      ->field('curr',null,'currency')
      ->field($q->expr($user_id),'user_id')
      ->field('approved')
      ;
    $this->sl_import_save($q);

  
    
  }



}
