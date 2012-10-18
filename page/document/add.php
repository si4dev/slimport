<?php
class Page_document_add extends Page {
  function init() {
    parent::init();
	
		$f = $this->add('form');
		$md = $this->add('Model_document');
		$f->setModel($md);
		$f->addSubmit();
		$f->getElement('type')->set($_GET['type'])->disable();
		
  //loading contacts 'ar ' or 'ap' depending on type 
	switch($_GET['type']){
		case 'si':
		case 'so':
		case 'sq':
		$f->getElement('contact_id')->setModel('contact')->setType('ar');
		break;
		case 'pi':
		case 'po':
		case 'pq':
		$f->getElement('contact_id')->setModel('contact')->setType('ap');
		break;
	}
	
	if($_GET['type'] == 'si'){
		$f->getElement('chart_against_id')->getModel()->addCondition('type', 'ar');
	}
	
	if($_GET['type'] == 'pi'){
		$f->getElement('chart_against_id')->getModel()->addCondition('type', 'ap');
	}
		
	if($f->isSubmitted()){
		$md->save();
		$this->api->redirect('documents');
	}
	}
}