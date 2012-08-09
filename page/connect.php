<?php
class Page_Connect extends Page {
  function init() {
    parent::init();

    $b=$this->add('Model_Business')->load(2);
    $b->ref('Connect')->load(1)->connect()->importOrders();
       
  }
}
