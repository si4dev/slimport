<?php
class Model_Contact extends Model_Table {
  public $table='contact';
  function init() {
    parent::init();
    
    $this->hasOne('Business');
    $this->addField('number');
    $this->addField('type');
    $this->addField('firstname');
    $this->addField('middlename');
    $this->addField('lastname');
    $this->addField('company');
    $this->addField('phone');
    $this->addField('mobile');
    $this->addField('email');
    $this->addField('cc');
    $this->addField('bcc');
    $this->addField('notes');
    $this->addField('terms')->type('int');
    $this->addField('taxnumber');
    $this->addField('iban');
    $this->addField('bic');
    $this->hasOne('Employee','employee_id');
    $this->hasOne('Connect');
    $this->addField('startdate');
    $this->addField('enddate');
    $this->addExpression('name')->set('concat(firstname,lastname,company)');
    $this->hasMany('Address');
  }


  function import() {  
  
    $this->dsql()->truncate();
   
    $q=$this->api->db2->dsql();
    $q->table('customer')
      ->field($q->expr('1'),'business_id')
      ->field($q->expr('(id-10000)'),'id')
      ->field($q->expr("'c'"),'type')
      ->field('customernumber',null,'number')
      ->field('name',null,'lastname')
      ->field('contact',null,'company')
      ->field('phone')
      ->field('email')
      ->field('cc')
      ->field('bcc')
      ->field('notes')
      ->field('terms')
      ->field('taxnumber')
      ->field('iban')
      ->field('bic')
      ->field($q->expr('1'),'employee_id')
      ->field('startdate')
      ->field('enddate')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }

    $q=$this->api->db2->dsql();
    $q->table('vendor')
      ->field($q->expr('1'),'business_id')
      ->field($q->expr('(id-10000)'),'id')
      ->field($q->expr("'v'"),'type')
      ->field('vendornumber',null,'number')
      ->field('name',null,'company')
      ->field('contact',null,'lastname')
      ->field('phone')
      ->field('email')
      ->field('cc')
      ->field('bcc')
      ->field('notes')
      ->field('terms')
      ->field('taxnumber')
      ->field('iban')
      ->field('bic')
      ->field($q->expr('1'),'employee_id')
      ->field('startdate')
      ->field('enddate')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }


  }
}
