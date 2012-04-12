<?php
class Page_Import extends Page {
  function init() {
    parent::init();
    
    
  
    $this->add('Model_Sqlledger');
//   $this->add('Model_Chart')->import();
//    $this->add('Model_Ledger')->import();
//    $this->add('Model_Document')->import();
    $this->add('Model_Contact')->import();
//    $this->add('Model_Product')->import();
//    $this->add('Model_Item')->import();
    
  }
}