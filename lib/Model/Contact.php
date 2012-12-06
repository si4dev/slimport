<?php
class Model_Contact extends Model_Table {
  public $table='contact';
  function init() {
    parent::init();
    
   
    $this->addField('number');
    $this->hasOne('contact_group')->caption('Group');
    $this->addField('firstname');
    $this->addField('middlename');
    $this->addField('lastname');
    $this->addExpression('name')->set('concat(firstname,lastname,company)');
    $this->addField('company');
    $this->addField('phone');
    $this->addField('mobile');
    $this->addField('email');
    $this->addField('cc');
    $this->addField('bcc');
    $this->addField('notes');
    $this->addField('terms')->type('int');
	
	$this->hasOne('tax');
	
	$this->addField('taxnumber');
    $this->addField('iban');
    $this->addField('bic');
    $this->hasOne('User');
    $this->hasOne('Batch')->system(true);
    $this->addField('startdate');
    $this->addField('enddate');
    $this->hasMany('Address');

    $group=$this->join('contact_group');
    $group->hasOne('Business');
    $group->addField('type');

    $this->addCondition('business_id', $this->api->business->id);
  }


  function sl_import() {  
    $this->deleteAll();
    $this->join('sqlledger.contact_id','id')->addField('sl_id');
    
    $q=$this->api->db2->dsql();
    $q->table('customer')
      ->field('id',null,'sl_id')
      ->field($q->expr("'ar'"),'type') // customer = AR
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
//      ->field('iban')
//      ->field('bic')
      ->field($q->expr($this->api->auth->model->id),'user_id')
      ->field('startdate')
      ->field('enddate')
      ;
      
    
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }

    $q=$this->api->db2->dsql();
    $q->table('vendor')
      ->field('id',null,'sl_id')
      ->field($q->expr("'ap'"),'type')
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
//      ->field('iban')
//      ->field('bic')
      ->field($q->expr($this->api->auth->model->id),'user_id')
      ->field('startdate')
      ->field('enddate')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }
  }
  
  function setType($type){
	 $this->addCondition('contact_group', $type);
  }
}