<?php
// ar=account receivables=debiteuren=verkoop
// ap=account payables=crebiteuren=inkoop
// http://en.wikipedia.org/wiki/Specialized_journals
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
    $this->hasOne('Employee','employee_id');
    $this->hasOne('Connect');
    $this->addField('connect_ref'); // id of other system connected to, e.g. prestashop id_order
    $this->addField('bank_refs'); // room to list other references possible for bank recognition (invoice/order/cart)
    $this->addField('approved')->type('boolean')->editable(false);
//    $this->setMasterField('business_id',1);
    $this->hasMany('Item');
    $this->hasMany('Ledger');
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
