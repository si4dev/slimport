<?php
class Page_Document extends Page {
 function init() {
  parent::init();
  
  $b=$this->api->business;
  
  $this->api->stickyGET('document');
  $this->add('P')->set('logged in as '.$this->api->auth->get('email'));
   
  // f for form and m for model used for the main form / model of this page. Then easy to reuse page snippets
  $f=$this->add('Form');
  $m=$b->ref('Document')->load($_GET['document']);
  $f->setModel($m);
  
  
  // show the line items, so the products on the invoice/order/quote
  $this->add('H2')->set('Line Items');
  $item=$m->ref('Item'); 
  
  $cItem=$this->add('CRUD');
  $cItem->setModel($item);
  
  $Total = $this->add('Frame')->setTitle('Total of Items')->set($item->sum('Total Price')->getOne());
  
  //ajax interaction to autofill description and price related to product
  if($cItem->form){
	 $p = $cItem->form->getElement('product');
	 $d= $cItem->form->getElement('description');
	 $r = $cItem->form->getElement('price');

		
	}
	
	$cItem->js('reload', $Total->js()->reload());
	
	
  
  

  if( $cItem->grid ) {
    $cItem->grid->addFormatter('description','grid/inline');  
    $cItem->grid->addFormatter('product','grid/inline')->editFields(array('product_id'));
    $cItem->grid->addFormatter('chart','grid/inline')->editFields(array('chart_id'));  
  }

  // show the transactions
  $this->add('H2')->set('Transactions');
  $cTrans=$this->add('CRUD');
  if($cTrans->grid) {
    $cTrans->grid->addColumn('expander','move');
  }
  $cTrans->setModel($m->ref('Transaction'),array('transdate','amount','contra_account','notes'));
  if( $cTrans->grid ) {
      $cTrans->grid->addFormatter('notes','wrap');
    //$cTrans->grid->addPaginator(10);
  }

  // only impact on ledger for invoice and general ledger. Not for order and quote.
  switch( $m->get('type') ) {
    case 'si':
    case 'pi':
    case 'gl':
	
      $this->add('H2')->set('Ledger');
	  
	  //TABS
	  $tabs = $this->add('Tabs');
      $ledger=$m->ref('Ledger');
		//tab CRUD Ledger
		$cLedger=$this->add('CRUD');
		$cLedger->setModel($ledger);
	  
		   if($cLedger->grid)
		   {
				$cLedger->grid->removeColumn('item'); //Hide item column
		   }
	  
	    //tab Grid Ledger
	     $gLedger = $this->add('Grid');
	     $gLedger->setModel(clone($ledger));
	     $gLedger->removeColumn('item'); //Hide item column
	  	  	  
	  $tabs->addTab('Ledger')->add($cLedger);
	  $tabs->addTab('Ledger Records')->add($gLedger);	 
    				
    }
  }
}