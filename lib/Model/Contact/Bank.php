<?php
class Model_Contact_Bank extends Model_Contact {
  function init() {
    parent::init();
    
    $this->hasOne('Chart','chart_bank_id');
    $this->addCondition('type','b');
  }
}
