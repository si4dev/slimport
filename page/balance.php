<?php
class Page_Balance extends Page {
  function init() {
    parent::init();
    
    
  


    $q=$this->api->db->dsql();
    $q->table('ledger')
      ->join('chart')
      ->field('accno')->group('accno')
      ->field('charttype')->group('charttype')
      ->field('category')->group('category')
      ->field('description')
      ->field($q->expr('sum(amount)'),'amount')
      ->where('transdate','>=','2010-01-01')
      ->where('transdate','<','2010-12-31')
      ->having('abs(sum(amount))>',0.001)
      ->order('category')->order('accno')
      ;
    $q->debug();

/* original to postgresql
    $this->add('Model_Sqlledger');
    $q=$this->api->db2->dsql();
    $q->table('acc_trans')
      ->field('accno')->group('accno')
      ->field('charttype')->group('charttype')
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
    
    
    $c=$this->add('Grid');
    $c->addColumn('text','accno')
      ->addColumn('text','description')
      ->addColumn('text','charttype')
      ->addColumn('text','category')
      ->addColumn('money','amount')
      ;
      $c->setSource($q);
  }
}