<?php
class Page_Document extends Page {
 function init() {
  parent::init();
  
   
  // f for form and m for model used for the main form / model of this page. Then easy to reuse page snippets

  $f=$this->add('MVCForm');
  $m=$this->add('Model_Document');
  
  // $m->load(10177);
  $f->setModel($m);
  // this is a test invoice 207 to show something, of course a page should be launched to
  // show a list of documents or add a new one.
  $m->load(207);
  
  // show the line items, so the products on the invoice/order/quote
  $this->add('H2')->set('Line Items');
  $item=$m->ref('Item');
  $cItem=$this->add('CRUD');
  $cItem->setModel($item);
  if( $cItem->grid ) {
    $cItem->grid->addPaginator(10);
  }

  // only imppact on ledger for invoice and general ledger. Not for order and quote.
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