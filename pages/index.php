<?php 
include 'conf.php';

$cartridge->sql_connect();


// Формирование вывода списка разделов для меню
$cartridge->sql_query="SELECT id, name FROM page_cat ORDER BY name ASC";
$cartridge->sql_execute();
$brands="";
while (list($id,$name)=mysql_fetch_row($cartridge->sql_res))
{
	$brands.="<a href=\"index.php?cat=$id\">$name</a> | ";
}
$brands.="<a href=\"index.php?cat=0\">Все</a>";

switch($_GET[act])
{


//---- Создание новой страницы
case creat_new_page:
//    $page_cat ? $page_cat=$_GET[page_cat]: $page_cat=999;
$now_date=date('U');
echo $cartridge->sql_query="insert into pages(id,page_title,page_cat,date)
        values('','Новая', $_GET[page_cat],'$now_date');";
$cartridge->sql_execute();
$last_id=mysql_insert_id();
echo "<script>top.location.href = 'index.php?act=view_page&page_id=$last_id'</script>";
break;

//---- Редактирование страницы
case view_page:
	
	$cartridge->sql_query="SELECT * FROM `pages` WHERE `id`='$_GET[page_id]'";
	$cartridge->sql_execute();
	if(mysql_num_rows($cartridge->sql_res)!=0)
	{
	list($id_page,$page_cat,$html_title,$m_kwords,$m_desc,$picture,$page_title,$page_cont,$publish,$date,$top_text,$is_top)=mysql_fetch_row($cartridge->sql_res);
	}

	$cartridge->sql_query="SELECT id,page_title FROM `pages` WHERE `page_cat`='$page_cat'";
	$cartridge->sql_execute();
	$quick_menu="";
	if(mysql_num_rows($cartridge->sql_res)!=0)
	{
		while(list($id,$name)=mysql_fetch_row($cartridge->sql_res))
		{
		if($id==$id_page){$quick_menu.="<b><a href=\"index.php?act=view_page&page_id=$id\">$name</a></b><hr>";}
		else{$quick_menu.="<a href=\"index.php?act=view_page&page_id=$id\">$name</a><hr>";}
		}
        }

 $cartridge->sql_query="SELECT id, name FROM page_cat ORDER BY name ASC";
$cartridge->sql_execute();
$page_cat_list="<SELECT name='page_cat'>";
while (list($id_cat,$name_cat)=mysql_fetch_row($cartridge->sql_res))
{
    $selected="";
if($id_cat==$page_cat){$selected="selected=selected";}
    $page_cat_list.="<OPTION $selected value=$id_cat>$name_cat</OPTION>";
}
$page_cat_list.="</SELECT>";

$date=date('d.m.Y',$date);
if($is_top){$checked="checked='checked'";}
if(!$is_top){$checked="";}
$text_form="
	<form enctype='multipart/form-data' action='index.php?act=save_page&id_page=$id_page' method=post>
<table width=\"100%\">
<tr>
<td><img src=\"$picture\" width=\"200\" title=\"картинка\"><br><br>

<input id=\"PicPath\" name=\"picture\" type=\"text\" value=\"$picture\" size=\"27\"/>
<input type=\"button\" value=\"Найти картинку\" onclick=\"BrowseServer();\"</td>
<td valign=\"top\">
<table width=\"100%\">
<tr><td>Раздел</td><td>$page_cat_list</td></tr>
<tr><td>Загл.</td><td><input type=\"text\" name=\"page_title\" value='$page_title' size=\"100\"></td></tr>
<tr><td>тэг title</td><td><input type=\"text\" name=\"html_title\" value='$html_title' size=\"100\"></td></tr>
<tr><td>Ключ слова</td><td><input type=\"text\" name=\"m_kwords\" value='$m_kwords' size=\"100\"></td></tr>
<tr><td>тэг описания</td><td><input type=\"text\" name=\"m_desc\" value='$m_desc' size=\"100\"></td></tr>
<tr><td>Топ фраза<input type=\"checkbox\" name=\"is_top\" $checked></td><td><input type=\"text\" name=\"top_text\" value=\"$top_text\" size=\"100\"></td></tr>

<tr><td>Дата</td><td><input type=text size=10 name='date' value=\"$date\" id=\"cal1\"></td></tr>
</table>
</td>
</tr>

<tr>
<td colspan=\"2\" align=\"left\"><br>
<textarea id=\"ckeditor\" name=\"content\" rows=\"20\" cols=\"100\">$page_cont</textarea></td>
 <script type=\"text/javascript\">
    var editor=CKEDITOR.replace( 'ckeditor' );
    CKFinder.setupCKEditor( editor,'../ckfinder/') ;
</script>

</tr>
<tr><td><input type=submit value='Сохранить'></td></tr>
</table>
</form>
";


$to_screen="

<table width=\"100%\" border=\"0\">
<tr><td width=\"150\" align=\"left\" valign=\"top\">$quick_menu</td>

<td width=\"*\" align=\"center\" valign=\"top\"> 

<table width=\"100%\" border=\"0\" bgcolor=grey>
<tr><td colspan=\"3\" bgcolor=white>$text_form</td></tr>
</table>
</td>
</tr></table>";	

break;

case save_page:

    $date_t=explode(".", $_POST[date]);
    $date=mktime(0, 0, 0, $date_t[1], $date_t[0], $date_t[2]);
    $top=0;
    if($_POST[is_top]=='on'){$top=1;}

   echo $cartridge->sql_query="UPDATE pages SET
            page_cat='$_POST[page_cat]',
            html_title='$_POST[html_title]',
            m_kwords='$_POST[m_kwords]',
            m_desc='$_POST[m_desc]',
            picture='$_POST[picture]',
            page_title='$_POST[page_title]',
            page_content='$_POST[content]',
            date='$date',
           top_text='$_POST[top_text]',
           is_top='$top'
        WHERE id=$_GET[id_page]";
    $cartridge->sql_execute();
    echo "<script>top.location.href = 'index.php?act=view_page&page_id=$_GET[id_page]'</script>";

    break;
case publish:
     $cartridge->sql_query="UPDATE pages SET
            publish='$_GET[publish]'
        WHERE id=$_GET[page_id]";
    $cartridge->sql_execute();
     echo "<script>top.location.href = 'index.php?cat=$_GET[cat]'</script>";
   
default:
// Формирование вывода перечня страниц
$order="date";
$page_filter=">0";
if($_GET[cat]) {$page_filter="=".$_GET[cat];}
if($_GET[order]) {$order=$_GET[order];}


$cartridge->sql_query="SELECT * FROM pages
WHERE page_cat$page_filter ORDER BY $order DESC";

$cartridge->sql_execute();

$page_table="<table width=\"98%\" border=1>
  <tr bgcolor=\"#dddddd\">
  <td width='5%'><b>id</b></td>
  <td width='5%'><b>дата</b></td>
  <td width='30*'><b>Название</b></td>
  <td width='50%'><b>Превью</b></td>
  <td width='5%'><b>публикация</b></td>
  <td width='2%'></td>
  </tr>";
while(list($id,$page_cat,$html_title,$m_kwords, $m_desc, $picture, $page_title, $page_content,$publish,$date,$top_text,$is_top)=mysql_fetch_row($cartridge->sql_res))
{
$date=date("d.m.Y",$date);
if($publish)
{
    $publish_href="<a href='index.php?act=publish&publish=0&page_id=$id&cat=$page_cat'>выкл.</a>";
    $unpublish_tr=" bgcolor='#ffffff'";
}
else
{
    $publish_href="<a href='index.php?act=publish&publish=1&page_id=$id&cat=$page_cat'>вкл.</a>";
    $unpublish_tr=" bgcolor='#cccccc'";
}

$is_top ? $top="<b>top</b>" : $top="";

$page_table.="<tr $unpublish_tr>
<td>$id</td>
<td>$date</td>
<td><a href=\"index.php?act=view_page&page_id=$id\">$page_title</a></td>
<td>$m_desc</td>
<td>$publish_href $top</td>
<td></td></tr>";
}

$page_table.="<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
$page_table.="</table>";
if($_GET[cat]!=0){
    $page_table.="<a href=\"index.php?act=creat_new_page&page_cat=$_GET[cat]\">Создать новую</a>";
}

$to_screen=$page_table;
}
?>
<html>
<head><title>Редактор страниц</title>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../ckfinder/ckfinder.js"></script>

