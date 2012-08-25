<?php
class page_test extends Page {
    function init(){

        parent::init();
        $page=$this;

        $form=$this->add('Form');

        $name=$form->addField('autocomplete','lastname','Complex Lookup/Add')->setModel('Contact');
        $form->getElement('lastname')->js('change',$form->js()->submit());

        $form2=$this->add('MVCForm');
        $model = $form2->setModel('Contact');
        if($_GET['id'])$model->load($_GET['id']);
        $form2->addSubmit();
        if($form2->isSubmitted()){
            $form2->update();
            $form2->js()->reload()->execute();
        }

        if($form->isSubmitted()){
            $form2->js()->reload(array('id'=>$form->get('lastname')))->execute();
        }
    }
}



class Page_Test2 extends Page {
  function init() {
    parent::init();

/*
        $p=$this;
    
    $f=$p->add('Form');
    $r=$f->addField('reference','name');
    $r->setModel('Contact');
    $r->includeDictionary(array('lastname'));
    $s=$f->addField('line','lastname');

    $r->js(true)->univ()->bindFillInFields(array('lastname'=>$s));
*/
    }
}
