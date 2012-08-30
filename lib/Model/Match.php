<?php
class Model_Match extends Model_Table {
  public $table='match';
  function init() {
    parent::init();
    
    $this->hasOne('Business');
    $this->hasOne('User')->system(true)->defaultValue($this->api->auth->model->id);
    $this->hasOne('Contact');
    $this->hasOne('Chart','chart_revenue_id');
    $this->hasOne('Chart','chart_tax_id');
    $this->addField('tax_rate');
    $this->addField('match_own_account');
    $this->addField('match_remote_account');
    $this->addField('match_remote_owner');
    $this->addField('match_remote_keyword1');
    $this->addField('match_remote_keyword2');
  }
}
