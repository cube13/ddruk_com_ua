﻿<?
require("req/vars.php");
class cls_mysql extends cls_vars
{
	var $sql_login="root";
	var $sql_pass="Baron_13";
	var $sql_database="systema";
//        var $sql_database="ddruk";
	var $sql_host="localhost";
	var $conn_id;
	var $sql_query;
	var $sql_err;
	var $sql_res;
	
	function sql_connect()
	{
		$this->conn_id=mysql_connect($this->sql_host,$this->sql_login,$this->sql_pass);
		mysql_select_db($this->sql_database);
		return(0);
	}
	
	function sql_execute()
	{
		$this->sql_res=mysql_query($this->sql_query,$this->conn_id);
		$this->sql_err=mysql_error();
//echo "->".$this-/sql_query;
//echo "<br>".mysql_error();
		return(0);
	}
	
	function sql_close()
	{
		mysql_close($this->conn_id);
		return(0);
	}
}
?>