<?php
class Page_Contact extends Page {
  function init() {
    parent::init();
	
	$this->add('H1')->set('Manage Contacts');
	$this->add('HR');
    $c=$this->add('CRUD');
    $c->setModel('Contact');
    if($c->grid) {
      $c->grid->addPaginator(10);
      $c->grid->addQuickSearch(array('company','firstname','lastname','phone','mobile','notes'));
    }
  }
}
