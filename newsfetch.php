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
$casper->start('http://english.manoramaonline.com/news.html');
$casper->capture(
    array(
        'top' => 0,
        'left' => 0,
        'width' => 800,
        'height' => 600
    ),
    '/var/www/html/images/csbank.png'
); 
$casper->run();
//print_r($casper->getNewWindow());
$debug = $casper->getOutput();
foreach($debug as $dg){
//echo $dg."</br>";
}
$conn= mysql_connect('localhost','root','roots'); 
    $db = mysql_select_db("news");

$html = str_replace('&nbsp;','',$casper->getHTML());
$DOM = new DOMDocument();
$DOM->loadHTML($html);
$xpath = new DOMXpath($DOM);
$Header = $xpath->query("//article[@class='storyclass1']/h4/a");
$details = $xpath->query("//article[@class='storyclass1']");
$image = $xpath->query("//article[@class='storyclass1']/a/img/@src");
$aTempData1 ='';
//$aDataTableheaderHTML='';
$aDataTableDetailHTML='';
$aTempData ='';
foreach($Header as $NodeHeader) 
	{
		$aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
	}
	print_r($aDataTableHeaderHTML);
	foreach($details as $Nodedet) 
	{
		$aTempData[]= trim($Nodedet->textContent);
	}
		foreach($image as $Nodedet) 
	{
		$aTempData1[]= trim($Nodedet->textContent);
	}
	$len=sizeof($aDataTableHeaderHTML);
	for($i=0;$i<($len-5);$i++)
	{
		$a=$aDataTableHeaderHTML[$i];
		$b=$aTempData[$i];
		$c=$aTempData1[$i];
		$a=str_replace("'",'"',$a);
		$b=str_replace("'",'"',$b);
		$val=str_replace($a,'',$b);
		$img='http://www.manoramaonline.com'.$c;
		$site='http://www.manoramaonline.com';
		/*$ss="select * from newscollection where title ='$a' and description ='$val'";
		$ss1=mysql_query($ss);
if(mysql_num_rows($ss1)>0)
				{
					
				}
		else
			{*/
	$sql="insert into newscollection(title,description,image,site) values ('$a','$val','$img','$site')";
	$r=mysql_query($sql);
		
	
		//}
	if($r)
	{
		echo "success";
		}
		
	}
	

?>