<?php
class Model_Business extends Model_Table {
  public $table='business';
  function init() {
    parent::init();
    $this->addField('name');
    $this->hasMany('Connect');
    $this->hasMany('Contact');
    $this->hasMany('Contact_Customer');
    $this->hasMany('Contact_Vendor');
    $this->hasMany('Document');
    $this->hasMany('Document_SalesOrder');
    $this->hasMany('Product');
    $this->hasMany('Chart');
  }
}
