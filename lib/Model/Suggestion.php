<?php
class Model_Suggestion extends Model_Table {
  public $table='bank_transaction';
  
  function init() {
    parent::init();
    
    $this->addField('???batch_id');
    $this->addField('account');
    $this->addField('currency');
    $this->addField('contra_account');
    $this->addField('date');
    $this->addField('amount');
    $this->addField('description');
    $this->addField('notes');
  }
}    