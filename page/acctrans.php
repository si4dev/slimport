<?php
class Page_AccTrans extends Page {
  function init() {
    parent::init();
    
    $this->add('Model_Sqlledger');
    $c=$this->add('Grid');
    $c->setModel('Sqlledger_AccTrans');
    $c->dq->limit(2);
//    if($c->grid) {
//      $c->grid->addPaginator(100);
  //  }
  }
}