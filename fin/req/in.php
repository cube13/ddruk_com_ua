<?
require("req/out.php");
class cls_in extends cls_out
{
	
var $kat_name;
	
	function in_kat_add($kat_name,$prihod_rashod)
	{		
		$this->sql_query="insert into fin_kat (id, name,prihod_rashod) values ('','$kat_name','$prihod_rashod')";
		$this->sql_execute();
		
		if($this->sql_err) return(11);
		return(0);
	}	
	
	function in_dengi_prihod_add($suma,$dengi_name,$kat_id)
	{
		$cur_date=date('U');
		$this->sql_query="insert into `fin_main`(id,suma,name,prihod_rashod,date,kat_id) values ('','$suma','$dengi_name','1','$cur_date','$kat_id')";
		$this->sql_execute();
		
		if($this->sql_err) return(11);
		return(0);
	}
	
	function in_dengi_rashod_add($suma,$dengi_name,$kat_id)
	{
		$cur_date=date('U');
		$this->sql_query="insert into `fin_main`(id,suma,name,prihod_rashod,date,kat_id) values ('','$suma','$dengi_name','0','$cur_date','$kat_id')";
		$this->sql_execute();
		
		if($this->sql_err) return(11);
		return(0);
	}
	
}
?>