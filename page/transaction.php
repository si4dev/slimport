<?php
class page_transaction extends Page {
  function init() {
    parent::init();

    $b=$this->api->business;
    $this->add('H1')->set('Transactions');
    $this->add('Text')->set('do the transaction stuff '. $b->get('name'));
    
    $t=$b->ref('Batch')->load($this->api->memorize('batch',$_GET['batch']))->ref('Transaction_Suggestion');
    $g=$this->add('grid');
    $g
      ->addPaginator(15)
      ->addColumn('expander','booking')
      ->setModel($t,array('suggestion','account','currency','date','amount','contra_account','description','notes'));
    $g->columns['notes']['thparam']=' style="color:red;width: 30px"'; 
    
    //$m=$this->add('Model_BankTransaction')->setMasterField('batch_id', 13);
    //$f=$this->add('MVCForm')->setModel($m);
    
    //$m->load(350);
    
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
