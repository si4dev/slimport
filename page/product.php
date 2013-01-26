<?php 

class Page_Product extends Page {
	function init(){
		parent::init();
		$this->add('H1')->set('Manage products');
		$this->add('HR');
		
		$m = $this->add('Model_Product' ); //$p for product
		
		$c = $this->add('CRUD');
		
		$c->setModel($m, array('productcode', 'product_type', 'product_type_id', 'description', 'unit', 'purchase_price','sellprice', 'Tax', 'tax_id'));
		
		if($c->grid){
			$c->grid->addPaginator(10);
			$c->grid->addQuickSearch(array('productcode', 'description'));
			$c->grid->removeColumn('product_type_id');
			$c->grid->removeColumn('tax_id');
		}
		
		if($c->form){
			$f = $c->form;

			
			
			if($f->isSubmitted()){				
				$tp = $f->getModel();
				$tp['business_id'] = $this->api->business->id;		//set business id by default					
				$tp->save();			
			}
		}
		
	}
}