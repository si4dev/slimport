<?php 

class Page_TaxLedger extends Page {
		function init(){
			parent::init();
			
			$this->add('CRUD')->setModel('taxLedger');
			
		}
}