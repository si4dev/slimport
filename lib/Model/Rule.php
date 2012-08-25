<?php
class Model_Rule extends Model_Table {
  public $table='rule';
  function init() {
    parent::init();
    $this->addField('name');
    $this->addField('type');
//    $rulechart=$this->join('rule_chart.rule_id');
//    $chart=$rulechart->join('chart');
  //  $chart->addField('description');
  }
}