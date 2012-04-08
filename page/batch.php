<?php
class page_batch extends Page {
  function init() {
    parent::init();
  
  
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
    $crud->setModel('Batch');

    if($crud->grid){
      $crud->grid->addColumn('button','import');
      if($_GET['import']){
        $r = $crud->grid->getModel()->loadData($_GET['import'])->import();
       
        $crud->grid->js(null,$crud->grid->js()->univ()->successMessage('Imported batch #'.
        $_GET['import'].''.$r))->reload()->execute();
      }
    }


  }
}
