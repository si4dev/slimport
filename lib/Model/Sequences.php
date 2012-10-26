<?php 

Class Model_Sequences extends Model_Table {
		public $table='sequences';
		
		function init(){
			parent::init();			
			$this->addField('sequence');
			$this->addField('type');//si or pi
			$this->hasOne('Business');
			$this->addCondition('business_id', $this->api->business->id);			
		}
		
		function getNext($type){
			$next = $this->loadBy('type', $type)->get('sequence');
			$next++;
			$this['sequence'] = $next;
			$this->save();
			return $next;
		}
		
}