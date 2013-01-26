<?php
class Page_Import extends Page {
  function init() {
    parent::init();
    
    // script memory
    if(isset($memory_limit)) ini_set("memory_limit", $memory_limit);
    // set the script timeout and database timeeout
    $timeout='6000';
    if( isset($timeout) ) {
      set_time_limit($timeout);
      ini_set('default_socket_timeout', ini_get('max_execution_time'));
    }
    
    
    $b=$this->api->business;
    
    if($b->id!=3) return;
    
    $this->add('Model_SqlledgerRef')->dsql()->truncate();
    
    $this->add('Model_Sqlledger');
    $b->ref('Chart')->sl_import();
    $b->ref('Contact')->sl_import();
    $b->ref('Product')->sl_import();
    $b->ref('Document')->sl_import(); // includes item + ledger
    
    
    
  }
}