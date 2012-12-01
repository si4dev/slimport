<?php
class Page_Document extends Page {
 function init() {
  parent::init();
  
  $b=$this->api->business;
  
  $this->api->stickyGET('document');
  $this->add('P')->set('logged in as '.$this->api->auth->get('email'));
  
  $m=$b->ref('Document')->load($_GET['document']);
   
  //$this->add('Grid')->setModel('contact')->setType('ap');
   
  // f for form and m for model used for the main form / model of this page. Then easy to reuse page snippets
  $f=$this->add('Form');  
  $f->setModel($m);
  $f->getElement('type')->set($_GET['type'])
						->disable();
  
  if(isset($_GET['type'])){
	
		$this->api->stickyGET('type');
		
	  //loading contacts 'ar ' or 'ap' depending on type 
		switch($_GET['type']){
			case 'si':
			case 'so':
			case 'sq':
			$f->getElement('contact_id')->setModel('contact')->setType('ar');
			break;
			case 'pi':
			case 'po':
			case 'pq':
			$f->getElement('contact_id')->setModel('contact')->setType('ap');
			break;
		}
		if($_GET['type'] == 'si'){
			$f->getElement('chart_against_id')->getModel()->addCondition('type', 'ar');
		}
		elseif($_GET['type'] == 'pi'){
			$f->getElement('chart_against_id')->getModel()->addCondition('type', 'ap');
		}
  }
  
  
  // show the line items, so the products on the invoice/order/quote
  $this->add('H2')->set('Line Items');
  $item=$m->ref('Item');   
  $cItem=$this->add('CRUD');
  $cItem->setModel($item);
  
  $Total = $this->add('Frame')->setTitle('Total of Items')->set($item->sum('total_price')->getOne());
  
		  if($cItem->form){
			//ajax interaction to autofill description and price related to product
			
			$f = $cItem->form;					
					
			$p = $f->getElement('product');
			$d= $f->getElement('description');
			$r = $f->getElement('price');
			$tax = $f->getElement('tax_id');
			
			
			$p->js('change')->univ()->ajaxec($this->api->getDestinationURL(),array('product' =>$p->js()->val()) );
			// $p->js('change', $f->js()->reload(array('product' => $p->js()->val()))); 
			
				if($_POST['product']){
					$product = $this->add('Model_product');
					$product->TryloadBy('productcode', $_POST['product']);
										
					$this->js(null, array(
					    $d->js()->val($product['description']),
						$r->js()->val($product['sellprice']),
						$tax->js()->val($product['tax_id'])
					
					))->execute();
					/* OLD autofill 
					$product = $this->add('Model_product');
					$product->TryloadBy('productcode', $_GET['product']);
					
					if($product->loaded()){
					  $p->set($_GET['product']);
					  $d->set($product['description']);
					  $r->set($product['sellprice']);
					  
					  if(isset($product['tax_id']) && $product['tax_id'] != 0){
							$tax->set($product['tax_id']);
					  } */
					}
				} 		
			
	
	$cItem->js('reload', $Total->js()->reload());

  if( $cItem->grid ) {
    $cItem->grid->addFormatter('description','grid/inline');  
    $cItem->grid->addFormatter('product','grid/inline')->editFields(array('product_id'));
	$cItem->grid->getColumn('product')->makeSortable();
	//$tax_type = $cItem->grid->getColumn('product_tax_type');
	//$tax_type->set($item['product']['tax_type']);
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
	  
	  //TABS Ledger
	  $tabs = $this->add('Tabs');
      $ledger=$m->ref('Ledger');
	  
	   //tab Grid Ledger Records
	     $gLedger = $this->add('Grid');
	     $gLedger->setModel($ledger);
	     $gLedger->removeColumn('item'); //Hide item column
		 
		//tab CRUD Ledger
		 $clonedLedger = clone($ledger);
		 $clonedLedger->addCondition('item_derived', 0);
		 $cLedger=$this->add('CRUD');
		 $cLedger->setModel($clonedLedger);
		 
		   if($cLedger->grid){
				$cLedger->grid->removeColumn('item'); //Hide item column
		   }	    
	  	  	  
	    $tabs->addTab('Ledger')->add($cLedger);
	    $ledgerRecords = $tabs->addTab('Ledger Records')->add($gLedger);	
	    $cItem->js('reload', $ledgerRecords->js()->reload()); //refresh grid when item added
	  
    }
  }
}