<?php
class Model_Item extends Model_Table {
  public $table='item';
  public $title_field='description';
  function init() {
    parent::init();
    $this->hasOne('Document');
    $this->hasOne('Product');
    $this->hasOne('Chart');
    $this->addField('description');
    $this->addField('serial');
    $this->addField('quantity');
    $this->addField('price');
    $this->hasMany('Ledger');
  }

  public function ledger() {
    
    $document=$this->ref('document_id');
    $contact=$document->ref('contact_id');
    $product=$this->ref('product_id');

    $transdate=$document->get('transdate');
    
    $taxContact=array();
    foreach($contact->ref('RuleChart_Tax') as $r) {
      if($r['startdate'] < $transdate ) {
        $taxContact[$r['chart_id']]=array(
          'rate'=>$r['rate'],
          'amount'=>$r['amount']
          );
      }
    }

    $taxProduct=array();
    foreach($product->ref('RuleChart_Tax') as $r) {
      if($r['startdate'] < $transdate ) {
        $taxProduct[$r['chart_id']]=array(
          'rate'=>$r['rate'],
          'amount'=>$r['amount']
          );
      }
    }
    
    $tax=array();
    foreach($taxProduct as $key=>$value) {
      if($taxContact[$key]) $tax[$key]=$value;
    }
      
    
 
    $chart=array();
    foreach($product->ref('RuleChart') as $r) $chart[$r['display']]=$r['chart_id'];
    foreach($contact->ref('RuleChart') as $r) $chart[$r['display']]=$r['chart_id'];
    
    
    $ledger=$this->ref('Ledger');
    $ledger->deleteAll();
    
    $productline=$this->get('price');
    foreach($tax as $t) {
      $taxline=$this->get('price') * $t['rate'] + $t['amount'];
      $productline=$productline-$taxline;
      $ledger->unload()
          ->set('document_id',$this->get('document_id'))
          ->set('chart_id',$t['chart_id'])
          ->set('amount', $taxline)
          ->set('transdate',$transdate)
          ->save();
      }
    
    $ledger->unload()
        ->set('document_id',$this->get('document_id'))
        ->set('chart_id',$chart['income'])
        ->set('amount',$productline) // opbrengst verkopen=credit=positive
        ->set('transdate',$transdate)
        ->save();
    $ledger->unload()
        ->set('document_id',$this->get('document_id'))
        ->set('chart_id',$chart['ar'])
        ->set('amount',-$this->get('price')) // ar=debiteuren=booking negative=debit
        ->set('transdate',$transdate)
        ->save();
  }

  public function ledgerTax() {
//    $tax=$this->ref('product_id')->ref('rule_tax_id')->;

  }

  function import() {
    $this->dsql()->truncate();
   
    $q=$this->api->db2->dsql();
    $q->table('orderitems')
      ->field($q->expr('(id-10000)'),'id')
      ->field($q->expr('(trans_id-10000)'),'document_id')
      ->field($q->expr('(parts_id-10000)'),'product_id')
      ->field('description')
      ->field('qty',null,'quantity')
      ->field('sellprice',null,'price')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }
   
    $q=$this->api->db2->dsql();
    $q->table('invoice')
      ->field($q->expr('(id-10000)'),'id')
      ->field($q->expr('(trans_id-10000)'),'document_id')
      ->field($q->expr('(parts_id-10000)'),'product_id')
      ->field('description')
      ->field('serialnumber',null,'serial')
      ->field('qty',null,'quantity')
      ->field('sellprice',null,'price')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }

  }



}
