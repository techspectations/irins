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
$casper->start('http://english.mathrubhumi.com/news');
$casper->click('.showMore');
$casper->wait(mt_rand(250, 450));
$casper->capture(
    array(
        'top' => 0,
        'left' => 0,
        'width' => 800,
        'height' => 600
    ),
    '/var/www/html/images/mathrubumi.png'
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
$Header = $xpath->query("//div[@class='common_text listtitle col-md-8 col-sm-8 col-xs-12 lsTitl']/b");
$details = $xpath->query("//div[@class='common_text listtitle col-md-8 col-sm-8 col-xs-12 lsTitl']/p");
$image = $xpath->query("//div[@class='col-md-12 col-sm-12 col-xs-12']/a/div/img/@data-src");


$aTempData1='';
$aDataTableDetailHTML='';
$aTempData ='';
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
	foreach($details as $Nodedet) 
	{
		$aTempData[]= trim($Nodedet->textContent);
	}
	print_r($aTempData);
	$len=sizeof($aDataTableHeaderHTML);
	for($i=0;$i<$len;$i++)
	{
		$a=$aDataTableHeaderHTML[$i];
		$b=$aTempData[$i];
		$c=$aTempData1[$i];
		$a=str_replace("'",'"',$a);
		$b=str_replace("'",'"',$b);
		$val=str_replace($a,'',$b);
		$img='http://english.mathrubhumi.com'.$c;
		$site='http://english.mathrubhumi.com';
	$sql="insert into newscollection(title,description,image,site) values ('$a','$val','$img','$site')";
	$r=mysql_query($sql);
		
	}
	
?>