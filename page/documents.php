<?php
class Page_Documents extends Page {
  function init() {
    parent::init();
    
    $c=$this->add('GRID');
    $c->setModel('Document');
      $c->addPaginator(20);
  }
}