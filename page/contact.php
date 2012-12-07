<?php
class Page_Contact extends Page {
  function init() {
    parent::init();
	
	$this->add('h1')->set('Manage Contacts');
	$this->add('hr');
    $c=$this->add('CRUD');
    $c->setModel('Contact');
    if($c->grid) {
      $c->grid->addPaginator(10);
      $c->grid->addQuickSearch(array('number','company','firstname','lastname','phone','mobile','notes'));
    }
  }
}
