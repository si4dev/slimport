<?php
class Model_Ledger2 extends Model_Table {
  public $table='ledger';
  function init() {
    parent::init();
      //$this->debug();
    $this->hasOne('Document');
    $this->hasOne('Chart');
    $this->hasOne('Item');
    $this->addField('amount');
    $this->addField('transdate');
    $this->addField('reference');
  }
  function addExtraFields() {
      $ch = $this->join('chart');
      $ch->addField('acc_nr');
      $ch->addField('chart_type');
      $ch->addField('category');
      $ch->addField('description');
      $ch->addField('business_id');

//      $dc = $this->join('document');

      $this->_dsql()->group('acc_nr')->group('chart_type')->group('category');
      $this->_dsql()->order('acc_nr')->order('chart_type')->order('category');
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

/*


    $q->table('ledger')
      ->join('document',null,'inner')
      ->join('chart',null,'inner')
      ->field('acc_nr')->group('acc_nr')
      ->field('chart_type')->group('chart_type')
      ->field('category')->group('category')
      ->field('description')
      ->field($q->expr('sum(if(amount<0,-amount,0))'),'debet') // debet
      ->field($q->expr('sum(if(amount>0,amount,0))'),'credit')  // credit
    //  ->where('ledger.transdate','>=','2011-01-01')
      ->where('ledger.transdate','<','2011-12-31')
      ->where('chart.business_id',$b->id)
  //    ->having('abs(sum(amount_credit))>',0.001)
  //    ->where('category','in',array('E','I'))
      ->order('category')->order('chart_type')->order('acc_nr')
      ;




 */
    
  
  