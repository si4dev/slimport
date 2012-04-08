<?php
class page_transaction extends Page {
  function init() {
    parent::init();

    $this->add('Text')->set('do the transaction stuff');
    
    $m=$this->add('Model_BankTransaction')->setMasterField('batch_id', 13);
    $f=$this->add('MVCForm')->setModel($m);
    
    $m->load(350);
    
    /*
    $f=$this->add('Form');
    
    
    $v=$f->addField('dropdown','name');
    $v->setModel('Sqlledger_Vendor');
    $f->addSubmit('Submit');
    
          if ($f->isSubmitted()) { 
          $varmsg = 'You told me vendor is '.$f->get('name');
          $f->js()->univ()->alert($varmsg)->execute();
          }
    */
    }
}
