<?php
class Model_Business extends Model_Table {
  public $table='business';
  function init() {
    parent::init();
    $this->addField('name');
  }
}
