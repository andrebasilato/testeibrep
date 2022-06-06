<?php
//error_reporting(E_ALL);

class MySqlTable {
	
	var $tableName;
	var $link;
	var $db_name;
	
	function MySqlTable($tableName='') {
	  	$this->tableName = $tableName;
		$c1 = Db_connection::getInstance();
		$db_connection = $c1->getDbConnection();
		$this->link = $db_connection['link'];
		$this->db_name = $db_connection['db_name'];
	}
	
	function escape($value) {
		$value = mysqli_real_escape_string($this->link,$value);
		return $value;
	}
	
	function loadByFields($fields, $values, $options=array()) {
		$fields = explode(',',$fields);
		$values = explode(',',$values);
		$selected_fields = $options['fields'];
		$display = $options['display'];
		
		$sqlSelect = $this->getSQLSelectByFields($selected_fields);
		
		$query = "SELECT ".$sqlSelect." FROM ". $this->tableName ." WHERE";
		for($i=0; $i<count($fields); $i++) {
			if($i==0) $query .= " " .$this->escape($fields[$i]). " = '" .$this->escape($values[$i]). "'";
			else $query .= " AND " .$this->escape($fields[$i]). " = '" .$this->escape($values[$i]). "'";
		}
		
		if($options!="") {
			foreach($options as $i => $v) {
				if($i=='group') $groupByCond = " GROUP BY ".$this->escape($v)." ";
				if($i=='order') $orderByCond = " ORDER BY ".$this->escape($v)." ";
				if($i=='limit') $limitCond = " limit ".$this->escape($v)." ";
			}
			$query .= $groupByCond.$orderByCond.$limitCond;
		}
		
		if($display=='1') echo $query;
		
		return $this->loadArrayFromQuery($query);
	}
	
	function selectAll($options=array()) {
		$fields = $options['fields'];
		$display = $options['display'];
		
		$sqlSelect = $this->getSQLSelectByFields($fields);
		
		$array = $this->loadIntoArray();
		$query = "SELECT ".$sqlSelect." FROM ". $this->tableName." WHERE 1 ";
		foreach($array as $key=>$value) {
			if($value!="")
				$query .= " AND ".$key." = '".$this->escape($value)."'";
		}
		
		if($options!="") {
			foreach($options as $i => $v) {
				if($i=='group') $groupByCond = " GROUP BY ".$this->escape($v)." ";
				if($i=='order') $orderByCond = " ORDER BY ".$this->escape($v)." ";
				if($i=='limit') $limitCond = " limit ".$this->escape($v)." ";
			}
			$query .= $groupByCond.$orderByCond.$limitCond;
		}
		
		if($display=='1') echo $query;
		
		return $this->loadArrayFromQuery($query);
	}
	
	function customQuery($query) {
		$return = $this->loadArrayFromQuery($query);
		return $return;
	}
	
	function getSQLSelectByFields($fields) {
		$fieldsArray = explode(',',$fields);
		
		if($fields=='') {
			$sqlSelect = "*";
		}
		else {
			$sqlSelect = '';
			$i=0;
			foreach($fieldsArray as $value) {
				if($i==0) $sqlSelect = $value;
				else $sqlSelect = $sqlSelect.', '.$value;
				$i++;
			}
		}
		return $sqlSelect;
	}
	
	function insert($options=array()) {
		$display = $options['display'];
		
		$array = $this->loadIntoArray();
		
		$query = "INSERT INTO ". $this->tableName." (";
		$s = "";
		foreach($array as $key => $value) {
			if($value || is_numeric($value)) {
				$query.= $s.$key;
				$s = ", ";
			}
		}
		$query .= ") VALUES (";
		$s = "";
		foreach($array as $value) {
			if($value || is_numeric($value)) {
				$query.= $s."'".$this->escape($value)."'";
				$s = ", ";
			}
		}
		$query .= ")";
		
		if($display=='1') echo $query;
		
		$this->executeQuery($query);
		$result = mysqli_insert_id($this->link);
		
		return $result;
	}
	
	function delete($id) {
		$query = "DELETE FROM ". $this->tableName." WHERE id = '".$this->escape($id)."'";
		$this->executeQuery($query);
	}
	
	function updateByFields($fields,$id,$options=array()) {
		$display = $options['display'];
		
		// Get the id of the table...
		$array = $this->loadIntoArray();
		$i=0;
		foreach($array as $key=>$value) {
			if($i==0) {
				$table_id=$key;
				break;
			}
			$i++;
		}
		// END Get the id of the table...
		
		$query = "UPDATE ". $this->tableName." SET ";
		$i=0;
		foreach($fields as $key=>$value) {
			if($i==0) $query.= $key." = '".$this->escape($value)."'";
			else $query.= ", ".$key." = '".$this->escape($value)."'";
			$i++;
		}
		
		if(is_array($id)) {
			$query .= " WHERE 1 ";
			foreach($id as $key=>$value) {
				$query .= " AND ".$key." = '".$this->escape($value)."'";;
			}
		}
		else {
			$query .=" WHERE ".$table_id." = '".$this->escape($id)."'";
		}
		
		if($display=='1') echo $query;
		
		return $this->executeQuery($query);
	}
	
	function update($id) {
		$array = $this->loadIntoArray();
		$query = "UPDATE ". $this->tableName." SET ";
		$s = "";
		foreach($array as $key=>$value) {
			if($value) {
				$query.= $s.$key." = '".$this->escape($value)."'";
				$s = ",";
			}
			elseif(is_numeric($value)) {
				$query.= $s.$key." = '".$this->escape($value)."'";
				$s = ",";
			}
		}
		$query .=" WHERE id = '".$id."'";
		//echo $query;
		
		$this->executeQuery($query);
	}
	
	/*
	function counter() {
		$sql = 'UPDATE count set count=count+1 WHERE id=1';
		mysqli_query($this->link, $sql);
	}
	*/
	
	function executeQuery($query) {
		//echo $query.'<br>';
		//echo 'db: '.$this->db_name.'<br>';
		mysqli_select_db($this->link, $this->db_name);
		//$this->counter();
		$result = mysqli_query($this->link, $query);
		return $result;
	}
	
	//execute the query and return an array of corresponding MySqlTable inherited object
	function loadArrayFromQuery($query) {
		$result = $this->executeQuery($query);
		$return = array();
		while ($rows = mysqli_fetch_assoc($result)) {
			$return[] = $rows;
		}
		return $return;
	}
}

?>
