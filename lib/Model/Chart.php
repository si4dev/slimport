<?php
class Model_Chart extends Model_Table {
  public $table='chart';
  function init() {
    parent::init();
    $this->hasOne('Business'); 
    $this->addField('acc_nr');
    $this->addField('description');
    $this->addField('type')->enum(array('AR', 'AP'));
    $this->addExpression('name',"concat(acc_nr,' ',description)");
    $this->addField('chart_type'); // H=Header, A=Asset, E=Expense 
    $this->addField('category'); // Q=eQuity, L=Liability, A=Asset, E=Expense, I=Income 
    // combinations charttype-category: A-Q,A-L,A-A,A-E,A-I,E-E, rest is Headers
    // E-E is alleen "Toeslag Indirecte Verkoopkosten"
    $this->hasMany('Tax');
    $this->addCondition('business_id',$this->api->business->id);
  }

  function sl_import() {
    // import charts from sqlledger into slimport
    $q=$this->api->db2->dsql();
    $q->table('chart')
      ->field('id',null,'sl_id')
      ->field('accno',null,'acc_nr')
      ->field('description')
      ->field('charttype',null,'chart_type')
      ->field('category')
      ->field('link',null,'type')
      ;
    $this->deleteAll();
    $this->join('sqlledger.chart_id','id')->addField('sl_id');
    
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }
      
  }
  
  


}
    
  
  