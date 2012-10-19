<?php
class Page_Documents extends Page {
  function init() {
    parent::init();
	
    /* replaced by tree menu...
	if(!isset($_GET['type'])){
		$this->api->redirect('selectType');
	}
	*/
	//else{
	$type = $_GET['type'];
	$this->api->stickyGET('type');
	
	//button ADD to add a document.
	$f = $this->add('Form');
	$add = $f->addSubmit()->setLabel('ADD document');
	if($f->isSubmitted()){
		$this->api->redirect('document_add');
	}
	
	$this->add('hr');
	
    
    $c=$this->add('Grid');
    $m=$this->api->business->ref('Document')->addCondition('type',$type);
    
    $m->getField('business')->visible(false);
    $c->setModel($m);
    $c->addPaginator(20);
    $c->addQuickSearch(array('number'));  
    
    
    $c->addColumn('button','document');
    if($_GET['document']){
      $p=$this->api->getDestinationURL(
          'document',array(
          'document'=> $_GET['document']
        ));
      $c->js()->univ()->location($p)->execute();
      $this->api->redirect($p);
    }
	
    
    //}
	
  }
}