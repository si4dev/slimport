<?php
class Model_Chart extends Model_Table {
  public $table='chart';
  public $title_field='description';
  function init() {
    parent::init();
    $this->hasOne('Business'); 
    $this->addField('accno');
    $this->addField('description');
    $this->addField('charttype'); // H=Header, A=Asset, E=Expense 
    $this->addField('category'); // Q=eQuity, L=Liability, A=Asset, E=Expense, I=Income 
    // combinations charttype-category: A-Q,A-L,A-A,A-E,A-I,E-E, rest is Headers
    // E-E is alleen "Toeslag Indirecte Verkoopkosten"
    
  }

  function import() {
   
    $q=$this->api->db2->dsql();
    $q->table('chart')
      ->field($q->expr('(id-10000)'),'id')
      ->field('accno')
      ->field('description')
      ->field('charttype')
      ->field('category')
      ;
    $this->dsql()->truncate();
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }
      
  }
  
  


}
    
  
  