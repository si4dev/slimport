<?php
class Model_Connect_Table extends Model_Table {
  function init() {
    parent::init();  
  }
  // to use a second database connection
  function initQuery(){
      $this->dsql=$this->api->db2->dsql();
      $table=$this->table?:$this->entity_code;
      if(!$table)throw $this->exception('$table property must be defined');
      $this->dsql->table($table,$this->table_alias);
      $this->dsql->default_field=$this->dsql->expr('*,'.
          $this->dsql->bt($this->table_alias?:$table).'.'.
          $this->dsql->bt($this->id_field))
          ;
  }
  
}
