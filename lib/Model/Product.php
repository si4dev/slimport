<?php
class Model_Product extends Model_Table {
  public $table='product';
  public $title_field='productcode';
  function init() {
    parent::init();
    $this->addField('productcode');
    $this->addField('description');
    $this->addField('unit');
    $this->addField('sellprice');
    $this->addField('active');
    $this->hasOne('Business');
    $this->setMasterField('business_id',1);
    //$this->addExpression('name',"concat(productcode,' ',description)");
  }


  function import() {
    $this->dsql()->truncate();
   
    $q=$this->api->db2->dsql();
    $q->table('parts')
      ->field($q->expr('1'),'business_id')
      ->field($q->expr('(id-10000)'),'id')
      ->field('partnumber',null,'productcode')
      ->field('description')
      ->field('unit')
      ->field('sellprice')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }
  }



}
