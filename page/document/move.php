<?php
class page_document_move extends Page {
  function init() {
    parent::init();
    
    $b=$this->api->business;
    $this->add('P')->set('Booking: '.$_GET['id']);
    $this->add('P')->set('Buseiness: '.$b['name']);
    
    $t=$this->add('Model_Transaction')->load($_GET['id']);
    $d=$t->ref('document_id')->loadBy('business_id',$b->id);

    $f=$this->add('Form');
    
    $m=$b->ref('Match');
    $f->addField('radio','type')->setValueList(array('ar','ap','bank'));
    
// TODO: Here 'ar' should be the value from the radio box above
    $m->getElement('contact_id')->getModel()->addCondition('type','ar');

    $m->getElement('chart_tax_id')->getModel()->setTransDate($t->get('transdate')); // find tax for this specific date
    
// TODO: Here 'ar' should be the value from the radio box above
    $m->getElement('chart_revenue_id')->getModel()->addCondition('type','ar'); 

// TODO: When the tax dropdown is changed, then fill in the default here.
// so now it's static 286 but it should be $f->get('chart_tax_id') however only when changed
    $tax=$this->add('Model_Chart_Tax');
    $tax->setTransDate($t->get('transdate'))->load(286); // ->load($f->get('chart_tax_id'));
    $m->getElement('tax_rate')->set($tax->get('tax_rate'));


    $f->setModel($m);
    
    
    
    
  }
}
