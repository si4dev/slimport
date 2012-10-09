<?php
class Model_Tax extends Model_Table {
  public $table='tax';
  function init() {
    parent::init();
    
    $this->hasOne('Chart');
    $this->addField('rate');
    $this->addField('amount');
    $this->addField('taxnumber');
    $this->addField('startdate');
  }
}
