<?php
class Page_Balance extends Page {
  function init() {
    parent::init();
    
    
    $b=$this->api->business;


    $q=$this->api->db->dsql();
    $q->table('ledger')
      ->join('document',null,'inner')
      ->join('chart',null,'inner')
      ->field('acc_nr')->group('acc_nr')
      ->field('chart_type')->group('chart_type')
      ->field('category')->group('category')
      ->field('description')
      ->field($q->expr('sum(if(amount<0,-amount,0))'),'debet') // debet
      ->field($q->expr('sum(if(amount>0,amount,0))'),'credit')  // credit
    //  ->where('ledger.transdate','>=','2011-01-01')
      ->where('ledger.transdate','<','2011-12-31')
      ->where('chart.business_id',$b->id)
  //    ->having('abs(sum(amount_credit))>',0.001)
  //    ->where('category','in',array('E','I'))
      ->order('category')->order('chart_type')->order('acc_nr')
      ;
    $q->debug();

/* original to postgresql  -- not operational!
    $this->add('Model_Sqlledger');
    $q=$this->api->db2->dsql();
    $q->table('acc_trans')
      ->field('accno')->group('accno')
      ->field('chart_type')->group('chart_type')
      ->field('category')->group('category')
      ->join('chart')
      ->field($q->expr('max(description)'),'description')
      ->field($q->expr('sum(amount)'),'amount')
//      ->where('transdate','>=','2010-01-01')
      ->where('transdate','<','2012-01-01')
      ->having('abs(sum(amount))>',0.001)
      ->order('category')->order('accno')
      ;
    $q->debug();
*/

//      $line->template->set($row);

    foreach($q as $l) {
      $ledger[$l['acc_nr']]=$l;
    }

    $chart=$b->ref('Chart')->setOrder('category','desc')->setOrder('acc_nr');
    $result=array();
    foreach($chart as $c) {
      $i=$c['acc_nr'];
      if(isset($ledger[$i])) {
        $result[$i]=(array)$c;
        $result[$i]['debet']=number_format($ledger[$i]['debet'],2);
        $result[$i]['credit']=number_format($ledger[$i]['credit'],2);
        $result[$i]['amount']=number_format($ledger[$i]['debet']-$ledger[$i]['credit'],2);
      } elseif($c['chart_type']=='H') {
        $result[$i]=(array)$c;
                //$result[$i]['debet']=0;
        //$result[$i]['credit']=0;
      }
    }

    $line=$this->add('Lister',null,'Line','Line');

    $line->addHook('formatRow',$this);
  
    $line->setSource($result);
        

    $c=$this->add('Grid');
    $c->addColumn('text','acc_nr')
      ->addColumn('text','description')
      ->addColumn('text','chart_type')
      ->addColumn('text','category')
      ->addColumn('number','debet')
      ->addColumn('number','credit')
      ->addColumn('number','amount')
      ->addTotals(array('debet','credit'))
      ->setTotalsTitle('description','Totals')
      ;
      $c->setSource($result);
  }

  function formatRow($line) {
    if($line->current_row['chart_type']=='H') {
      $line->current_row['context']='ui-widget-header';
    } else {
      $line->current_row['context']='';
    }
  }
  
  function defaultTemplate() {
      return array('page/balance');
  }
}