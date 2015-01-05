<?php
include_once "mysql_koneksi.php";
class MysqlQuery{
	
	function insertQuery($table,$field_db,$fieldValue){
		global $mysqli;
		if($mysqli->query("INSERT INTO ".$table." (".$field_db.") VALUES (".$fieldValue.")") or die($mysqli->error)){
			return true;
			}else{
			return false;
		}
	}
	
	function updateQuery($table, $fieldUpdate, $field, $operator){
	global $mysqli;
		if($mysqli->query("UPDATE ".$table." SET ".$fieldUpdate." WHERE ".$field." = '".$operator."'")or die($mysqli->error)){
			return true;
			}else{
			return false;
		}	
	}
	
	function deleteQuery($table, $field, $record) {
	global $mysqli;
		$result = $mysqli->query("DELETE FROM ".$table." WHERE $field = '".$record."'")or die($mysqli->error);
		return $result;
	}

	function getRecord($table, $field_db) {
	global $mysqli;
		$result = $mysqli->query("SELECT ".$field_db." FROM ".$table ) or die($mysqli->error);
		return $result;
	}
}
?>