<script type="text/javascript">
function BrowseServer()
{
	// You can use the "CKFinder" class to render CKFinder in a page:
	var finder = new CKFinder();
	finder.basePath = '../../';	// The path for the installation of CKFinder (default = "/ckfinder/").
	finder.selectActionFunction = SetFileField;
	finder.popup();

	// It can also be done in a single line, calling the "static"
	// Popup( basePath, width, height, selectFunction ) function:
	// CKFinder.Popup( '../../', null, null, SetFileField ) ;
	//
	// The "Popup" function can also accept an object as the only argument.
	// CKFinder.Popup( { BasePath : '../../', selectActionFunction : SetFileField } ) ;
}
// This is a sample function which is called when a file is selected in CKFinder.
function SetFileField( fileUrl )
{
	document.getElementById( 'PicPath' ).value = fileUrl;
}
</script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

  <script>
      jQuery(function($){
	$.datepicker.regional['ru'] = {
		closeText: 'Закрыть',
		prevText: '&#x3c;Пред',
		nextText: 'След&#x3e;',
		currentText: 'Сегодня',
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
		'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
		'Июл','Авг','Сен','Окт','Ноя','Дек'],
		dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
		dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
		dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
		weekHeader: 'Не',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
                showButtonPanel: true,
                gotoCurrent: true,
		yearSuffix: ''};

	$.datepicker.setDefaults($.datepicker.regional['ru']);
});
  $(document).ready(function() {
    $("#cal1").datepicker();

});

  </script>




<style>
    *
    {
/*        background-color: white;*/
        font-size: 14px;
        font-family: arial,tahoma;
    }
</style>

</head>
<body>
<table width="100%" border="0" >

<tr height="10">
<td><?php echo $brands;?></td>
</tr>
<tr>
<td><?php echo $to_screen; ?></td>
</tr>

</table>
</body>
</html>
