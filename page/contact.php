<?php
class Page_Contact extends Page {
  function init() {
    parent::init();
    $c=$this->add('CRUD');
    $c->setModel('Contact');
    if($c->grid) {
      $c->grid->addPaginator(10);
      $c->grid->addQuickSearch(array('company','firstname','lastname','phone','mobile','notes'));
    }
  }
}
