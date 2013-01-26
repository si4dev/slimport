<?php
class Controller_Connect_Ogone extends AbstractController {
  function init() {
    parent::init();
    
    if(!($this->owner instanceof Model))throw $this->exception('Use addController() based on Model');
    $this->model=$this->owner;
    
  }
  
  
  function importBank() {

    $file=$this->model->get('source');
    
    if (($handle = fopen($file, 'r')) !== FALSE) {
      while (($data = fgetcsv($handle, 10000, "\t")) !== FALSE) {
        $m->unload();
        
        
        
    /*
      Id,REF,ORDER,STATUS,LIB,ACCEPT,PAYDATE,CIE,NAME,COUNTRY,TOTAL,CUR,SHIP,TAX,METHOD,BRAND,CARD,STRUCT,
      286895354,2011000001SIGNS_FR,01/01/2011,9,Betaling aangevraagd,532154,02/01/2011,,FREDERIC VICTORION,Fr,305.03,EUR,0.00,0.00,CreditCard,VISA,XXXXXXXXXXXX2403,028689535406,
    */
  }
}