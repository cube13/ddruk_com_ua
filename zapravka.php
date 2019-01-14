<?php
require 'req/mysql.php';
include('header.php');


if($_GET[page_id])
{
    $cartridge->sql_query="select pages.id, page_title,html_title, m_desc, 
            page_content, url_name,m_kwords FROM pages
        JOIN page_cat ON page_cat=page_cat.id
         where publish='1' and pages.id=$_GET[page_id]";
$cartridge->sql_execute();

list($page_id, $page_title,$title,$description,$content, $cat_url_name,$keywords)=mysql_fetch_array($cartridge->sql_res);

    $set_keywords=$keywords;
    $set_description=$description;
    $set_title=$title;
        
    $page_content="<table width=95% style='margin:15px;'>
    <!--<tr><td class=\"page_title\">$page_title</td></tr>-->
    <tr><td class=\"page_content\">".str_replace('/ddruk.local', 'http://ddruk.com.ua', $content)."</td></tr>
    </table>";
   
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

    $cartridge->sql_query="SELECT
    printers.id,
    printers.name,
    cartridge.id,
    cartridge.name,
    cartridge.cena_zapravki,
    cartridge.cena_vostanovlenia,
    cartridge.is_chip,
    cartridge.html_title, 
    printers.html_title,
    cartridge.cena_novogo,
    cartridge.cena_novogo_bn
FROM print_join_cart
JOIN cartridge ON print_join_cart.cartridge_id=cartridge.id
JOIN printers ON print_join_cart.printer_id=printers.id
WHERE cartridge.brand$brand_filter and cartridge.type='$TYPE'
AND printers.publish='1' AND cartridge.publish='1' AND print_join_cart.enable='1'
ORDER by printers.name, cartridge.name ASC";

$cartridge->sql_execute();

$price_table="<table width=\"95%\" border=\"0\">
<tbody>
<tr>
<td class=\"border_bottom\" width=\"200\"><strong><span style=\"font-size: small;\">Аппарат<br><br></span></strong></td>
<td class=\"border_bottom\" width=\"*\"><strong><span style=\"font-size: small;\">Картридж<br><br></span></strong></td>
<td class=\"border_bottom\" width=\"*\"><strong><span style=\"font-size: small;\"><a href='http://ddruk.com.ua/publication/66-o-zapravke-kartridgey.html'>Заправка<br>картриджа</a>, грн.</span></strong></td>
<td class=\"border_bottom\" width=\"*\"><strong><span style=\"font-size: small;\">
<a href='http://ddruk.com.ua/news/64-O-vosstanovlenii-kartridzhey.html'>Восстановление<br>картриджа</a>, грн.</span></strong></td>
<td class=\"border_bottom\" width=\"*\"><strong><span style=\"font-size: small;\">Стоимость<br>нового, грн.</span></strong></td></tr>";
$for_new_site='';
$flag_chip=0;
while(list($printer_id,$printer_name,$cart_id,$cart_name,$zapravka,$vostanov,$chip,$cart_title,$print_title,$noviy,$noviy_bn)=mysql_fetch_row($cartridge->sql_res))
{
/*$for_new_site.='<li class="span2 print" data-id="id-0" data-type="hp">
        <h3 class="title"><a href="portfolio-project.htm">'.$cart_name.'</a><small class="pull-right">'.$BRAND.'</small></h3>
        <p class="muted">
             Заправка: '.$zapravka.'<br/>
             Восстановление: '.$vostanov.'<br/>
             Вечный: 109
             
        </p>
      </li> ';*/
    
$link_to_chip="";
if($chip=="1") {$link_to_chip=" <a href=\"#about_chip\"> * </a>";$flag_chip=1;}
//if($cart_name!=$m_cart_name) {$KEYWORDS_1.="$cart_name, ";}
//$m_cart_name=$cart_name;
//$KEYWORDS.="$BRAND $printer_name,";
if($bgcolor=="white")
{$bgcolor="#e0e0e0";}else{$bgcolor="white";}

$price_table.="
<tr bgcolor=\"$bgcolor\" >";

if($print_title=="")
{$price_table.="<td >$BRAND $printer_name</td>";}
else
{$price_table.="<td ><a href=\"$BRAND-".str_replace(' ', '-', $printer_name)."/printer-$printer_id.html\">$BRAND $printer_name</a></td>";}

if($cart_title=="")
{$price_table.="<td >$cart_name</td>";}
else
{$price_table.="<td ><a href=\"zapravka-i-vosstanovlenie-$cart_name.html\">$cart_name</a></td>";}

if(!$vostanov){$vostanov="-";}
if(!$zapravka){$zapravka="-";}
if(!$noviy){$noviy="-";}else{$noviy=round($noviy*$kurs_usd_nal*1.20);}
if(!$noviy_bn){$noviy_bn="-";}else{$noviy_bn=round($noviy_bn*$kurs_usd_nal*1.20);}
$price_table.="
<td >$zapravka $link_to_chip</td>
<td >$vostanov</td>
<td >$noviy</td>
</tr>
";

}
$price_table.="</tbody></table>";

$page_title="Заправка картриджей $_GET[brand]";
$page_content="<p>Компания «Добрый друк» предлагает <strong>заправку картриджей к принтерам $BRAND</strong>. Стоимость
заправки картриджей $BRAND составляет от 49 грн. Заправка картриджа $BRAND выполняется
высококачественным сертифицированным тонером на современном оборудовании
квалифицированными специалистами.
<p><strong>Заправка картриджей $BRAND</strong> включает в себя полную разборку картриджа, диагностику
деталей на изношенность до заправки картриджа, качественную очистку валов и лезвий
картриджа, смазку шестеренок картриджа, заправку картриджа тонером, сборку картриджа
после заправки.";

$set_title="Заправка картриджей $_GET[brand]";
$set_keywords="Заправка картриджей $_GET[brand]";
$set_description="Заправка картриджей $_GET[brand] по приемлемой цене и с гарантией качества.";

}

include 'header_html.php';
echo $top;
echo $top_menu2;
?>

<div style="float:center;min-width: 1001px; width:100%;" >
    <div style="position: relative;float:left;width:175px;text-align: reight;margin-top: 2px">
        <?php include('left_menu.php');?></div>
    <div id="content_cp" >
        <div style="margin-left: 20px;">
            
        <div class="price_zaglav"><?php echo $page_title;?></div>  
        <div style="text-align: justify;margin-left: 5px;margin-right: 35px;font-size: 12px;">
            <?php echo $page_content;?></div>
<?php echo $price_table;?>
            <?php if($flag_chip)
{
echo "<a name=\"about_chip\"><p><span style=\"font-size: small;\"> * В стоимость <a href='http://ddruk.com.ua/publication/66-o-zapravke-kartridgey.html'>заправки</a> или восстановения картриджа входит обнуление чипа</span></p>";
}
?>
        </div><br>
        
        
    </div>
  <div class="right_block"><?php include('right_block.php');?></div>  
    
                
   
</div>
<?php include ('bottom.php');?>

    </body>
</html>
