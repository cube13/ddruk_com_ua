<?php
/*
$cartridge->sql_query="SELECT count(*) as counts, cartridge.name, brands.name FROM `reestr`
join cartridge on reestr.name_id=cartridge.id
join brands on cartridge.brand=brands.id
group by name_id
order by counts desc, brands.name ASC, cartridge.name asc limit 15";
$cartridge->sql_execute();

while(list($kolvo,$name,$brand)=mysql_fetch_row($cartridge->sql_res))
{
    $top_cartridges.="<a href=\"$base_url/zapravka-i-vosstanovlenie-$name.html\">Заправка картриджа $brand $name</a><br>";
}
*/
$cartridge->sql_query="select pages.id, page_title, m_desc FROM pages
            WHERE publish='1' and page_cat=3";
    $cartridge->sql_execute();
    $t_=0;
    while(list($page_id,$page_title,$m_desc)=mysql_fetch_array($cartridge->sql_res))
    {
        $top_pages[$t_][page_id]=$page_id;
        $top_pages[$t_][page_title]=$page_title;
        $top_pages[$t_][desc]=$m_desc;

        $t_++;
    }
    $random_advice=rand(0,$t_-1);

    $advice="<div class=\"advice\"><b>".$top_pages[$random_advice][page_title]."</b><br>".$top_pages[$random_advice][desc]."</div>";

?>
    
<div class="zaglav_w_line_r">Добрый совет</div>
<?php echo $advice;?>
<br>
<!--
<div class="zaglav_w_line_r">Заправляйте у нас</div>
<?php echo $top_cartridges;?>
-->



