<?php 

Class Model_Sequences extends Model_Table {
		public $table='sequences';
		
		function init(){
			parent::init();			
			$this->addField('sequence');
			$this->addField('type');//si or pi
			
		}
		
		function getNext($type){
			$next = $this->loadBy('type', $type)->get('sequence');
			$next++;
			$this['sequence'] = $next;
			$this->save();
			return $next;
		}
		
}