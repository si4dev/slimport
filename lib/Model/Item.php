<?php
class Model_Item extends Model_Table {
  public $table='item';
  public $title_field='description';
  function init() {
    parent::init();
    $this->hasOne('Document');
    $this->hasOne('Product');
    $this->addField('description');
    $this->addField('serial');
    $this->addField('quantity');
    $this->addField('price');
  }


  function import() {
    $this->dsql()->truncate();
   
    $q=$this->api->db2->dsql();
    $q->table('orderitems')
      ->field($q->expr('(id-10000)'),'id')
      ->field($q->expr('(trans_id-10000)'),'document_id')
      ->field($q->expr('(parts_id-10000)'),'product_id')
      ->field('description')
      ->field('qty',null,'quantity')
      ->field('sellprice',null,'price')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }
   
    $q=$this->api->db2->dsql();
    $q->table('invoice')
      ->field($q->expr('(id-10000)'),'id')
      ->field($q->expr('(trans_id-10000)'),'document_id')
      ->field($q->expr('(parts_id-10000)'),'product_id')
      ->field('description')
      ->field('serialnumber',null,'serial')
      ->field('qty',null,'quantity')
      ->field('sellprice',null,'price')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }

  }



}
