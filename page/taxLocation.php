<?php
class page_TaxLocation extends Page {
	function init(){
		parent::init();
			$this->add('CRUD')->setModel('TaxLocation');			
	}
}	