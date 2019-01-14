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

$price_table=$page_content;

}

else
{

$cartridge->sql_query="SELECT value FROM settings WHERE id=1";
$cartridge->sql_execute();
if(!$cartridge->sql_err)
{
list($kurs_usd_nal)=mysql_fetch_row($cartridge->sql_res);
}

}
include 'header_html.php';
echo $top;
echo $top_menu4;
?>
<div style="float:center; width:100%;" >
 
    
    <div id="content">
        <div style="margin-left: 20px;">
            
      
<?php echo $price_table;?>
           
        </div>
        
        
    </div>

     <div class="right_block"><?php include('right_block.php');?></div>  
                
   
</div>
<?php include ('bottom.php');?>

    </body>
</html>
