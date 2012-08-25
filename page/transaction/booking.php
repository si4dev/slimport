<?php
class Page_Transaction_Booking extends Page {
  function init() {
    parent::init();
    
    
    $b=$this->api->business;
    $this->add('Text')->set('Booking:'.$_GET['id']);
    
    
    
    $f=$this->add('Form');
    $f->addField('autocomplete','contact')->setModel($b->ref('Contact'));
    

    
  }
}
