<?php
class Crud_Model extends CI_Model{
	private $table;
	
    function set_table($table){
		$this->table=$table;
	}
	function get_data($fields,$relations,$options,$conditions,$search){
		$this->db->select('*, '.$this->table.'.'.$this->getPrimaryKey().' as \''.$this->getPrimaryKey().'\'');
		
		foreach($fields as $field => $label){
			$this->db->select($this->table.'.'.$field.' as \''.$label.'\'');
		}
		if(is_array($relations)){
			$i=0;
			foreach($relations as $relation){
				$table='table'.$i;
				$this->db->select($table.'.'.$relation['value'].' as \''.$relation['label'].'\'');
				$this->db->join($relation['table'].' as '.$table,$table.'.'.$relation['key'].'='.$this->table.'.'.$relation['field'],'left');
				$i++;
			}
		}
		
		if(is_array($options)){
			foreach($options as $option){
				//generates a CASE sql statement
				$statement='(CASE '.$this->db->dbprefix($this->table).'.'.$option['field'];
				foreach($option['options'] as $key=>$value)
					$statement.=' WHEN \''.$key.'\' THEN \''.$value.'\'';
				$statement.=' END) as \''.$option['label'].'\'';
				$this->db->select($statement);
			}
		}
        // conditions
		if(is_array($conditions)){
			foreach($conditions as $condition){
			 	$this->db->where($condition);
				
			}	
		}
		 // new search
		 //if(is_array($search)){
			
			if( isset( $_GET['search']['value'] ) && $_GET['search']['value'] != '' ){
			//	if( isset( $this->config['search'] ) && $this->config['search'] != '' ){
					// $rows = $rows->where( $this->config['search']." LIKE '%".$_POST['search']['value']."%'" );
					//$this->db->where( $this->config['search']." LIKE '%".$_POST['search']['value']."%'" );
					print_r($_GET['search']['value']);
					//die();
					$this->db->like($search,$_GET['search']['value']);
					
			//	}
			}
				
		//}
        // limit
        $limit = null;
        if($this->input->get('length')!= null && $this->input->get('length')!= -1){
            $limit = $this->input->get('length');
        }

        // offset
        $offset = null;
        if($this->input->get('start') != null){
            $offset = $this->input->get('start');
        }
		
		return $this->db->get($this->table,$limit,$offset);
	}
	function getViewDataById( $fields,$relations,$options,$id ){
		$this->db->select('*, '.$this->table.'.'.$this->getPrimaryKey().' as \''.$this->getPrimaryKey().'\'');
		$this->db->where($this->table.'.'.$this->getPrimaryKey(),$id);
		
		foreach($fields as $field => $label){
			$this->db->select($this->table.'.'.$field.' as \''.$label.'\'');
		}
		if(is_array($relations)){
			$i=0;
			foreach($relations as $relation){
				$table='table'.$i;
				$this->db->select($table.'.'.$relation['value'].' as \''.$relation['label'].'\'');
				$this->db->join($relation['table'].' as '.$table,$table.'.'.$relation['key'].'='.$this->table.'.'.$relation['field'],'left');
				$i++;
			}
		}
		
		if(is_array($options)){
			foreach($options as $option){
				//generates a CASE sql statement
				$statement='(CASE '.$this->db->dbprefix($this->table).'.'.$option['field'];
				foreach($option['options'] as $key=>$value)
					$statement.=' WHEN \''.$key.'\' THEN \''.$value.'\'';
				$statement.=' END) as \''.$option['label'].'\'';
				$this->db->select($statement);
			}
		}
		
		return $this->db->get($this->table);
	}
	function get_total($relations,$conditions){
		$this->db->select('count(*) as total');
        
        // think! in relation needed in total
        /*
        if(is_array($relations)){
			$i=0;
			foreach($relations as $relation){
				$table='table'.$i;
				$this->db->join($relation['table'].' as '.$table, $table.'.'.$relation['key'].'='.$this->table.'.'.$relation['field'], 'left');
				$i++;
			}
		}
        */
        // conditions
		if(is_array($conditions)){
			foreach($conditions as $condition){
				$this->db->where($condition);
			}
		}
		return $this->db->get($this->table)->row()->total;
	}
	function get_options($relation){
		if( $relation['where'] != '')
            $this->db->where($relation['where']);
        
        $rows = $this->db->get($relation['table'])->result_array();
		$options[0]='Select '.$relation['label'];
		foreach($rows as $row){
			$options[$row[$relation['key']]]=$row[$relation['value']];
		}
		return $options;
	}
	function getDataById($id){
		$this->db->where($this->getPrimaryKey(),$id);
		return $this->db->get($this->table)->row_array();
	}
	function get_field_data(){
		return $this->db->field_data($this->table);
	}
	function saveData($data){
		if($this->db->insert($this->table,$data)) return true;
	}
	function updateData($id,$data){
		$this->db->where($this->getPrimaryKey(),$id);
		if($this->db->update($this->table,$data)) return true;
	}
	function getPrimaryKey(){
        return 'id';// thinking about this
		$fields = $this->db->field_data($this->table);
		foreach($fields as $field){
			if($field->primary_key==1)
				return $field->name;
		}
	}
	function deleteData($id){
		$this->db->where($this->getPrimaryKey(),$id);
		if($this->db->delete($this->table)) return true;
	}
}