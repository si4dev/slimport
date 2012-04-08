<?php
class page_vendor extends Page {
  function init() {
    parent::init();

    $this->add('h1')->set('vendor here');
    $this->add('Model_Sqlledger');
        
    $c=$this->add('CRUD');
    $c->setModel('Model_Sqlledger_Vendor');
    if ($c->grid){ 
      $c->grid->addPaginator(5); 
    }  
  }
}

