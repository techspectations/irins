<?php
require 'vendor/autoload.php';
use Browser\Casper;
$casper = new Casper();
$casper->setOptions(array(
    'ignore-ssl-errors' => 'yes',
	'ssl-protocol'      => 'any',
	'load-images' => 'false',
	'debug'=>'true'
));
$casper->setUserAgent('Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36');
$casper->start('http://www.ians.in/index.php?param=category/139/139');
$casper->capture(
    array(
        'top' => 0,
        'left' => 0,
        'width' => 800,
        'height' => 600
    ),
    '/var/www/html/images/ians.png'
); 
$casper->run();
//print_r($casper->getNewWindow());
$debug = $casper->getOutput();
foreach($debug as $dg){
echo $dg."</br>";
}
$html = str_replace('&nbsp;','',$casper->getHTML());
$DOM = new DOMDocument();
$DOM->loadHTML($html);
$xpath = new DOMXpath($DOM);
$Header = $xpath->query("//div[@id='pagecontent']/p/strong/a");
$details=$xpath->query("//div[@id='pagecontent']/p");
//$p=$xpath->query("//div[@id='pagecontent']/p/span");
//$q=$xpath->query("//div[@id='pagecontent']");


$aDataTableDetailHTML='';
$aTempData ='';
$aTempData1 ='';
//$aTempcon ='';
$conn= mysql_connect('localhost','root','roots'); 
    $db = mysql_select_db("news");
foreach($Header as $NodeHeader) 
	{
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
	}
print_r($aDataTableHeaderHTML);
	foreach($details as $NodeHeader) 
	{
		
		$aTempData[] = trim($NodeHeader->textContent);
	}
	print_r($aTempData);
	$len=sizeof($aTempData);
	for($i=1;$i<21;$i++)
	{
		$a=$aDataTableHeaderHTML[$i];
		$b=$aTempData[$i+1];
		$a=str_replace("'",'"',$a);
		$b=str_replace("'",'"',$b);
		$val=str_replace($a,'',$b);
		$v=explode("(IANS)",$val);
		$s=$v['1'];
		$site='http://www.ians.in';
		$sql1="insert into newscollection(title,description,site) values ('$a','$s','$site')";
		$r1=mysql_query($sql1);
		
		$sql="insert into manorama(title,description) values ('$a','$s')";
		$r=mysql_query($sql);
		
		 //echo $s."<br/>";
	}
	