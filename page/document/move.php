<?php
class page_document_move extends Page {
  function init() {
    parent::init();
    
    
    $b=$this->api->business;
    $this->add('Text')->set('Booking:'.$_GET['id']);
    $this->add('Text')->set('Buseiness:'.$b['name']);
    
    
    
    $f=$this->add('Form');
    $m=$b->ref('Match');
$v=  $m->getActualFields(); print_r($v);
$m->getElement('contact_id')->getModel()->addCondition('business_id',$b->id);
    $f->setModel($m);
    
    
    
    
  }
}
