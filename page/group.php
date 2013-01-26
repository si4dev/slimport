<?php 

class Page_group extends Page {
	function init(){
		parent::init();
		
		$this->add('H1')->set("Manage Groups");
		$this->add('H3')->set("Contact group");
			$cg = $this->add('Model_Contact_Group'); //$cg for contact group
			$c = $this->add('CRUD');
			$c->setModel($cg);
		
		$this->add('H3')->set("Product Group");
			$pg = $this->add('Model_ProductGroup'); //$pg for product group
			$p = $this->add('CRUD');
			$p->setModel($pg);
			
	}
}