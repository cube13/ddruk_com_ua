<?php
require 'req/mysql.php';
include('header.php');

$cartridge->sql_query="SELECT value FROM settings WHERE id=1";
$cartridge->sql_execute();
if(!$cartridge->sql_err)
{
list($kurs_usd_nal)=mysql_fetch_row($cartridge->sql_res);
}


if($_GET[cart_name])
{
$cart_name=$_GET[cart_name];
//echo $cart_name;
}
//query with available material
$cartridge->sql_query="SELECT
cartridge.id,
cartridge.name,
cartridge.cena_zapravki,
cartridge.cena_vostanovlenia,
cartridge.cena_novogo,
cartridge.cena_pokupki_bu,
cartridge.color,
cartridge.type,
cartridge.is_chip,
cartridge.resurs,
cartridge.html_title,
cartridge.page_title,
cartridge.page_content,
cartridge.m_kwords,
cartridge.m_desc,
cartridge.picture,
brands.name,
cart_rashodka.rashodnik_id,
store_it.available,
cart_rashodka.stage_code
FROM cartridge
join brands on cartridge.brand=brands.id
join cart_rashodka on cartridge.id=cart_rashodka.id_cart
join store_it on store_it.id=cart_rashodka.rashodnik_id
WHERE
cartridge.name='$cart_name'
AND
cartridge.publish='1'
AND
cartridge.html_title!='';";

//query without material
/*$cartridge->sql_query="SELECT
cartridge.id,
cartridge.name,
cartridge.cena_zapravki,
cartridge.cena_vostanovlenia,
cartridge.cena_novogo,
cartridge.cena_pokupki_bu,
cartridge.color,
cartridge.type,
cartridge.is_chip,
cartridge.resurs,
cartridge.html_title,
cartridge.page_title,
cartridge.page_content,
cartridge.m_kwords,
cartridge.m_desc,
cartridge.picture,
brands.name
FROM cartridge
JOIN brands ON cartridge.brand=brands.id
WHERE
cartridge.name='$cart_name'
AND
cartridge.publish='1'
AND
cartridge.html_title!='';";*/

$cartridge->sql_execute();

if(!$cartridge->sql_err)
{

list($cart_id,$cartr_name,$cena_z,$cena_v,$cena_n,$cena_obmen,$color,$type,$is_chip,$resurs,$html_title,$page_title,$content,$keywords,$desc,$picture,$brand,$rashodnik_id,$avail,$stage_code)=mysql_fetch_row($cartridge->sql_res);

$CART_NAME=$cartr_name;

$HTML_TITLE=$html_title;
$PAGE_TITLE=$page_title;
$CONTENT=$content;
$KEYWORDS=$keywords;
$DESCRIBE=$desc;

$CENA_Z=$cena_z;
$CENA_V=$cena_v;
$CENA_N=$cena_n*$kurs_usd_nal;
$CENA_O=$cena_obmen;
$COLOR=$color;
$TYPE=$type;
$IS_CHIP=$is_chip;
$RESURS=$resurs;
$BRAND=$brand;
//$PICTURE=str_replace("/ddruk.local", $base_url, $picture);
$PICTURE="http://ddruk.center".$picture;

$cena_n=$cena_n*$kurs_usd_nal;
}

$table_uslugi="<table border=0 cellspacing=0 style='margin-left:30px;margin-top:15px;'><tr>
    <th class='uslugi_header' width='250px'>Тип услуги</th>
    <th class='uslugi_header' width='100px'>Стоимость</th></tr>
    <tr><td class='uslugi'>Чистка</td><td class='uslugi'>бесплатно</td></tr>";

