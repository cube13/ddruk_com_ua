<?php
require 'req/mysql.php';
include('header.php');

$cartridge->sql_query="SELECT value FROM settings WHERE id=1";
$cartridge->sql_execute();
if(!$cartridge->sql_err)
{
list($kurs_usd_nal)=mysql_fetch_row($cartridge->sql_res);
}


if($_GET[printer_id])
{
$printer_id=$_GET[printer_id];
}


$cartridge->sql_query="SELECT printers.name,brands.name,printers.alter_name,printers.html_title,printers.m_kwords,
         printers.m_desc,printers.picture,printers.page_content,printers.cena_novogo,printers.user_manual, printers.type
    FROM printers
    JOIN brands ON printers.brand=brands.id
    WHERE printers.id=$printer_id";
$cartridge->sql_execute();

if(!$cartridge->sql_err)
{
list($PRINTER_NAME,$BRAND,$alter_name,$HTML_TITLE,$KEYWORDS,$DESCRIBE,$picture,$content,$cena,$user_manual,$TYPE)=mysql_fetch_row($cartridge->sql_res);

$alter_name ? $page_t=$alter_name : $page_t="Принтер $BRAND $PRINTER_NAME";
$alter_name ? $PRINTER_NAME=$alter_name : $PRINTER_NAME=$PRINTER_NAME;
//$PICTURE=str_replace("/ddruk.local", "http://ddruk.com.ua", $picture);
$PICTURE="http://ddruk.center".$picture;
$ALTER_NAME=$alter_name;

}


// Выбор характеристик принтера
 $cartridge->sql_query="SELECT params_print.name, print_join_param_txt.znach FROM params_print
    JOIN print_join_param_txt ON params_print.id=print_join_param_txt.id
    WHERE print_join_param_txt.printer_id=$printer_id AND print_join_param_txt.znach!=''
         ORDER by params_print.sort ASC";
$cartridge->sql_execute();
if(!$cartridge->sql_err)
{
    $flag_style=1;
    $printer_value_table="<table border=0 width=\"85%\" cellspacing=0 style='background-color: white; margin-left:35px;margin-top:10px'>";
    while(list($param,$value)=mysql_fetch_row($cartridge->sql_res))
    {
        $flag_style ? $style="value_bg_white" : $style="value_bg_grey";
        $flag_style ? $flag_style-- : $flag_style++;

        $printer_value_table.="<tr><td class='$style' width=\"350\">$param</td>
        <td class='$style' width=\"*\">$value</td></tr>";
    }
    $printer_value_table.="</table>";
}

// Выбор цен принтера
/* $cartridge->sql_query="SELECT vid_rabot.name, print_join_cena_rabot.cena FROM vid_rabot
    JOIN print_join_cena_rabot ON vid_rabot.id=print_join_cena_rabot.rabota_id
    WHERE print_join_cena_rabot.printer_id=$printer_id AND public='1'
         ORDER by vid_rabot.name ASC";
$cartridge->sql_execute();
if(!$cartridge->sql_err)
{
    $printer_price_table="<table border=0 cellspacing=0 style='margin-left:30px;margin-top:15px;'><tr>
    <th class='uslugi_header' width='250px'>Тип услуги</th>
    <th class='uslugi_header' width='100px'>Стоимость</th></tr>";
    
    while(list($param,$value)=mysql_fetch_row($cartridge->sql_res))
    {
        $printer_price_table.="<tr><td class='uslugi'>$param</td>
       <td class='uslugi'>$value грн</td></tr>";
    }
    $printer_price_table.="</table>";
}
 * 
 */

//Выбор картриджей принтера

$cartridge->sql_query="SELECT
    cartridge.id,
    cartridge.name,
    cartridge.cena_zapravki,
    cartridge.cena_vostanovlenia,
    cartridge.is_chip,
    cartridge.html_title, 
    cartridge.cena_novogo,
    cartridge.cena_novogo_bn
FROM print_join_cart
JOIN cartridge ON print_join_cart.cartridge_id=cartridge.id
JOIN printers ON print_join_cart.printer_id=printers.id
WHERE cartridge.brand$brand_filter and cartridge.type='$TYPE'
AND printers.publish='1' AND cartridge.publish='1' AND print_join_cart.enable='1'
        AND print_join_cart.printer_id=$printer_id
