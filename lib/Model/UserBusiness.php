<?php
class Model_UserBusiness extends Model_Table {
  public $table='user_business';
  function init() {
    parent::init();

    $this->hasONe('User');
    $this->hasONe('Business');
  }
}
