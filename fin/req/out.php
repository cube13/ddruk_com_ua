<?
require("req/utils.php");
class cls_out extends cls_utils

{
var $r_count;
var $day_rashod;
var $day_prihod;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function out_day($day,$month,$year)
{

$sday=date('U',mktime(0,0,0,$month,$day,$year));
$eday=date('U',mktime(23,59,59,$month,$day,$year));

$this->sql_query="select * from `fin_main` where `prihod_rashod`='0' and date>='$sday'
and date<='$eday'";
$this->sql_execute();
if($this->sql_err) return(11);
$day_sum_rashod=0;
while(list($id,$suma, $name, $date,$pr_ra,$kat_id,$kat_name,$receipt_id)=mysql_fetch_row($this->sql_res))
{
$day_sum_rashod=$day_sum_rashod+$suma;
}	
$this->day_rashod=$day_sum_rashod;

$this->sql_query="select * from `fin_main` where `prihod_rashod`='1' and date>='$sday'
and date<='$eday'";
$this->sql_execute();
if($this->sql_err) return(11);
$day_sum_rashod=0;
while(list($id,$suma, $name, $date,$pr_ra,$kat_id,$kat_name,$receipt_id)=mysql_fetch_row($this->sql_res))
{
$day_sum_rashod=$day_sum_rashod+$suma;
}
$this->day_prihod=$day_sum_rashod;

		
return 0;
}	

function out_kat($p_r,$cur_month,$cur_year=0)
{		
if(!$cur_month) {$cur_month=date('m');}
if(!$cur_year) {$cur_year=date('Y');}

$lust_day=date('t',mktime(0,0,0,$cur_month,1,$cur_year));

$smonth=date('U',mktime(0,0,0,$cur_month,1,$cur_year));

$emonth=date('U',mktime(23,59,59,$cur_month,$lust_day,$cur_year));

$print1=date('d-m-y',$smonth);
$print2=date('d-m-y',$emonth);
if($p_r) echo "Выручка с $print1 по $print2";
if(!$p_r) echo "Расходы с $print1 по $print2";
	
$this->sql_query="select * from fin_kat where `prihod_rashod`='$p_r' order by name asc";
$this->sql_execute();
if($this->sql_err) return(11);
$r_count=0;
while(list($id,$name,$p_rs) =mysql_fetch_row($this->sql_res))
{ 
$rarrayid[$r_count]=$id;
$rarrayname[$r_count]=$name;
$r_count++;
}
$ALL_SUMA=0;


$this->sql_query="select * from `fin_main` where `prihod_rashod`='$p_r' and date>='$smonth'
and date<='$emonth'";
$this->sql_execute();
if($this->sql_err) return(11);
while(list($id,$suma, $name, $date,$pr_ra,$kat_id,$kat_name)=mysql_fetch_row($this->sql_res))
{$ALL_SUMA=$ALL_SUMA+$suma;}

echo "<table>
<tr bgcolor=#dddddd>
<td align=center>Статья</td>
<td align=center>Сум</td>
<td align=center>в %</td>
</tr>";

for($i=0;$i<$r_count;$i++)
{
$SUMA2=0;
$this->sql_query="select * from `fin_main` where `prihod_rashod`='$p_r' and `kat_id`='$rarrayid[$i]' and date>='$smonth'
and date<='$emonth'";
$this->sql_execute();

echo "<tr bgcolor=#cccccc>
<td align=left>";
		
while(list($id,$suma, $name, $date,$pr_ra,$kat_id,$kat_name,$receipt_id)=mysql_fetch_row($this->sql_res))
{$SUMA2=$SUMA2+$suma;}
$procent=$SUMA2/$ALL_SUMA*100;
$bar_color="#cccccc";
if($procent){$bar_color="red";}
$temp=explode(".", $procent);



if(substr($temp[1],1)>=5)
{$procent=$temp[0]+1;}

if(substr($temp[1],1)<5)
{$procent=$temp[0];}


echo "$rarrayname[$i]</td>
<td align=center>$SUMA2</td>
<td align=left>
<table><tr>
<td width=15 >$procent</td>
<td width=$procent bgcolor='$bar_color'>
</td>
</tr></table>
</td>
</tr>";

}
echo "<tr><td>Всего</td> 
	<td>$ALL_SUMA</td></tr>";
echo "</table>";

		return(0);
	}
	
	
function out_balans($year)
{

$syear=date('U',mktime(0,0,0,1,1,$year));

$eyear=date('U',mktime(23,59,59,12,31,$year));

$SUMA_PRIHODA=0;
$SUMA_RASHODA=0;
$BALANS=0;		
$this->sql_query="select * from `fin_main` where `prihod_rashod`='1' and date>='$syear' and date<='$eyear' ";
		$this->sql_execute();
		while(list($id,$suma, $name, $date,$pr_ra,$kat_id,$kat_name,$receipt_id)=mysql_fetch_row($this->sql_res))
{			$SUMA_PRIHODA=$SUMA_PRIHODA+$suma;
}
$this->sql_query="select * from `fin_main` where `prihod_rashod`='0'  and date>='$syear' and date<='$eyear'";
		$this->sql_execute();
		while(list($id,$suma, $name, $date,$pr_ra,$kat_id,$kat_name,$receipt_id)=mysql_fetch_row($this->sql_res))
{			$SUMA_RASHODA=$SUMA_RASHODA+$suma;
}		$BALANS=$SUMA_PRIHODA-$SUMA_RASHODA;
echo "<table><tr><td>Сума прихода</td>
<td>$SUMA_PRIHODA</td></tr>
<tr><td>Сума расхода</td><td>$SUMA_RASHODA</td></tr><tr><td>Баланс</td><td>$BALANS</td></tr>
</table>";
		
return(0);
}

function out_balans_month($smonth,$emonth)
{

$SUMA_PRIHODA=0;
$SUMA_RASHODA=0;
$BALANS=0;		
$this->sql_query="select * from `fin_main` where `prihod_rashod`='1'
and date>='$smonth'
and date<='$emonth'";	$this->sql_execute();
while(list($id,$suma, $name, $date,$pr_ra,$kat_id,$kat_name,$receipt_id)=mysql_fetch_row($this->sql_res))
{
$SUMA_PRIHODA=$SUMA_PRIHODA+$suma;
}		
$this->sql_query="select * from `fin_main` where `prihod_rashod`='0'
and date>='$smonth'
and date<='$emonth'";

$this->sql_execute();
		while(list($id,$suma, $name, $date,$pr_ra,$kat_id,$kat_name,$receipt_id)=mysql_fetch_row($this->sql_res))
{
$SUMA_RASHODA=$SUMA_RASHODA+$suma;
}		$BALANS=$SUMA_PRIHODA-$SUMA_RASHODA;

$is_visible=$SUMA_PRIHODA+$SUMA_RASHODA;

if($is_visible)
{
echo "<table><tr><td>Сума прихода</td>
<td>$SUMA_PRIHODA</td></tr>
<tr><td>Сума расхода</td><td>$SUMA_RASHODA</td></tr><tr><td>Баланс</td><td>$BALANS</td></tr>
</table>";
}


return(0);
}


function out_all()
{
$this->sql_query="select * from `fin_kat` order by id asc";
$this->sql_execute();

while(list($id,$name,$prihod_rashod)=mysql_fetch_row($this->sql_res)) {
  $kat[$id]=$name;
}

$this->sql_query="select * from `fin_main` order by date desc limit 2300";
$this->sql_execute();
		
echo "<table border=0>
<tr bgcolor=aaaaa>
<td width='400'>назначение</td>
<td width='150'>сумма</td>
<td width='200'>кат</td>
<td width='200'>дата</td>
</tr>";

while(list($id,$suma, $name, $pr_ra,$date,$kat_id,$kat_name,$receipt_id)=mysql_fetch_row($this->sql_res))
{

$d=date("d.m.y  H:i:s U" ,$date);

if($pr_ra==0)
{$color=dadada;} 
if($pr_ra==1)
{$color=green;}

echo "<tr bgcolor=$color>
<td>$name</td>
<td>$suma</td>
<td>".$kat[$kat_id]."</td>
<td>$d</td></tr>";			
}
echo "</table>";			return(0);
}
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function out_period($date_start,$date_end)
{
echo "$date_start - $date_end";
$temp=explode(".",$date_start);
$month=$temp[1];
$day=$temp[0];
$year=$temp[2];
$date_start=date('U',mktime(0,0,0,$month,$day,$year));

$temp=explode(".",$date_end);
$month=$temp[1];
$day=$temp[0];
$year=$temp[2];
$date_end=date('U',mktime(23,59,59,$month,$day,$year));
echo "<br>$date_start - $date_end";

echo "<table border=0>
<tr bgcolor=aaaaa>
<td>назначение</td>
<td>сумма</td>
<td>кат</td>
<td>дата</td>
</tr>";

$this->sql_query="select * from `fin_main` where `date`>='$date_start' and `date`<='$date_end' order by `date`";
$this->sql_execute();
		


while(list($id,$suma, $name, $pr_ra,$date,$kat_id,$kat_name,$receipt_id)=mysql_fetch_row($this->sql_res))
{
$d=date("d.m.y  H:i:s" ,$date);

if($pr_ra==0) {$color=dadada;} 
if($pr_ra==1) {$color=green;}

echo "<tr bgcolor=$color>
<td>$name</td>
<td>$suma</td>
<td>$kat_id</td>
<td>$d</td></tr>";			
}
echo "</table>";	

return(0);
}	

}
?>