if($CENA_Z) $table_uslugi.="<tr><td class='uslugi'><a href='http://ddruk.com.ua/publication/66-o-zapravke-kartridgey.html'>Заправка</a></td><td class='uslugi'>$CENA_Z</td></tr>";
if($CENA_V) $table_uslugi.="<tr><td class='uslugi'><a href='http://ddruk.com.ua/news/64-O-vosstanovlenii-kartridzhey.html'>Восcтановление<a></td><td class='uslugi'>$CENA_V</td></tr>";
if($CENA_O) $table_uslugi.="<tr><td class='uslugi'>Обмен (\"вечный картридж\")</td><td class='uslugi'>$CENA_O</td></tr>";
if($CENA_N) $table_uslugi.="<tr><td class='uslugi'>Купить новый</td><td class='uslugi'>$CENA_N</td></tr>";
if($IS_CHIP==0) $table_uslugi.="<tr><td class='uslugi'>Замена или обнуление чипа</td><td class='uslugi'>не требуется</td></tr>";
if($IS_CHIP==1) $table_uslugi.="<tr><td class='uslugi'>Замена или обнуление чипа</td><td class='uslugi'>требуется</td></tr>";
if($IS_CHIP==2) $table_uslugi.="<tr><td class='uslugi'>Замена или обнуление чипа</td><td class='uslugi'>требуется</td></tr>";
if($IS_CHIP==3) $table_uslugi.="<tr><td class='uslugi'>Замена или обнуление чипа</td><td class='uslugi'>прошивка принтера</td></tr>";

$table_uslugi.="</table>";

$cartridge->sql_query="SELECT printer_id, cartridge_id, printers.name, printers.id
FROM  `print_join_cart` 
JOIN printers ON printers.id = printer_id
WHERE cartridge_id = $cart_id";
$cartridge->sql_execute();
if(!$cartridge->sql_err)
{
    $koma="";
    $koma_flag=0;
    while(list($not_use,$not_use2,$printer_name,$printer_id)= mysql_fetch_row($cartridge->sql_res))
    {
       $whith_print.="$koma <a href=\"$BRAND-".str_replace(' ', '-', $printer_name)."/printer-$printer_id.html\">$BRAND $printer_name</a>";
        if(!$koma_flag) $koma_flag=1;
        if($koma_flag) $koma=",";
    }
}

if(!$TYPE)
{
    $color_text="монохромных";
    $kharakteristiki="Черный (black) картридж $BRAND $CART_NAME совместим с принтерами $whith_print.<br><br>";
}
if($TYPE)
{
    $color_type[K]="Черный (black)";
    $color_type[Y]="Желтый (yellow)";
    $color_type[C]="Синий (cyan)";
    $color_type[M]="Красный (magenta)";
    $color_text="цветных";
    
    $kharakteristiki="<b>".$color_type[$COLOR]."</b> картридж $BRAND $CART_NAME совместим с принтерами $whith_print.<br><br>";
} 

$kharakteristiki.="<b>Ресурс картриджа</b> (как нового оригинального, так и после 
<a href='http://ddruk.com.ua/publication/66-o-zapravke-kartridgey.html'>заправки картриджа</a>): <b>$RESURS страниц</b>    формата А4 при 5% заполнении страницы";


$opisanie="<p>Картридж $BRAND $CART_NAME используется в $color_text лазерных печатающих
устройствах производства компании $BRAND. Заправка картриджа $BRAND $CART_NAME выполняется
высококачественным сертифицированным тонером на современном оборудовании
квалифицированными специалистами.

<p><strong><a href='http://ddruk.com.ua/publication/66-o-zapravke-kartridgey.html'>Заправка картриджа</a></strong> $BRAND $CART_NAME включает в себя полную разборку картриджа,
диагностику деталей заправляемого картриджа на изношенность, качественную очистку
валов и лезвий картриджа, смазку шестеренок картриджа, заправку картриджа тонером,
сборку картриджа после заправки.

<p><strong><a href='http://ddruk.com.ua/news/64-O-vosstanovlenii-kartridzhey.html'>Восстановление картриджа</a></strong> $BRAND $CART_NAME включает в себя полную разборку картриджа, 
диагностику деталей заправляемого картриджа на изношенность, <b>замену всех изношенных деталей</b>, 
качественную очистку картриджа, смазку шестеренок картриджа, <strong>заправку картриджа тонером</strong>, 
сборку картриджа после заправки.

<p>На заправляемый или восстанавливаемый картридж $BRAND $CART_NAME при приеме будет
установлен <b>уникальный индивидуальный код</b>, данные с которого переносятся в систему учета,
обеспечивающую контроль всех работ по заправке картриджей. Вы всегда будете знать,
когда и какие работы производились с Вашими картриджами.

<p>Сданный на заправку картридж $BRAND $CART_NAME пройдет предварительную <b>бесплатную
диагностику</b> (а для распространенных картриджей – тест печати до и после заправки
картриджа) для определения узлов и деталей, требующих замены в связи с их
критическим износом.

