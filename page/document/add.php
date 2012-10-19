<?php
class Page_document_add extends Page {
  function init() {
    parent::init();
	
		$f = $this->add('form');
		$md = $this->add('Model_document');
		$f->setModel($md);
		$f->addSubmit();
		$f->getElement('type')->set($_GET['type'])->disable();
		
		$this->api->stickyGET('type');
		
  //loading contacts 'ar ' or 'ap' depending on type 
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