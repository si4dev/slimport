<?php 

class Page_group extends Page {
	function init(){
		parent::init();
		
		$this->add('h1')->set("Manage Groups");
		$this->add('h3')->set("Contact group");
			$cg = $this->add('Model_contact_group'); //$cg for contact group
			$c = $this->add('CRUD');
			$c->setModel($cg);
		
		$this->add('h3')->set("Product Group");
			$pg = $this->add('Model_product_group'); //$pg for product group
			$p = $this->add('CRUD');
			$p->setModel($pg);
			
	}
}