<p>Все работы по заправке картриджа $BRAND $CART_NAME будут произведены <b>только в
производственных условиях сервисного центра</b>. Никто не рассыплет тонер в Вашем
помещении, не возникнут непредвиденные проблемы с отсутствием нужного чипа или
других деталей картриджа.

<p>После заправки картридж $BRAND $CART_NAME упаковывается в защитную упаковку. На
заправленный картридж $BRAND $CART_NAME, а также на восстановленный картридж $BRAND $CART_NAME
дается гарантия работоспособности.

<p>Заправка картриджа в нашем <a href='/nashi-koordinaty.html'>сервис-центре в Киеве</a> занимает, как правило, 20-30
минут. При необходимости мы организовываем доставку картриджей для заправки или
восстановления в сервисный центр и обратно силами своей курьерской службы (по Киеву).

<p>Стоимость <a href='http://ddruk.com.ua/news/64-O-vosstanovlenii-kartridzhey.html'>восстановления</a> 
картриджа $BRAND $CART_NAME включает в себя стоимость заправки
картриджа $BRAND $CART_NAME, а также стоимость замены всех изношенных деталей картриджа.

<p>Заправить картридж $BRAND $CART_NAME вы можете в нашем <a href='/nashi-koordinaty.html'>сервис-центре в Киеве</a>.
При необходимости вы можете купить картридж $BRAND $CART_NAME у нас с доставкой по Киеву.";

/*$opisanie="Картридж $BRAND $CART_NAME используется в $color_text лазерных принтерах $BRAND.
Заправка картриджа $BRAND $CART_NAME выполняется высококачественным тонером на современном оборудовании опытными мастрами.<br>
Заправка картриджа $BRAND $CART_NAME включает в себя полную разборку картриджа, дианностику деталей картриджа на изношенность,
качественную очитску валов и лезвий картриджа, смазку шестренок картриджа, заправку картриджа тонером, сборку картриджа.
После заправки картридж $BRAND $CART_NAME тестируется на соответсвующем ему принтере и упаковывется в защитную упаковку.
На заправленный картридж $BRAND $CART_NAME, а также на восстановленный картридж $BRAND $CART_NAME дается гарантия работоспособности.
Стоимость восстановления картриджа $BRAND $CART_NAME включает в себя стоимость заправки картриджа $BRAND $CART_NAME, а также стоимость
замены всех изношеных деталей картриджа.<br>
Заправить картридж $BRAND $CART_NAME в Киеве вы можете в нашем сервес-центре в Киеве.<br>
При необходимости вы можете купить картридж $BRAND $CART_NAME у нас с доставкой по Киеву.";*/


$set_title="Заправка картриджа $BRAND $CART_NAME";
$set_keywords="Заправка картриджа $BRAND $CART_NAME";
$set_description="Заправка картриджа $BRAND $CART_NAME по приемлемой цене и с гарантией качества.";

include 'header_html.php';
echo $top;
echo $top_menu2;

?>
<div style="float:center;min-width: 1001px; width:100%;" >
    <div style="position: relative;float:left;width:175px;text-align: reight;margin-top: 2px;'">
        <?php include('left_menu.php');?></div>
    <div id="content_cp" >
    <div class="page_title" align="center">
         
    <table border="0" width="90%" style="margin-top: 25px;">
        <tr><td width="40%" class="oglav_cart_print">
            <?php echo $BRAND." ".$CART_NAME;?>
            </td>
        <td rowspan="2" width="60%" align="left" valign="top"><?php echo $table_uslugi;?></td></tr>
        <tr><td align="center" valign="top"><?php echo "<br><img width=\"300\" src=\"$PICTURE\" alt=\"Заправка картриджа $BRAND $CART_NAME\" title=\"Заправка картриджа $BRAND $CART_NAME\">";?></td></tr>        
    </table>
    </div>  
        <div style="width:90%;text-align: center;font-size: 16px;font-weight: bold;"><br>Характеристики</div>
        <div class="page_text"><p><?php echo $kharakteristiki;?></div>
        
        <div style="width:90%;text-align: center;font-size: 16px;font-weight: bold;"><br>Описание</div>
        <div class="page_text"><?php echo $opisanie;?></div><br>
    </div>

    
 <div class="right_block"><?php include('right_block.php');?></div>  
   
</div>


<?php include ('bottom.php');?>

</body>
</html>
