<?php
class Page_Document extends Page {
 function init() {
  parent::init();
  
  $this->api->stickyGET('document');
  $this->add('P')->set('logged in as '.$this->api->auth->get('email'));
   
  // f for form and m for model used for the main form / model of this page. Then easy to reuse page snippets
  $f=$this->add('Form');
  $m=$this->api->business->ref('Document')->load($_GET['document']);
  $f->setModel($m);
  
  
  // show the line items, so the products on the invoice/order/quote
  $this->add('H2')->set('Line Items');
  $item=$m->ref('Item');
  $cItem=$this->add('CRUD');
  $cItem->setModel($item);
  if( $cItem->grid ) {
    //$cItem->grid->addPaginator(10);
  }

  // show the transactions
  $this->add('H2')->set('Transactions');
  $cTrans=$this->add('CRUD');
  if($cTrans->grid) {
    $cTrans->grid->addColumn('expander','move');
  }
  $cTrans->setModel($m->ref('Transaction'),array('transdate','amount','contra_account','notes'));
  if( $cTrans->grid ) {
    //$cTrans->grid->addPaginator(10);
  }

  // only impact on ledger for invoice and general ledger. Not for order and quote.
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