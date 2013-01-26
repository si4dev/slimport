<?php
class page_batch extends Page {
  function init() {
    parent::init();
  
  
    $b=$this->api->business;
  
  
/*  
    $f=$this->add('Form');
    $f->addField('upload','upload');
*/
          /*     
    
    $f=$this->add('MVCform');
    $f->setModel('Batch');
    $f->addSubmit();
*/    

    if(!$shop_id=$_GET['connect']) {
      $this->add('Hint')->set('First select what to connect to'); 
      return;
    }
    
    $this->api->stickyGET('connect');
    $c=$this->add('CRUD');
    $c->setModel($b->ref('Connect')->load($_GET['connect'])->ref('Batch'));

    if($c->grid){
      $c->grid->addColumn('button','import');
      if($_GET['import']){
        $r = $c->grid->getModel()->load($_GET['import'])->import();
       
        $c->grid->js(null,$c->grid->js()->univ()->successMessage('Imported batch #'.
        $_GET['import'].''.$r))->reload()->execute();
      }
      $c->grid->addColumn('button','transactions');
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
          $c->js()->univ()->location($p)->execute();
        
          $this->api->redirect($p);
        }
    }

  }
}
