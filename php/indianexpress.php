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
$casper->start('http://indianexpress.com/india/');
$casper->capture(
    array(
        'top' => 0,
        'left' => 0,
        'width' => 800,
        'height' => 600
    ),
    '/var/www/html/images/kinexi.png'
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
$Header = $xpath->query("//div[@class='caption']");
$detail=$xpath->query("//div[@class='stories']");
$image=$xpath->query("//div[@class='sto-img']/a/img/@src");

$aDataTableDetailHTML='';
$aTempData ='';
$aTempData1='';
$conn= mysql_connect('localhost','root','roots'); 
    $db = mysql_select_db("news");
foreach($Header as $NodeHeader) 
	{
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
	}
	//print_r($aDataTableHeaderHTML);
	foreach($detail as $Nodedet) 
	{
		$aTempData[]= trim($Nodedet->textContent);
	}
		foreach($image as $Nodedet) 
	{
		$aTempData1[]= trim($Nodedet->textContent);
	}
	print_r($aTempData1);
	$len=sizeof($aDataTableHeaderHTML);
	for($i=0;$i<$len-1;$i++)
	{
		$a=$aDataTableHeaderHTML[$i];
		$b=$aTempData[$i];
		$c=$aTempData1[$i];
		$val1=str_replace($a,'',$b);
		$a=str_replace("'",'"',$a);
		$val=str_replace("'",'"',$val1);
		$img='http://indianexpress.com'.$c;
		$site='http://indianexpress.com';
$sql="insert into newscollection(title,description,site,image) values ('$a','$val','$site','$img')";
	$r=mysql_query($sql);
	if($r)
	{
		echo "success";
		}
		else
		{
			echo "...not...";
		}
	}
	//print_r($aTempData);
	
?>