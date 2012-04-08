<?php
class Model_Balance extends Model_Table {
  function init() {
    parent::init();
    $chart=$this->join('chart');
    $chart->addField('description');
    $chart->addField('charttype');
    $chart->addField('category');
//    $this->addCondition('category',array('A','L'));

   

  }
}