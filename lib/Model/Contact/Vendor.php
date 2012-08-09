<?php
class Model_Contact_Vendor extends Model_Contact {
  function init() {
    parent::init();
    
    $this->addCondition('type','v');
  }
}
