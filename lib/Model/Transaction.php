<?php

class Model_Transaction extends Model_Table {
     public $entity_code='bank_transaction';
     function init(){
        parent::init();

        $this->hasOne('Batch');
        $this->addField('raw');
        $this->addField('account');
        $this->addField('currency');
        $this->addField('date');
        $this->addField('amount');
        $this->addField('contra_account');
        $this->addField('description');
        $this->addField('notes');
        
    }
}