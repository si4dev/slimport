<?php
class Page_Document extends Page {
 function init() {
  parent::init();
  
   
  // f for form and m for model used for the main form / model of this page. Then easy to reuse page snippets

  $f=$this->add('MVCForm');
  $m=$f->setModel('Model_Document');
  $m->load(10177);
  
  
  $this->add('H2')->set('Line Items');
  $item=$m->ref('Item');
  $cItem=$this->add('CRUD');
  $cItem->setModel($item,array('product','description','serial','quantity','sellprice'));

  switch( $m->get('type') ) {
    case 'si':
    case 'pi':
    case 'gl':
      $this->add('H2')->set('Ledger');
      $ledger=$m->ref('Ledger');
      $cLedger=$this->add('CRUD');
      $cLedger->setModel($ledger);
    }
  }
}