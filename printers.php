<?php
require 'req/mysql.php';
include('header.php');


if($_GET[page_id])
{
    $cartridge->sql_query="select pages.id, page_title,html_title, m_desc, page_content, url_name,m_kwords FROM pages
        JOIN page_cat ON page_cat=page_cat.id
         where publish='1' and pages.id=$_GET[page_id]";
$cartridge->sql_execute();

list($page_id, $page_title,$set_title,$set_description,$content, $cat_url_name,$set_keywords)=mysql_fetch_array($cartridge->sql_res);

    $page_content="<table width=95% style='margin:15px;'>
    <tr><td class=\"page_title\">$page_title</td></tr>
    <tr><td class=\"page_content\">".str_replace('/ddruk.local', 'http://ddruk.com.ua', $content)."</td></tr>
    </table>";

$print_table=$page_content;

}

else
{

$cartridge->sql_query="SELECT value FROM settings WHERE id=1";
$cartridge->sql_execute();
if(!$cartridge->sql_err)
{
list($kurs_usd_nal)=mysql_fetch_row($cartridge->sql_res);
}

$TYPE=0;
$order="name";
$brand_filter=">0";
if($_GET[brand]==0) {$brand_filter=">0";}
if($_GET[brand]){$brand_filter="=".$brand_arr[$_GET[brand]]; $BRAND=$_GET[brand];}
if($_GET[order]) {$order=$_GET[order];}


//Формирование заголовка таблици
    $cartridge->sql_query="SELECT name FROM params_print
WHERE  inmaintable=1
ORDER by sort ASC";
    $cartridge->sql_execute();
    $num_of_cols=0;
    $print_table="<table width=95%><tr><td class='print_table'>Модель аппарата</td>";
    while(list($col_name)=  mysql_fetch_row($cartridge->sql_res))
    {
        $print_table.="<td class='print_table'>$col_name</td>";
        $num_of_cols++;
    }
    $print_table.="</tr>";
  
// Формирование вывода таблицы принтеров с параметрами   
   $cartridge->sql_query="SELECT printers.id, printers.name, printers.picture, 
        params_print.name,print_join_param_txt.znach
            FROM printers
JOIN print_join_param_txt ON print_join_param_txt.printer_id=printers.id
JOIN params_print ON print_join_param_txt.id=params_print.id
WHERE printers.brand$brand_filter AND  printers.type='$TYPE' AND params_print.inmaintable='1'
   AND printers.picture!='default.jpg' AND printers.publish='1' 
ORDER by printers.name, params_print.sort ASC";

$cartridge->sql_execute();
while(list($print_id,$print_name,$picture,$param_name,$param_value)=  mysql_fetch_row($cartridge->sql_res))
{
    if($cols_value==0) 
    {
        $flag_style ? $style="value_bg_white" : $style="value_bg_grey";
        $flag_style ? $flag_style-- : $flag_style++;
        $picture=str_replace("/ddruk.local", "http://ddruk.com.ua", $picture);
        $print_table.="<tr><td class='$style' width=200px valign=center>
        <table 'width=100%'><tr><td width='150'>
        <a href=\"$BRAND-".str_replace(' ', '-', $print_name)."/printer-$print_id.html\">$BRAND $print_name</a></td>
        <td width=50px><img src='$picture' width='50px'></td></tr></table></td>";
        
    }
    $cols_value++;
    if($cols_value>0)$print_table.="<td class='$style' style='text-align: center;'>$param_value</td>";
    if($cols_value==$num_of_cols){ $print_table.="</tr>";$cols_value=0;}
}


$print_table.="</table>";
$page_title="Монохромные принтеры ".$_GET[brand];

$SET_TITLE="Принтеры $_GET[brand]. Ремонт, сервис, продажа. Добрый Друк 392-86-87. Киев, Лукьяновка, центр.";
$SET_DESCRIPTION=$SET_TITLE;

}
include 'header_html.php';
echo $top;
echo $top_menu3;
?>

<div style="float:center;min-width: 1101px; width:100%;" >
    <div style="position: relative;float:left;width:175px;text-align: reight;margin-top: 2px">
        <?php 
        //include('left_menu_printers.php');
        include('left_menu.php');
        ?></div>
    <div id="content_cp" >
        <div style="margin-left: 20px;">
<?php echo $print_table;?>
        </div><br>
    </div>
    <div class="right_block"><?php include('right_block.php');?></div>  
                
   
</div>tc
<?php include ('bottom.php');?>

    </body>
</html>
