<?
require("req/in.php");
//require("../class.phpmailer.php");
$dengi=new cls_in();
Error_Reporting(E_ALL & ~E_NOTICE);
$dengi->sql_connect();
$MONTH_RU=array("Январь","Февраль","Март","Апрель", "Май", "Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь");
$YEAR=2018;

echo "<html><head>
<meta http-equiv='Content-Type' content='text/html; charset=utf8'>
<title>Мои финансы</title></head><body>";
echo date('d-m-Y H:i:s');  echo "<br>";

if(!$_GET[act]){$action=rashod;}
if($_GET[act]){$action=$_GET[act];}

switch($action)
{	
case prihod:	
echo "<form enctype='multipart/form-data' action='$PHP_SELF?act=prihod_add' method=post>
<table><tr><td>Описание</td><td><input type=text size=50 name='name'></td></tr>
					<tr><td>Сумма</td><td><input type=text size=20 name='suma'></td></tr>
					<tr><td>Статья</td><td><SELECT  name='kat' class=button>";
$dengi->sql_query="SELECT * FROM `fin_kat` where `prihod_rashod`='1' ORDER BY `name` ASC";
$dengi->sql_execute();
while(list($kat_id, $kat_name, $prih_rash)=mysql_fetch_row($dengi->sql_res))
					{echo "<OPTION value=$kat_id>$kat_name</OPTION>";}
					echo "</SELECT></td></tr>
					<tr><td><input type=submit value='OK'></td><td></td></tr>
					</table></form>";
					break;
	case prihod_add:	$dengi->in_dengi_prihod_add($_POST[suma],$_POST[name],$_POST[kat]);	break;
	case rashod :		echo "<form enctype='multipart/form-data' action='$PHP_SELF?act=rashod_add' method=post>
						<table><tr><td>Описание</td><td><input type=text size=50 name='name'></td></tr>
						<tr><td>Сумма</td><td><input type=text size=20 name='suma'></td></tr>
						<tr><td>Статья</td><td>	<SELECT  name='kat' class=button>";
$dengi->sql_query="SELECT * FROM `fin_kat` where `prihod_rashod`='0' ORDER BY `name` ASC";
$dengi->sql_execute();
while(list($id, $name, $prih_rash)=mysql_fetch_row($dengi->sql_res))
						{echo "<OPTION value=$id>$name</OPTION>";}
						echo "</SELECT></td></tr>
						<tr><td><input type=submit value='OK'></td><td></td></tr>
						</table></form>";break;
	case rashod_add :	$dengi->in_dengi_rashod_add($_POST[suma],$_POST[name],$_POST[kat]);	break;
	
case kat_rashod :

if(!$_POST[month]){$cur_month=date("n");}
else{$cur_month=$_POST[month];}	
if(!$_POST[year]){$cur_year=date("Y");}
else{$cur_year=$_POST[year];}

echo "<form enctype='multipart/form-data' action='$PHP_SELF?act=kat_rashod' method=post>
						<table><tr><td>Месяц</td><td><SELECT  name='month' class=button>";
						for($i=1;$i<=12;$i++)
						{
						if($cur_month==$i){$selected="selected=\"selected\"";}
						else {$selected="";}
							echo "<OPTION value=".$i." $selected>".$MONTH_RU[$i-1]."</OPTION>";
						}
					echo "</SELECT><input type=text size=4 name=year></input></td><td><input type=submit value='OK'></td><td></td></tr>
					</table></form>";

$dengi->out_kat(0, $cur_month,$cur_year);
$dengi->out_kat(1,  $cur_month,$cur_year);
						echo "<br>Добавление статьи раcхода<br>
						<form enctype='multipart/form-data' action='$PHP_SELF?act=kat_rashod_add' method=post>
						<table><tr><td><input type=text size=30 name='kat_name'></td>
						</tr><tr><td valign=bottom align=center><input class=button_ type=submit value='Добавить'></td>
						</tr></table></form>";	break;

case day: 
$cur_month=date('m');
$cur_year=date('y'); 
$c_day=date('d');

echo "<table>";
echo "<tr  bgcolor=#dddddd><td>дата</td><td>приход</td><td>расход</td></tr>";
for($d=1;$d<=$c_day;$d++)
{
$dengi->out_day($d,$cur_month,$cur_year);

echo "<tr  bgcolor=#cccccc><td>$d-$cur_month-$cur_year</td><td>$dengi->day_prihod</td><td>$dengi->day_rashod</td></tr>";
}
echo "</table>";
break;

case period:
echo "<br>Просмотр операций за период<br>
<form enctype='multipart/form-data' action='$PHP_SELF?act=view_period' method=post>
<table><tr><td>с</td><td><input type=text size=20 name='date_start'></td></tr>
<tr><td>по</td><td><input type=text size=20 name='date_end'></td></tr>
<tr><td valign=bottom align=center><input class=button_ type=submit value='Показать'></td>
</tr></table></form>";	break;

case view_period: 
$dengi->out_period($_POST[date_start], $_POST[date_end]); break;
case kat_rashod_add:	$dengi->sql_query="insert into `fin_kat` (id,name,prihod_rashod) values ('','$_POST[kat_name]','0')";		$dengi->sql_execute();break;

case kat_prihod:	 if(!$_POST[month]){$cur_month=date("n");}
else{$cur_month=$_POST[month];}
if(!$_POST[year]){$cur_year=date("Y");}
else{$cur_year=$_POST[year];}
echo "<form enctype='multipart/form-data' action='$PHP_SELF?act=kat_prihod' method=post>
						<table><tr><td>Месяц</td><td>	<SELECT  name='month' class=button>";
						for($i=1;$i<=12;$i++)
						{
						if($cur_month==$i){$selected="selected=\"selected\"";}
						else {$selected="";}
							echo "<OPTION value=".$i." $selected>".$MONTH_RU[$i-1]."</OPTION>";
						}
					echo "</SELECT><input type=text size=4 name=year></input></td><td><input type=submit value='OK'></td><td></td></tr>
					</table></form>";

    $dengi->out_kat(1,$cur_month,$cur_year);
    $dengi->out_kat(0,$cur_month,$cur_year);

echo "<br>Добавление статьи прихода<br>
<form enctype='multipart/form-data' action='$PHP_SELF?act=kat_prihod_add' method=post><table><tr><td>
<input type=text size=30 name='kat_name'></td></tr>
<tr><td valign=bottom align=center><input class=button_ type=submit value='Добавить'></td></tr></table></form>";break;
	case kat_prihod_add:	$dengi->sql_query="insert into `fin_kat` (id,name,prihod_rashod) values ('','$_POST[kat_name]','1')";
$dengi->sql_execute();break;

case balans:  $dengi->out_balans();
		break;

case graf:
$dengi->sql_query="select * from fin_kat where `prihod_rashod`='0' order by `name` asc";
$dengi->sql_execute();
$cat_count=0;
while(list($id,$name, $type)=mysql_fetch_row($dengi->sql_res))
{
$cat_array_id[$cat_count]=$id;
$cat_array_name[$cat_count]=$name;
$cat_count++;
echo " <br/>$id $name";
}

for($i=0;$i<=$cat_count;$i++)
{
$id_p=$cat_array_id[$i];
$name_p=$cat_array_name[$i];
 echo "<br/>$name_p";
}
break;

case showall:  $dengi->out_all();
    break;

case import:
    include('../simplehtmldom/simple_html_dom.php');
$i=0;
$pars=file_get_html($_GET[file]);


foreach($pars->find('td') as $e)
{
//echo $e->innertext."<br>";
    $n++;
    if($n>12){$n=1;$i++;}

$vypyska[$i][$n]=$e->innertext;
   
}

$dengi->sql_query="SELECT * FROM `fin_kat` where `prihod_rashod`='1' ORDER BY `name` desc";
$dengi->sql_execute();
$prihod_list="";
while(list($kat_id, $kat_name, $prih_rash)=mysql_fetch_row($dengi->sql_res))
    {$prihod_list.="<OPTION value=$kat_id>$kat_name</OPTION>";}
$prihod_list.="</SELECT>";

$dengi->sql_query="SELECT * FROM `fin_kat` where `prihod_rashod`='0' ORDER BY `name` desc";
$dengi->sql_execute();
$rashod_list="";
while(list($kat_id, $kat_name, $prih_rash)=mysql_fetch_row($dengi->sql_res))
    {$rashod_list.="<OPTION value=$kat_id>$kat_name</OPTION>";}
$rashod_list.="</SELECT>";

 
$vypyska_form="<form enctype='multipart/form-data'
    action='index.php?act=vypyska_add' method=post>
    <table>
        <tr><td width=50>Дата</td><td>сумма</td><td>назначение</td><td>едрпоу</td><td></td><td></td></tr>";

for($n=0;$n<=$i;$n++)
{
    //echo $vypyska[$n][2]." - ".$vypyska[$n][4]." - ".$vypyska[$n][7]." - ".$vypyska[$n][8]."<br>";
  if($vypyska[$n][8]!=3047313536)
  {
      $dengi->sql_query="SELECT invc_num FROM invoice_control
     WHERE invc_from='3047313536'
      AND invc_to='".$vypyska[$n][8]."'
     AND is_payd='0' ORDER BY invc_num DESC";
// $dengi->sql_execute();
$invc_list="<SELECT name=invcnum_$n><OPTION value=0>-----</OPTION>";

while(list($invc_num)=mysql_fetch_row($dengi->sql_res))
    {$invc_list.="<OPTION value=$invc_num>$invc_num</OPTION>";}

$invc_list.="</SELECT>";

if($vypyska[$n][4]) 
{
$moneyForm=$vypyska[$n][4];
}
elseif($vypyska[$n][5]) 
{
$moneyForm=$vypyska[$n][5];
}
      $vypyska_form.='<tr><td><input type=text size=7 name=date_'.$n.' value="'.$vypyska[$n][2].'"></td>
          <td><input type=text size=7 name=suma_'.$n.' value="'.$moneyForm.'"></td>
          <td><input type=text size=60 name=name_'.$n.' value="'.$vypyska[$n][7].'"></td>
         <td><input type=text size=7 name=edrpou_'.$n.' value="'.$vypyska[$n][8].'"></td>';

      if($moneyForm>0)
          {
              $vypyska_form.='<td><input type=checkbox checked="checked" name=rp_'.$n.' ></td>
                  <td>'.$invc_list.'</td>
                  <td><SELECT  name=kat_'.$n.'>'.$prihod_list.'</td>';
          }
          if($moneyForm<0)
          {
              $vypyska_form.='<td><input type=checkbox name=rp_'.$n.' ></td><td></td>
                  <td><SELECT  name=kat_'.$n.'>'.$rashod_list.'</td>';
          }
          $vypyska_form.='</tr>';
  }



    

} $vypyska_form.="<tr><td><input type=submit value='Сохранить'></td><td></td><td></td><td></td><td></td></tr></table>
</form>";


break;
    case vypyska_add:
        $t=0;
      foreach ($_POST as $key=>$value)
{
    $temp=explode("_",$key);
    $v_array[$temp[0]][$temp[1]]=$value;
     
    $last=$temp[1];

}
for($i=0;$i<=$last;$i++)
{
    if(strlen($v_array[suma][$i])>0)
    {
        if($v_array[rp][$i]=="on"){$pr=1;}
        else{$pr=0;}
    $date_=explode(".",$v_array[date][$i]);
    $cur_date=date(U, mktime('17', '0', '0', $date_[1], $date_[0], $date_[2]));
    $pr_=str_replace("-", "", $v_array[suma][$i]);

     $dengi->sql_query="insert into `fin_main`(id,suma,name,prihod_rashod,date,kat_id,edrpou,receipt_num)
   values ('','".str_replace("-", "", $v_array[suma][$i])."','".$v_array[name][$i].
            "','$pr','".$cur_date."','".$v_array[kat][$i]."','".$v_array[edrpou][$i]."','".$v_array[invcnum][$i]."')";
        $dengi->sql_execute();

        $message="Швайко В. В.<br>";
        if($v_array[suma][$i]>0 && $v_array[invcnum][$i]!=0)
            {
$message.="Оплачен счет № ".$v_array[invcnum][$i]." На сумму ".$v_array[suma][$i].
            ". ЕДРПОУ ".$v_array[edrpou][$i]."<br>";
$dengi->sql_query="UPDATE invoice_control SET is_payd='1', pay_date=".$cur_date."
    WHERE  invc_from='3047313536'
    AND invc_num='".$v_array[invcnum][$i]."'";
//$dengi->sql_execute();
            }

    }
}
break;

}

echo $vypyska_form;

echo "<hr>
<table><tr><td><a href=index.php?act=showall>Показать все</a></td><td>
<a href=index.php?act=period>Показать за период</a></td></tr></table>
<hr><table><tr><td>Операции</td></tr>
<tr><td>
<a href='index.php?act=prihod'>Приход</a></td>
<td><a href='index.php?act=rashod'>Расход</a></td></tr>
<tr><td>Справочники</td></tr><tr><td><a href='index.php?act=kat_prihod'>Статьи приходов</a></td>
<td><a href='index.php?act=kat_rashod'>Статьи расходов</a></td></tr></table>";
	


/*for($i=0;$i<12;$i++)
{
$mon=$i+1;

echo "<hr>";
echo "<b>$MONTH_RU[$i]</b>";
$cur_smonth=date('U',mktime(0,0,0,$mon,1,2016));
$cur_emonth=date('U',mktime(23,59,59,$mon,31,2016));
$dengi->out_balans_month($cur_smonth,$cur_emonth);
}*/

echo "<hr>";
$cur_month=date("n")-1;

$cur_day=date('d');

echo "<b>За сегодня:</b>";

$cur_sday=date('U',mktime(0,0,0,$cur_month+1,$cur_day,$YEAR));
$cur_eday=date('U',mktime(23,59,59,$cur_month+1,$cur_day,$YEAR));
$dengi->out_balans_month($cur_sday,$cur_eday);

echo "<hr>";
$prev_month=$cur_month-1;
echo "Прошлый месяц:<b> $MONTH_RU[$prev_month]</b> "; 

$lust_day=date('t',mktime(0,0,0,$cur_month,1,$YEAR));

$cur_smonth=date('U',mktime(0,0,0,$cur_month,1,$YEAR));
$cur_emonth=date('U',mktime(23,59,59,$cur_month,$lust_day,$YEAR));

$dengi->out_balans_month($cur_smonth,$cur_emonth);

echo "<hr>";

echo "Текущий месяц:<b> $MONTH_RU[$cur_month]</b> "; 

echo "<a href='index.php?act=day'>по дням</a>";
$lust_day=date('t');
$cur_smonth=date('U',mktime(0,0,0,$cur_month+1,1,$YEAR));
$cur_emonth=date('U',mktime(23,59,59,$cur_month+1,$lust_day,$YEAR));

$dengi->out_balans_month($cur_smonth,$cur_emonth);

echo "<hr><b>Всего</b>";
$dengi->out_balans($YEAR);

//echo "<hr><b>Всего 2011</b>";

//$dengi->out_balans(2011);


$dengi->sql_close();
echo "</body></html>";
?>
