<?php
$db_config = array(
	'host' => 'localhost',
	'user' => 'DB USER',
	'pass' => 'DB PASS',
	'name' => 'DB Name'
);
 $mysqli = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
// set charset to UTF-8
$mysqli->set_charset("utf8");
$sql = "SELECT * FROM /*Table Name*/ WHERE published='1' ORDER BY id DESC LIMIT 1000";
if (!$result = $mysqli->query($sql)) {
    die ('There was an error running query[' . $mysqli->error . ']');
}


$xml = new XMLWriter();
$xml->openMemory();
//Start The XML FILE
$xml->startDocument('1.0', 'UTF-8');
$xml->setIndent(true); 


/*
$xmlns = 'http://www.sitemaps.org/schemas/sitemap/0.9';
$xml->startElementNS(NULL, 'urlset', $xmlns);


*/




$xml->startElement('urlset');
//this adds the correct namespace for the sitemap specification
$xml->writeAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
$xml->writeAttribute('xmlns:news','http://www.google.com/schemas/sitemap-news/0.9');

date_default_timezone_set("Africa/Cairo");
while ($row = $result->fetch_assoc()) {
$Content = preg_replace("/&#?[a-z0-9]{2,8};/i","",$row["title"]);
$str = preg_replace("/q#?[a-z0-9]{2,8};/i"," ",$Content);
//Start The url ELEMENT
$xml->startElement('url');
//Start The url LOCation
$xml->startElement('loc');
$xml->text('http://www.qalag.com/news/'.$row["id"].'/');
$xml->endElement();
//Start The News ELEMENT
$xml->startElement('news:news');
//Start The publication 
$xml->startElement('news:publication');
//Start The name 
$xml->startElement('news:name');
$xml->text($str);
//End The Name 
$xml->endElement();
//Start The Lang 
$xml->startElement('news:language');
$xml->text('ar');
//End The Lang 
$xml->endElement();
//End The publication 
$xml->endElement();
//Start The publication_date 
$xml->startElement('news:publication_date');
$xml->text(date('Y-m-d'));
//End The publication_date 
$xml->endElement();
//Start The Keywords 
$xml->startElement('news:keywords');
$xml->text($str);
//End The Keywords 
$xml->endElement();
//Start The title 
$xml->startElement('news:title');
$xml->text($str);
//End The title 
$xml->endElement();
//End The News 
$xml->endElement();
//End The Url 
$xml->endElement();

}
$xml->endDocument();
file_put_contents('newssitemap.xml', $xml->outputMemory());
mysqli_close($mysqli);
?>