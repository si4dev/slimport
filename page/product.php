<?php 

class Page_Product extends Page {
	function init(){
		parent::init();
		
		$p = $this->add('Model_product'); //$p for product
		
		$c = $this->add('CRUD');
		$c->setModel($p);
		if($c->grid){
			$c->grid->removeColumn('business');
			$c->grid->addPaginator(10);
			$c->grid->addQuickSearch(array('productcode', 'description'));
		}
	}
}