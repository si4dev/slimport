<?php 

class Page_Product extends Page {
	function init(){
		parent::init();
		
		$p = $this->add('Model_product');
		
		$c = $this->add('CRUD');
		$c->setModel($p);
		if($c->grid){
			$c->grid->removeColumn('business');
			$c->grid->addPaginator(10);
		}
		
		if($c->form){
			$f = $c->form;
			$sequence = $this->add('Model_Sequences');
			$pcode = $sequence->getNext('product');
			$code = $f->getElement('productcode')->set($pcode);
			$code->disable();
		}
	}
}