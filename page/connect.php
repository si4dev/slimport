<?php
class Page_Connect extends Page {
  function init() {
    parent::init();

    $b=$this->api->business;
    $this->add('Text')->set('Business '. $b->get('name'));
    
   // $b->ref('Connect')->load(1)->connect()->importOrders();
    $c=$this->add('Grid');
    $c->setModel($b->ref('Connect'),array('platform'));
    $c->addColumn('button','batch');
    if($_GET['batch']){
      $p=$this->api->getDestinationURL(
          'batch',array(
          'connect'=> $_GET['batch']
        ));
      $c->js()->univ()->location($p)->execute();
      $this->api->redirect($p);
    }
  }
}
