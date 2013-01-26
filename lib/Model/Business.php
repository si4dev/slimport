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
    $this->hasMany('Contact_Bank');
    $this->hasMany('Document');
    $this->hasMany('Document_Open');
    $this->hasMany('Document_SalesOrder');
    $this->hasMany('Product');
    $this->hasMany('Chart');
    $this->hasMany('Batch');
    $this->hasMany('Match');
  }
}
