<?php
class Model_Ledger extends Model_Table {
  public $table='ledger';
  function init() {
    parent::init();
    $this->hasOne('Chart');
    $this->hasOne('Document');
    $this->hasOne('Item');
    $this->addField('amount');
    $this->addField('transdate');
    $this->addField('reference');
  }


  function import() {
   
    $q=$this->api->db2->dsql();
    $q->table('acc_trans')
      ->field($q->expr('(trans_id-10000)'),'document_id')
      ->field($q->expr('(chart_id-10000)'),'chart_id')
      ->field('amount')
      ->field('transdate')
      ->field('source',null,'reference')
      ;
    $this->dsql()->truncate();
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }
      
  }
  

}
    
  
  