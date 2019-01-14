<?php
require 'req/mysql.php';
include('header.php');

//Выводим новость
if($_GET[page_id])
{
$cartridge->sql_query="select pages.id, page_title,html_title, m_desc, page_content, url_name,m_kwords,page_cat,page_cat.name,date FROM pages
        JOIN page_cat ON page_cat=page_cat.id
         where publish='1' and pages.id=$_GET[page_id];";
$cartridge->sql_execute();

list($page_id, $page_title,$set_title,$set_description,$content, $cat_url_name, $set_keywords,$page_cat_id,$page_cat_name,$date)=mysql_fetch_array($cartridge->sql_res);

    $page_content="<table width=75% style='margin-left:95px;margin-top:15px;'>
    <tr><td class=\"page_title\">$page_title</td></tr></table>
    <table width=75% style='margin-left:105px;margin-top:15px;'>
    <tr><td>".str_replace('/ddruk.local', 'http://ddruk.com.ua', $content)."</td></tr>
        <tr><td><br>".date('d.m.Y',$date)."</td></tr>
    </table>";
    
$PAGE_CAT_NAME=$page_cat_name;
$PAGE_CAT_ID=$page_cat_id;
$PAGE_ID=$page_id;


}

//Формируем блок еще новости раздела
 $cartridge->sql_query="select pages.id, picture, page_title, m_desc, date FROM pages
        where publish='1' and page_cat='$PAGE_CAT_ID' and pages.id not IN($PAGE_ID,63)
        ORDER by date DESC limit 20";
$cartridge->sql_execute();
$more_news='

<table border=0 cellspacing=0 cellpading=0 style="margin-left:95px;margin-bottom:15px; width:700px;">
    ';
$t=0;
while(list($page_id, $pic,$page_title,$desc, $date)=mysql_fetch_array($cartridge->sql_res))
{
    $more_news.='<tr>
        <td class="news_text_preview" width="72px;">'.date('d.m.Y',$date).' -</td><td class="news_text_preview">
     <a class="public_block_title" href="'.$base_url.'/news/'.$page_id.'-'.translit($page_title).'.html">'.$page_title.'</a>
      </td>
    </tr>';
}
$more_news.='</table>';


//А если категория то выводим категорию
if($_GET[page_cat])
{
     $cartridge->sql_query="select pages.id, picture, page_title, m_desc, name,date FROM pages
        JOIN page_cat ON page_cat=page_cat.id
        where publish='1' and page_cat.url_name='$_GET[page_cat]' 
             ORDER by date DESC;";
$cartridge->sql_execute();
$page_content='<table border=0 cellspacing=0 cellpading=0 style="margin-left:95px;margin-bottom:15px; width:700px;">';
while(list($page_id, $pic,$page_title,$desc, $cat_name, $date)=mysql_fetch_array($cartridge->sql_res))
{
    $page_content.='<tr>
        <td class="news_text_preview" width="72px;">'.date('d.m.Y',$date).' -</td><td class="news_text_preview">
     <a class="public_block_title" href="'.$base_url.'/news/'.$page_id.'-'.translit($page_title).'.html">'.$page_title.'</a>
      </td>
    </tr>';
    $PAGE_CAT_NAME=$cat_name;
}
$page_content.='</table>';
    
}


include 'header_html.php';
echo $top;
echo $top_menu1;

?>

    <div style="float:center;min-width: 1001px; width:100%;" >
    <div id="content">
        <?php if($PAGE_CAT_ID!=5) echo '<div class="zaglav_w_line_page" style="margin-left: 95px;margin-top: 20px; width: 75%;">'.$PAGE_CAT_NAME.'</div>';?>
        
        <?php echo $page_content;?>
    
    <?php if($_GET[page_id]) {?>
        <div id="">
            <div class="zaglav_w_line_page" style="margin-left: 95px;margin-top: 50px; width: 700px;">Еще публикации раздела</div>
                <?php echo $more_news;?>
        </div>
        <?php }?>
        <br>
    </div>
        
           <div class="right_block"><?php include('right_block.php');?></div>  
   
</div>
<?php include ('bottom.php');?>
    </body>
</html>
