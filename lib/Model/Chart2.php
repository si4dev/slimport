<?php
class Model_Chart2 extends Model_Table {
  public $table='chart';
  function init() {
    parent::init();$this->debug();
    $this->hasOne('Business'); 
    $this->addField('acc_nr');
    $this->addField('description');
    $this->addField('type');
    $this->addExpression('name',"concat(acc_nr,' ',description)");
    $this->addField('chart_type'); // H=Header, A=Asset, E=Expense 
    $this->addField('category'); // Q=eQuity, L=Liability, A=Asset, E=Expense, I=Income 
    // combinations charttype-category: A-Q,A-L,A-A,A-E,A-I,E-E, rest is Headers
    // E-E is alleen "Toeslag Indirecte Verkoopkosten"
    $this->hasMany('Tax');
    $this->addCondition('business_id',$this->api->business->id);
  }
    function addExtraFields() {
        $lg = $this->join('ledger','id','inner',null);
        $lg->addField('amount');
        $lg->addField('transdate');

  //      $dc = $this->join('document');

        $this->_dsql()->group('acc_nr')->group('chart_type')->group('category');
        //$this->_dsql()->order('acc_nr')->order('chart_type')->order('category');
        $this->_dsql()->order('acc_nr')->order('category');
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
    
  
  