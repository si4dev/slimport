<?php
class Page_Balance2 extends Page {
  function init() {
    parent::init();

    
    $b=$this->api->business;


//    $q=$this->api->db->dsql();
//    $q->table('ledger')
//      ->join('document',null,'inner')
//      ->join('chart',null,'inner')
//      ->field('acc_nr')->group('acc_nr')
//      ->field('chart_type')->group('chart_type')
//      ->field('category')->group('category')
//      ->field('description')
//    //  ->where('ledger.transdate','>=','2011-01-01')
//      ->where('ledger.transdate','<','2011-12-31')
//      ->where('chart.business_id',$b->id)
//  //    ->having('abs(sum(amount_credit))>',0.001)
//  //    ->where('category','in',array('E','I'))
//      ->order('category')->order('chart_type')->order('acc_nr')
//      ;
//    $q->debug();
//
//    foreach($q as $l) {
//      $ledger[$l['acc_nr']]=$l;
//    }
//
//    $chart=$b->ref('Chart')->setOrder('category','desc')->setOrder('acc_nr');
//    $result=array();
//    foreach($chart as $c) {
//      $i=$c['acc_nr'];
//      if(isset($ledger[$i])) {
//        $result[$i]=(array)$c;
//        $result[$i]['debet']=number_format($ledger[$i]['debet'],2);
//        $result[$i]['credit']=number_format($ledger[$i]['credit'],2);
//        $result[$i]['amount']=number_format($ledger[$i]['debet']-$ledger[$i]['credit'],2);
//      } elseif($c['chart_type']=='H') {
//        $result[$i]=(array)$c;
//                //$result[$i]['debet']=0;
//        //$result[$i]['credit']=0;
//      }
//    }

    $line=$this->add('Grid_Balance');
      $line->addPaginator(500);
    $model=$this->add('Model_Chart2');
    $model->addExtraFields();
    $model->_dsql()->where('transdate','<','2011-12-31');
//    $model->_dsql()->limit('1000');
    $line->setModel($model,array('acc_nr','description','chart_type','category','debet','credit','amount'));
  }
  
  function defaultTemplate() {
      return array('page/balance2');
  }
}