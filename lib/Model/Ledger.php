<?php
class Model_Ledger extends Model_Table {
  public $table='ledger';
  function init() {
    parent::init();
    $this->hasOne('Chart');
    $this->hasOne('Document');
    $this->hasOne('Item');
    $this->addField('amount');
    $this->addField('transdate');
    $this->addField('reference');
	$this->addField('item_derived')->type('boolean')->defaultValue(false);
	//ALTER TABLE `ledger` ADD `item_derived` BOOLEAN NOT NULL
  }


  function sl_import($trans_id) {
    
    $this->deleteAll();
    
    if(!isset($this->sl_chart)) {
      $this->sl_chart=$this->add('Model_SqlledgerRef')->addCondition('chart_id','', $this->dsql->expr('is not null') );
    }

    $q=$this->api->db2->dsql();
    $q->table('acc_trans')
      ->field('chart_id')
      ->field('amount')
      ->field('transdate')
      ->field('source',null,'reference')
      ->where('trans_id',$trans_id)
      ;
    
    foreach($q as $row) {
      if($row['chart_id']) {
        $row['chart_id']= $this->sl_chart->loadBy('sl_id',$row['chart_id'])->get('chart_id');
        $this->unload()
          ->set($row)
          ->save();
      } else {
        print_r($row);
        echo "no chart for this ledger";
      }
    }
      
  }
  

}
    
  
  