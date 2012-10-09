<?php
class Model_RuleChart extends Model_Rule {
  function init() {
    parent::init();
    $rulechart=$this->join('rule_chart.rule_id');
    $rulechart->addField('chart_id');
    $chart=$rulechart->join('chart');
    $chart->addField('display');
  }
}