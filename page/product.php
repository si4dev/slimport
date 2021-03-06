<?php 

class Page_Product extends Page {
	function init(){
		parent::init();
		$this->add('h1')->set('Manage products');
		$this->add('hr');
		
		$m = $this->add('Model_product' ); //$p for product
		
		$c = $this->add('CRUD');
		
		$c->setModel($m, array('product_code', 'product_type', 'product_type_id', 'description', 'unit', 'purchase_price','sell_price', 'tax', 'tax_id'));
		
		if($c->grid){
			$c->grid->addPaginator(10);
			$c->grid->addQuickSearch(array('product_code', 'description'));
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