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
$casper->start('http://timesofindia.indiatimes.com/india');
$casper->wait(mt_rand(1000, 1500));
$casper->capture(
    array(
        'top' => 0,
        'left' => 0,
        'width' => 800,
        'height' => 600
    ),
    '/var/www/html/images/timesofindia.png'
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
$Header = $xpath->query("//div[@id='fsts']/h2/a");
$detail=$xpath->query("//div[@id='fsts']/span[@id='dtfrmt1']/span[@class='bln']");
$aDataTableDetailHTML='';
$aTempData ='';
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
	//print_r($aTempData);
	$len=sizeof($aTempData);
	for($i=0;$i<$len;$i++)
	{
		$a=$aDataTableHeaderHTML[$i];
		
		$b=$aTempData[$i];
		$a=str_replace("'",'"',$a);
		$b=str_replace("'",'"',$b);
		$site='http://timesofindia.indiatimes.com';
		$sql="insert into newscollection(title,description,site) values ('$a','$b','$site')";
	$r=mysql_query($sql);
	if($r)
	{
		echo "success";
		}else{echo "notsucess";}
	}
	
?>