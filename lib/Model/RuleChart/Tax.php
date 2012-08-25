<?php
class Model_RuleChart_Tax extends Model_Rule {
  function init() {
    parent::init();
    $rulechart=$this->join('rule_chart.rule_id');
    $rulechart->addField('chart_id');
    $chart=$rulechart->join('chart');
    $chart->addField('display');
 
    $tax=$chart->join('tax.chart_id');
    $tax->addField('rate');
    $tax->addField('amount');
    $tax->addField('startdate');
    $this->setOrder('startdate');
  }
  

    
}