ORDER by printers.name, cartridge.name ASC";

$cartridge->sql_execute();

$price_table_cartridge="<table border=0 width=\"85%\" cellspacing=0 style='background-color: white; margin-left:35px;margin-top:10px'>
<tbody>
<tr>
<td class=\"border_bottom\" width=\"*\"><strong><span style=\"font-size: small;\">Наименование</span></strong></td>
<td class=\"border_bottom\" width=\"*\"><strong><span style=\"font-size: small;\">Заправка</span></strong></td>
<td class=\"border_bottom\" width=\"*\"><strong><span style=\"font-size: small;\">Восстановление</span></strong></td>
<td class=\"border_bottom\" width=\"*\"><strong><span style=\"font-size: small;\">Новый</span></strong></td>
</tr>";

$flag_chip=0;
while(list($cart_id,$cart_name,$zapravka,$vostanov,$chip,$cart_title,$noviy,$noviy_bn)=mysql_fetch_row($cartridge->sql_res))
{
$link_to_chip="";
if($chip=="1") {$link_to_chip=" <a href=\"#about_chip\"> * </a>";$flag_chip=1;}
$m_cart_name=$cart_name;
if($bgcolor=="white")
{$bgcolor="#e0e0e0";}else{$bgcolor="white";}
$price_table_cartridge.="
<tr bgcolor=\"$bgcolor\" >";
if($cart_title=="")
{$price_table_cartridge.="<td >$cart_name</td>";}
else
{$price_table_cartridge.="<td ><a href=\"/zapravka-i-vosstanovlenie-$cart_name.html\">$cart_name</a></td>";}

if(!$vostanov){$vostanov="-";}
if(!$zapravka){$zapravka="-";}
if(!$noviy){$noviy="-";}else{$noviy=$noviy*$kurs_usd_nal;}
if(!$noviy_bn){$noviy_bn="-";}else{$noviy_bn=$noviy_bn*$kurs_usd_nal;}
$price_table_cartridge.="
<td >$zapravka $link_to_chip</td>
<td >$vostanov</td>
<td >$noviy</td>
</tr>
";

}
$price_table_cartridge.="</tbody></table>";
$SET_TITLE="Принтер $BRAND $PRINTER_NAME. Ремонт, обслуживание. Купить  $BRAND $PRINTER_NAME. Добрый Друк 392-86-87. Киев, Лукьяновка, центр.";
$SET_DESCRIPTION=$SET_TITLE;
include 'header_html.php';
echo $top;
echo $top_menu3;
?>
<div style="float:center;min-width: 1001px; width:100%;" >
    <div style="position: relative;float:left;width:175px;text-align: reight;margin-top: 2px;'">
        <?php 
        //include('left_menu_printers.php');
        include('left_menu.php');?></div>
    <div id="content_cp" >
    <div class="page_title" align="center">
         
    <table border="0" width="90%" style="margin-top: 25px;">
        <tr><td width="40%" class="oglav_cart_print">
            <?php echo $BRAND." ".$PRINTER_NAME;?>
            </td>
        <td rowspan="2" width="60%" align="left" valign="top"><?php echo $printer_price_table;?></td></tr>
        <tr><td align="center" valign="top"><?php echo "<br><img width=\"300\" src=\"$PICTURE\">";?></td></tr>        
    </table>
    </div>  
        <div style="width:90%;text-align: center;font-size: 16px;font-weight: bold;"><br>Картриджи</div>
        <div class="page_text"><?php echo $price_table_cartridge;?></div>
        
        <div style="width:90%;text-align: center;font-size: 16px;font-weight: bold;"><br>Технические характеристики</div>
        <div class="page_text"><?php echo $printer_value_table;?></div>
        
        <div style="width:90%;text-align: center;font-size: 16px;font-weight: bold;"><br>Описание</div>
        <div class="page_text"><?php echo $content;?></div>
    </div>

    
         <div class="right_block"><?php include('right_block.php');?></div>      
   
</div>
<?php include ('bottom.php');?>
    </body>
</html>