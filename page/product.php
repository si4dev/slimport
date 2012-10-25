<?php 

class Page_Product extends Page {
	function init(){
		parent::init();
		
		$p = $this->add('Model_product');
		
		$c = $this->add('CRUD');
		$c->setModel($p);
		if($c->grid){
			$c->grid->removeColumn('business_id');
			$c->grid->addPaginator(10);
		}
	}
}