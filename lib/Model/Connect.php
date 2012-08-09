<?php
class Model_Connect extends Model_Table {
  public $table='connect';
  function init() {
    parent::init();
    
    $this->hasOne('Business');
    $this->addField('platform');
    $this->addField('source');
  }
  
  function connect() {
    return $this->setController('Connect_'.ucwords($this->get('platform')));
  }
}