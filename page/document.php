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
	if($_GET['type'] == 'pi'){
		$f->getElement('chart_against_id')->getModel()->addCondition('type', 'ap');
	}
  }
  else
  {
	
  }
  
  
  // show the line items, so the products on the invoice/order/quote
  $this->add('H2')->set('Line Items');
  $item=$m->ref('Item'); 
  
  $cItem=$this->add('CRUD');
  $cItem->setModel($item);
  
  $Total = $this->add('Frame')->setTitle('Total of Items')->set($item->sum('total_price')->getOne());
  
  //ajax interaction to autofill description and price related to product
  if($cItem->form){
	//get product code value $p->js()->val() - 
	// ->send ajax request to load product description and price 
	//->set form_fields with return.
	
	$f = $cItem->form;
	$p = $f->getElement('product');
	$d= $f->getElement('description');	
	$r = $f->getElement('price');
	
	//send the ajax request and add values to the form fields
		$p->js('change', $f->js()->reload(array('product' => $p->js()->val())) );
	
	if($_GET['product']){
		$product = $this->setModel('product');
		$product->TryloadBy('productcode', $_GET['product']);
		if($product->loaded()){
		  $desc = $product['description'];
		  $price = $product['sellprice'];
		  $p->set($_GET['product']);
		  $d->set($desc);
		  $r->set($price);
	    }
	}
	
	
	  if($f->isSubmitted()){
			//add caculated ledger
			$tledger = $m->ref('Ledger');
			$tledger['document'] = $_GET['document'];
			$tledger['chart_id'] = $f->get('chart_id');
			$tledger['item_id'] = '0';
			$amount = $f->get('quantity') * $f->get('price');
			$tledger['amount'] = $amount;
			$tledger['item_derived'] = true;
			$tledger->save();		
		}
		
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
	  
	  //tab Grid Ledger Records
	     $gLedger = $this->add('Grid');
	     $gLedger->setModel($ledger);
	     $gLedger->removeColumn('item'); //Hide item column
		 
		 $Total->js('reload', $gLedger->js()->reload()); //refresh grid when item added
		 
		//tab CRUD Ledger
		$clonedLedger = clone($ledger);
		$clonedLedger->addCondition('item_derived', 0);
			$cLedger=$this->add('CRUD');
			$cLedger->setModel($clonedLedger);
	  
		   if($cLedger->grid)
		   {
				$cLedger->grid->removeColumn('item'); //Hide item column
		   }
	  
	    
	  	  	  
	  $tabs->addTab('Ledger')->add($cLedger);
	  $lr = $tabs->addTab('Ledger Records')->add($gLedger);	
	  $cItem->js('reload', $lr->js()->reload());
	  
    }
  }
}