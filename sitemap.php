<?php 

include "include/main.inc.php";

header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="'.$GLO_SITE['url_site'].'sitemap.xsl"?>';



?>

<!-- generated -->
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">	
<url>
		<loc><?php echo $GLO_SITE['url_site'];?></loc>
		<changefreq>monthly</changefreq>
		<priority>1.0</priority>

</url>

<?php
	$tab = explode("\n",getSetting('menu'));
	foreach($tab as $k=>$v) { 
		list($lib,$url) = explode('=>',$v);
		$lib=trim($lib);
		$url=trim($url);
		if(is_numeric($url)) {
			$page = getPage($url);
			$url = url_page($page);
		}
?>
<url>
		<loc><?php echo $GLO_SITE['url_site'];?><?php echo $url;?></loc>
		<changefreq>weekly</changefreq>
		<priority>1</priority>

</url>
<?php }?>
<?php
$les_news = getNews(0,100);
foreach($les_news as $k=>$v) {
?>
<url>
		<loc><?php echo $GLO_SITE['url_site'];?><?php echo url_news($v);?></loc>
		<changefreq>weekly</changefreq>
		<priority>1</priority>

</url>
<?php }?>




</urlset>