<?php
class page_document_move extends Page {
  function init() {
    parent::init();

    $this->api->stickyGET('id');
    
    $b=$this->api->business;
    $this->add('P')->set('Booking: '.$_GET['id']);
    $this->add('P')->set('Buseiness: '.$b['name']);
    
    $t=$this->add('Model_Transaction')->load($_GET['id']);
    $d=$t->ref('document_id')->loadBy('business_id',$b->id);

    $f=$this->add('Form_Move');
    
    $m=$b->ref('Match');
    $f->addField('dropdown','type')->setValueList(array('ar'=>'ar','ap'=>'ap','bank'=>'bank'));
    
// TODO: Here 'ar' should be the value from the radio box above
    $type = (isset($_GET['type'])) ? $_GET['type'] : 'ar' ;
    $m->getElement('contact_id')->getModel()->addCondition('type',$type);

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


class Form_Move extends Form {
    function setModel($m) {
        parent::setModel($m);

        $type = $this->getElement('type');
        $this->js('change',$this->js()->atk4_form('reloadField','contact_id',array(
                            $this->api->url(),
                            'type'=>$type->js()->val())
        ));

    }
}