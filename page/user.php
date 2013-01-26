<?php
class page_user extends Page {
  function init() {
    parent::init();
    
        $this->add('Text')->set('hello here');
    
    $c=$this->add('CRUD');
    $c->setModel('Model_User');
    if ($c->grid){ 
        $c->grid->addPaginator(5); 
        $c->grid->addQuickSearch(array('name','email'));
    }  
  }
}

