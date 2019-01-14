<?php
Error_Reporting(E_ALL & ~E_NOTICE);
include('utils.php');

$base_url="http://ddruk.com.ua";

$cartridge=new cls_mysql();
$cartridge->sql_connect();

$cartridge->sql_query="SELECT id, name FROM brands ORDER BY id;";
$cartridge->sql_execute();

while(list($id,$name)=mysql_fetch_row($cartridge->sql_res))
{
$brand_arr[$name]=$id;
}

$top_menu1='
<div><div id="top_menu" width="100%">
<div id="delimeter_top_menu" style="width:10px;"></div>
<div class="lb"></div>
<div id="top_menu_back_white" ><div style="width:100%;height:12px;"></div><a href="/" class="topmenu">Главная</a></div>
<div class="rb"></div>
<div id="delimeter_top_menu"></div>
<div id="top_manu_back_grey" ><div style="width:100%;height:12px;"></div><a href="/Zapravka" class="topmenu">Заправка картриджей</a></div>
<div id="delimeter_top_menu"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:3px;"></div><a href="/remont-printerov.html" class="topmenu">Ремонт принтеров,<br>копиров, МФУ</a></div>
<div id="delimeter_top_menu"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:3px;"></div><a href="/remont-komputerov.html" class="topmenu">Ремонт компьютеров,<br/>ноутбуков, мониторов</a></div>
<div id="delimeter_top_menu"></div></div>
';
$top_menu2='
<div><div id="top_menu" width="100%">
<div id="delimeter_top_menu" style="width:10px;"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:12px;"></div><a href="/" class="topmenu">Главная</a></div>
<div id="delimeter_top_menu"></div>
<div class="lb"></div>
<div id="top_menu_back_white"><div style="width:100%;height:12px;"></div><a href="/Zapravka" class="topmenu">Заправка картриджей</a></div>
<div class="rb"></div>
<div id="delimeter_top_menu"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:3px;"></div><a href="/remont-printerov.html" class="topmenu">Ремонт принтеров,<br>копиров, МФУ</a></div>
<div id="delimeter_top_menu"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:3px;"></div><a href="/remont-komputerov.html" class="topmenu">Ремонт компьютеров,<br/>ноутбуков, мониторов</a></div>
<div id="delimeter_top_menu"></div></div>
';
$top_menu3='
<div><div id="top_menu" width="100%">
<div id="delimeter_top_menu" style="width:10px;"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:12px;"></div><a href="/" class="topmenu">Главная</a></div>
<div id="delimeter_top_menu"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:12px;"></div><a href="/Zapravka" class="topmenu">Заправка картриджей</a></div>
<div id="delimeter_top_menu"></div>
<div class="lb"></div>
<div id="top_menu_back_white"><div style="width:100%;height:3px;"></div><a href="/remont-printerov.html" class="topmenu">Ремонт принтеров,<br>копиров, МФУ</a></div>
<div class="rb"></div>
<div id="delimeter_top_menu"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:3px;"></div><a href="/remont-komputerov.html" class="topmenu">Ремонт компьютеров,<br/>ноутбуков, мониторов</a></div>
<div id="delimeter_top_menu"></div></div>
';
$top_menu4='
<div><div id="top_menu" width="100%">
<div id="delimeter_top_menu" style="width:10px;"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:12px;"></div><a href="/" class="topmenu">Главная</a></div>
<div id="delimeter_top_menu"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:12px;"></div><a href="/Zapravka" class="topmenu">Заправка картриджей</a></div>
<div id="delimeter_top_menu"></div>
<div id="top_manu_back_grey"><div style="width:100%;height:3px;"></div><a href="/remont-printerov.html" class="topmenu">Ремонт принтеров,<br>копиров, МФУ</a></div>
<div class="lb"></div>
<div id="top_menu_back_white"><div style="width:100%;height:3px;"></div><a href="/remont-komputerov.html" class="topmenu">Ремонт компьютеров,<br/>ноутбуков, мониторов</a></div>
<div class="rb"></div>
</div>
';

?>
