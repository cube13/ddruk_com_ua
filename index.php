<?php
require 'req/mysql.php';
include('header.php');

//Формируем блок публикаций
$cartridge->sql_query = "select pages.id, picture, page_title, m_desc, url_name,date FROM pages
        JOIN page_cat ON page_cat=page_cat.id
        where publish='1' and page_cat='2'
        ORDER by date DESC limit 10";
$cartridge->sql_execute();
$publication = '
<div class="zaglav_w_line"><a style="color:black;" href="' . $base_url . '/publication.html">Обзоры принтеров и публикации</a></div>

<table border=0 cellspacing=0 cellpading=0 style="margin-right:17px;">
    ';
$t = 0;
while (list($page_id, $pic, $page_title, $desc, $cat_url_name, $date) = mysql_fetch_array($cartridge->sql_res)) {
    $publication .= '<tr>
    <td class="news_small_pic">
        <img width="125" align="left" src="' . str_replace('/ddruk.local', 'http://ddruk.com.ua', $pic) . '"/>
    </td>
    <td class="news_text_preview">
     <a class="public_block_title" href="' . $base_url . '/' . $cat_url_name . '/' . $page_id . '-' . translit($page_title) . '.html">' . $page_title . '</a>
     <br/><span id="date_publication_preview">' . date("d.m.Y", $date) . '</span><br/>' . $desc . '
    </td>
    </tr>';
}
$publication .= '</table>';


//Формируем блок новостей
$cartridge->sql_query = "select pages.id, page_title, m_desc, url_name,date FROM pages
        JOIN page_cat ON page_cat=page_cat.id
         where publish='1' and page_cat='1'
         ORDER by date DESC limit 10";
$cartridge->sql_execute();
$world_news = '<table style="margin:5px 0px;">';
while (list($page_id, $page_title, $desc, $cat_url_name, $date) = mysql_fetch_array($cartridge->sql_res)) {
    $world_news .= '<tr><td valign="top"><div class="date_news_preview">' . date("d.m", $date) . '</div></td>
        <td style="padding: 0px 7px;padding-bottom:7px;" valign="top"><a class="news_block_title" href="' . $cat_url_name . '/' . $page_id . '-' . translit($page_title) . '.html">' . $page_title . '</a></td></tr>';
}
$world_news .= '</table>';

//Формируем блок корпоративных новостей
$cartridge->sql_query = "select pages.id, page_title, m_desc, url_name,date FROM pages
        JOIN page_cat ON page_cat=page_cat.id
         where publish='1' and page_cat='4'
         ORDER by date DESC limit 10";
$cartridge->sql_execute();
$corp_news = '<table style="margin-left:17px;margin-top:5px;">';
while (list($page_id, $page_title, $desc, $cat_url_name, $date) = mysql_fetch_array($cartridge->sql_res)) {
    $corp_news .= '<tr><td valign="top"><div class="date_news_preview">' . date("d.m", $date) . '</div></td>
        <td style="padding: 0px 7px;padding-bottom:7px;" valign="top"><a class="news_block_title" href="' . $cat_url_name . '/' . $page_id . '-' . translit($page_title) . '.html">' . $page_title . '</a></td></tr>';
}
$corp_news .= '</table>';


include 'header_html.php';
echo $top;
echo $top_menu1;
?>

<div style="float:center;min-width: 1001px; width:100%;">
    <div id="content">
        <br>
        <div id="news_company">
            <div class="zaglav_w_line" style="margin-left: 20px;"><a style="color:black;"
                                                                     href="<?php echo $base_url; ?>/corporation_news.html">Новости
                    компании</a></div>
            <?php echo $corp_news; ?>
        </div>

        <div id="news_world">
            <div class="zaglav_w_line"><a style="color:black;" href="<?php echo $base_url; ?>/news.html">Новости
                    рынка</a></div>
            <?php echo $world_news; ?>
        </div>
        <br>
        <div id="publication" style="margin-left: 20px;">
            <?php echo $publication; ?>
        </div>


    </div>


    <div class="right_block"><?php include('right_block.php'); ?></div>

</div>
<?php include('bottom.php'); ?>

<div xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate" class="bottom">
    <span property="v:itemreviewed" class="bottom">DDRUK.COM.UA</span>
    <span rel="v:rating">
      <span typeof="v:Rating">
         <span property="v:average">10</span> из <span property="v:best">10</span>
      </span>
   </span>
    на основе <span property="v:votes">10</span> оценок. <span property="v:count">10</span> клиентских отзывов.
</div>

</body>
</html>
