<?php
class Model_Contact_Customer extends Model_Contact {
  function init() {
    parent::init();
    
    $this->addCondition('type','c');
  }
}
