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
$casper->start('http://www.thehindu.com/news/national/');
$casper->capture(
    array(
        'top' => 0,
        'left' => 0,
        'width' => 800,
        'height' => 600
    ),
    '/var/www/html/images/hindud.png'
); 
$casper->run();
//print_r($casper->getNewWindow());
$debug = $casper->getOutput();
foreach($debug as $dg){
echo $dg."</br>";
}
$conn= mysql_connect('localhost','root','roots'); 
 $db = mysql_select_db("news");
$html = str_replace('&nbsp;','',$casper->getHTML());
$DOM = new DOMDocument();
$DOM->loadHTML($html);
$xpath = new DOMXpath($DOM);
$Header = $xpath->query("//div[@class='section-columns']/div[@class='left']/h3/a");
$detail=$xpath->query("//div[@class='section-columns']/div[@class='left']/div[@class='article-additional-info']");
$image= $xpath->query("//div[@class='section-columns']/div[@class='left']/a/img/@src");
$Header1 = $xpath->query("//div[@class='section-columns']/div[@class='right']/h3/a");
$detail1=$xpath->query("//div[@class='section-columns']/div[@class='right']/div[@class='article-additional-info']");
$image2= $xpath->query("//div[@class='section-columns']/div[@class='right']/a/img/@src");
$aDataTableDetailHTML='';
$aTempData ='';
$aTempData1='';
$aTempData2='';
$aTempData3='';
	foreach($image as $Nodedet) 
	{
		$aTempData1[]= trim($Nodedet->textContent);
	}
	print_r($aTempData1);
foreach($Header as $NodeHeader) 
	{
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
	}
	print_r($aDataTableHeaderHTML);
	foreach($detail as $Nodedet) 
	{
		$aTempData[]= trim($Nodedet->textContent);
	}
	//print_r($aTempData);
	$len=sizeof($aDataTableHeaderHTML);
	for($i=0;$i<5;$i++)
	{
		$a=$aDataTableHeaderHTML[$i];
		$c=$aTempData1[$i];
		$b=$aTempData[$i];
		$a=str_replace("'",'"',$a);
		$b=str_replace("'",'"',$b);
		$sql="insert into newscollection(title,description,image) values ('$a','$b','$c')";
	$r=mysql_query($sql);
	if($r)
	{
		echo "success";
		}else{echo "notsucess";}
	}
	foreach($image2 as $Nodedet) 
	{
		$aTempData2[]= trim($Nodedet->textContent);
	}
	print_r($aTempData2);
	foreach($Header1 as $NodeHeader) 
	{
		$aDataTableHeaderHTML1[] = trim($NodeHeader->textContent);
	}
	print_r($aDataTableHeaderHTML1);
	foreach($detail1 as $Nodedet) 
	{
		$aTempData3[]= trim($Nodedet->textContent);
	}
	//print_r($aTempData1);
	$len1=sizeof($aDataTableHeaderHTML1);
	for($i=0;$i<5;$i++)
	{
		$a=$aDataTableHeaderHTML1[$i];
		$c1=$aTempData2[$i];
		$b=$aTempData3[$i];
		$a=str_replace("'",'"',$a);
		$b=str_replace("'",'"',$b);
		$site='http://www.thehindu.com';
		$sql="insert into newscollection(title,description,site,image) values ('$a','$b','$site','$c1')";
	$r=mysql_query($sql);
	if($r)
	{
		echo "success";
		}else{echo "notsucess";}
	}
	
?>