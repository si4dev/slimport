<?php
class Model_Chart_Tax extends Model_Chart {
  public $transdate;
  function init() {
    parent::init();
    $this->addCondition('type','like','tax%');
    $this->addExpression('atdate','now()');

    // get tax rate with max date by this article:
    // http://stackoverflow.com/questions/121387/fetch-the-row-which-has-the-max-value-for-a-column/123481#123481
    /*
    SELECT t1.*
    FROM mytable AS t1
      LEFT OUTER JOIN mytable AS t2
        ON (t1.UserId = t2.UserId AND t1."Date" < t2."Date")
    WHERE t2.UserId IS NULL;
    */
    /*
    $tax1=$this->leftJoin('tax',$this->dsql->expr('tax1.chart_id=chart.id'),null,'tax1');
    $tax2=$tax1->leftJoin('tax',$this->dsql->expr('tax2.chart_id=chart.id and tax1.startdate < tax2.startdate'),null,'tax2');
    */
    
    $this->addExpression('tax_rate')->set(function($m,$q){
        return $m->refSQL('Tax')->dsql()
          ->where('startdate', '<=', ($m->transdate?:$q->expr('now()')))
          ->order('startdate','desc')->field('rate')->limit(1);
    });
  }
  
  public function setTransDate($transdate) {
    $this->transdate=$transdate;
    return $this;
  }
}