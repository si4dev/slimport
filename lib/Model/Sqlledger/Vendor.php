<?php
class Model_Sqlledger_Vendor extends Model_Table2 {
  public $table='vendor';
  function init() {
    parent::init();
    
    $this->addField('name');
    $this->addField('iban');
    
    
  }
}
    
  
  