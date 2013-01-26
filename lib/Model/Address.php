<?php
class Model_Address extends Model_Table {
  public $table='address';
  function init() {
    parent::init();
    
    $this->hasOne('Contact');
    $this->addField('type')->enum(array('invoice','deliver'));
    $this->addField('address');
    $this->addField('address2');
    $this->addField('city');
    $this->addField('state');
    $this->addField('postcode');
    $this->hasOne('Country','country_iso','iso');
  }
}
