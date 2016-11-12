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
$casper->start('http://indiatoday.intoday.in/section/114/1/india.html');
$casper->capture(
    array(
        'top' => 0,
        'left' => 0,
        'width' => 800,
        'height' => 600
    ),
    '/var/www/html/images/intoday.png'
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
$Header = $xpath->query("//div[@class='innerbox']/a");
$detail=$xpath->query("//div[@class='innerbox']");
$image = $xpath->query("//div[@class='posrel']/a/img/@data-original");
$aTempData1='';
$aDataTableDetailHTML='';
$aTempData ='';
		foreach($image as $Nodedet) 
	{
		$aTempData1[]= trim($Nodedet->textContent);
	}
	print_r($aTempData1);
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
		$c=$aTempData1[$i];
		$b=$aTempData[$i];
		$a=str_replace("'",'"',$a);
		$b=str_replace("'",'"',$b);
		$val=str_replace($a,'',$b);
		$site='http://indiatoday.intoday.in';
		$sql="insert into newscollection(title,description,image,site) values ('$a','$val','$c','$site')";
	$r=mysql_query($sql);
	if($r)
	{
		echo "success";
		}else{echo "notsucess";}
	}
	
?>