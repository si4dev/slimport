<?php
class page_chart extends Page {
  function init() {
    parent::init();

    $this->add('h1')->set('chart here');
    $this->add('Model_Sqlledger');
    
    $c=$this->add('CRUD');
    $c->setModel('Model_Sqlledger_Chart');
    if ($c->grid){ 
      $c->grid->addPaginator(50); 
    }  
  }
}

