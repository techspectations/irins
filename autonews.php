<?php
$conn= mysql_connect('localhost','root','roots'); 
    $db = mysql_select_db("news");
	$sql1="delete from manorama";
		$r1=mysql_query($sql1);
header("Location: ec2-35-154-31-121.ap-south-1.compute.amazonaws.com/ians.php");
		
?>