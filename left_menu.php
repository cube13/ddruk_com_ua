<?php
// Формирование меню монохромных картриджей
$menu_laser="";
$flag=0; $under_brand=0;
$cartridge->sql_query="select brands.id, brands.name from cartridge, brands
    where cartridge.publish='1' and (cartridge.type='0' or cartridge.type='1' )
    and cartridge.brand=brands.id group by brands.name";
$cartridge->sql_execute();

if(!$_GET[brand]) $set_brand=$BRAND;
else $set_brand=$_GET[brand];

while(list($id, $name)=mysql_fetch_row($cartridge->sql_res))
{
    $border_top=""; 
     if($flag==0){$border_top="style='border-top:1px solid black;'";}
    //if($name==$_GET[brand] || $name==$BRAND)
    if($name==$set_brand)
     {
        $cartridge_menu.="<div class='left_menu_item_active'>$name<br>";
        
        $is_set=new cls_mysql();
        $is_set->sql_connect();
        $is_set->sql_query="SELECT COUNT( id ) FROM cartridge 
            WHERE  `publish` =  '1' AND  `type` =  '0' AND  `brand` =".$brand_arr[$set_brand];
        $is_set->sql_execute();
         list($num_m)=  mysql_fetch_row($is_set->sql_res);
               
        $is_set->sql_query="SELECT COUNT( id ) FROM cartridge
WHERE  `publish` =  '1' AND  `type` =  '1' AND  `brand` =".$brand_arr[$set_brand];
        $is_set->sql_execute();
        list($num_c)=  mysql_fetch_row($is_set->sql_res);
        if($TYPE==0){
        if($num_m){$cartridge_menu.="&nbsp;&nbsp;<a class='in_menu_active' href=\"$base_url/zapravka-kartridgey-$name.html\"><b>монохромные</b></a>";}
        if($num_c){$cartridge_menu.="<br>&nbsp;&nbsp;<a class='in_menu_active' href=\"$base_url/zapravka-cvetnyh-kartridgey-$name.html\">цветные</a>";}
        }
        if($TYPE==1){
        if($num_m){$cartridge_menu.="&nbsp;&nbsp;<a class='in_menu_active' href=\"$base_url/zapravka-kartridgey-$name.html\">монохромные</a>";}
        if($num_c){$cartridge_menu.="<br>&nbsp;&nbsp;<a class='in_menu_active' href=\"$base_url/zapravka-cvetnyh-kartridgey-$name.html\"><b>цветные</b></a>";}
        }
        
        
        $cartridge_menu.="</div>";
        
        $BRAND=$name;
        $under_brand=1;
    }
    else
    {
        if($under_brand){$under_brand=0;
            $cartridge_menu.="<div style='width:158px;height:6px;float: right;
    background-color: #999999;border-left:1px solid black;border-top:1px solid black;border-right:1px solid black;
    position: relative;'></div>";}
        
        $cartridge_menu.="<div class='left_menu_item' $border_top><a class='left_menu'  href=\"$base_url/zapravka-kartridgey-$name.html\">$name</a></div>
        <div class='left_menu_item_reight_border' $border_top></div>";
        
    }
$brand_array[$id]=$name; $flag++;
}

echo $cartridge_menu;
?>

