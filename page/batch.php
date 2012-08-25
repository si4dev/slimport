<?php
class page_batch extends Page {
  function init() {
    parent::init();
  
  
    $b=$this->add('Model_Business')->load(2);
  
  
/*  
    $f=$this->add('Form');
    $f->addField('upload','upload');
*/
          /*     
    
    $f=$this->add('MVCform');
    $f->setModel('Batch');
    $f->addSubmit();
*/    
    
    $crud=$this->add('CRUD');
    $crud->setModel($b->ref('Batch'));

    if($crud->grid){
      $crud->grid->addColumn('button','import');
      if($_GET['import']){
        $r = $crud->grid->getModel()->loadData($_GET['import'])->import();
       
        $crud->grid->js(null,$crud->grid->js()->univ()->successMessage('Imported batch #'.
        $_GET['import'].''.$r))->reload()->execute();
      }
      $crud->grid->addColumn('button','transactions');
        if($_GET['transactions']){
          //$this->api->memorize('shop',$_GET['pricelist']);
           // $c->grid->js(null,$c->grid->js()->univ()->successMessage('Imported batch #'.$_GET['pricelist'].''.$r))->reload()->execute();
          
          // learn how to redirect to other page. http://agiletoolkit.org/doc/grid/interaction 
          // replace dialogURL() with location() and drop first argument. 
          // also for non ajax add api redirect http://agiletoolkit.org/doc/form/submit
          $p=$this->api->getDestinationURL(
                'transaction',array(
                'batch'=> $_GET['transactions']
                ));
          $crud->js()->univ()->location($p)->execute();
        
          $this->api->redirect($p);
        }
    }


  }
}
