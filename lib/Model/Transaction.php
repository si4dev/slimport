<?php

class Model_Transaction extends Model_Table {
  public $entity_code='transaction';
  function init(){
    parent::init();

    $this->hasOne('Batch');
    $this->addField('own_account');
    $this->addField('currency');
    $this->addField('transdate');
    $this->addField('amount');
    $this->addField('remote_account');
    $this->addField('remote_owner');
    $this->addField('description');
    $this->addField('notes');
    $this->addField('raw');
    $this->addField('hash');
    $this->hasOne('Document');
    
    
    
    $this->addHook('beforeSave',function($m){
      });
    }
    
}