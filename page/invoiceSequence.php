<?php
//admin page for sequences of numbers of invoices
Class Page_invoiceSequence extends Page {
	function init(){
	parent::init();
	
	$m = $this->add('Model_invoiceSequence');
	$this->add('CRUD')->setModel($m);	
	}
}