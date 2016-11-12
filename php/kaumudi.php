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
$casper->start('http://www.kaumudi.com/sectionpage.php?news_category=india');
$casper->capture(
    array(
        'top' => 0,
        'left' => 0,
        'width' => 800,
        'height' => 600
    ),
    '/var/www/html/images/kauubumi.png'
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
$Header = $xpath->query("//td[@class='title']/a");
$detail=$xpath->query("//div[@align='justify']");
$image = $xpath->query("//table[@class='bottomborder']/tbody/tr/td/a/img/@src");
$aDataTableDetailHTML='';
$aTempData ='';
$aTempData1='';
$conn= mysql_connect('localhost','root','roots'); 
    $db = mysql_select_db("news");
		foreach($image as $Nodedet) 
	{
		$aTempData1[]= trim($Nodedet->textContent);
	}
	//print_r($aTempData1);
foreach($Header as $NodeHeader) 
	{
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
	}
	//print_r($aDataTableHeaderHTML);
	foreach($detail as $Nodedet) 
	{
		$aTempData[]= trim($Nodedet->textContent);
	}
	//print_r($aTempData);
	$len=sizeof($aDataTableHeaderHTML);
	for($i=0;$i<$len;$i++)
	{
		$a=$aDataTableHeaderHTML[$i];
		$b=$aTempData[$i];
		$c=$aTempData1[$i];
		$a=str_replace("'",'"',$a);
		$b=str_replace("'",'"',$b);
		//$img='http://www.kaumudi.com/'.$c;
		//echo $img;
		$site='http://www.kaumudi.com/';
$sql="insert into newscollection(title,description,site) values ('$a','$b','$site')";
	$r=mysql_query($sql);
	if($r)
	{
		echo "success";
		}else{echo "notsucess";}
	}
	
?>