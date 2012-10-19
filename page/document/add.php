<?php
class Page_document_add extends Page {
  function init() {
    parent::init();
		
		$this->api->stickyGET('type');
	
		$f = $this->add('form');
		$md = $this->add('Model_document');
		$f->setModel($md);
		
		//type set by default
		$f->getElement('type')->set($_GET['type'])->disable();
		
		//autonummer for invoices.. could be added as hook to model later ..
		$num = $this->add('Model_invoiceSequence');
		$number = $num->getNext($_GET['type']);
		$f->getElement('number')->set($number)->disable();
		
		$f->addSubmit();
		
		
		
		
		
  //loading contacts and chart 'ar ' or 'ap' depending on type 
	switch($_GET['type']){
		case 'si':
		case 'so':
		case 'sq':
		$f->getElement('contact_id')->setModel('contact')->setType('ar');
		$f->getElement('chart_against_id')->getModel()->addCondition('type', 'ar');
		break;
		case 'pi':
		case 'po':
		case 'pq':
		$f->getElement('contact_id')->setModel('contact')->setType('ap');
		$f->getElement('chart_against_id')->getModel()->addCondition('type', 'ap');
		break;
	}
	

		
	if($f->isSubmitted()){
		$md->save();
		$this->api->redirect('documents');
	}
	}
}