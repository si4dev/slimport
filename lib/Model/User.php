<?php
class Model_User extends Model_Table {
  public $table='user';
  function init() {
    parent::init();

    $this->addField('name');
    $this->addField('email');
  }
}
