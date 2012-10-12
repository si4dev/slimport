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
    $this->hasOne('Rule','rule_tax_id');
    $this->addField('rule_pl_id');
    $this->hasMany('RuleChart','id','rule_pl_id');
    $this->hasMany('RuleChart_Tax','id','rule_tax_id');
    $this->hasOne('Business');
    //$this->addExpression('name',"concat(productcode,' ',description)");

      $this->addCondition('business_id',$this->api->business->id);
    }


  function sl_import() {
    $this->deleteAll();
    $this->join('sqlledger.product_id','id')->addField('sl_id');

   
    $q=$this->api->db2->dsql();
    $q->table('parts')
      ->field('id',null,'sl_id')
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
