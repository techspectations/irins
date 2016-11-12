<?php
$conn= mysql_connect('localhost','root','roots'); 
    $db = mysql_select_db("news");
	$s="select description from manorama ";
	$sql=mysql_query($s);
	if(mysql_num_rows($sql)>0)
				{
					while($res=mysql_fetch_array($sql))
					{
	$r=$res['description'];

	$a1=explode(' ',$r);
	$len =sizeof($a1);
$count=0;
$r1='';
	for($i=0;$i<$len;$i++)
	{
		$b=$a1[$i];
$bb=strlen($b);
		$w="select * from grammer where common='$b'";
		$sql2=mysql_query($w);
		$res2=mysql_fetch_array($sql2);
			//print_r($res2);		
			if(!$res2)
			{
				if($bb>2)
				{
				echo "<br/><br/>".$b."<br/>";
				$query="select description from newscollection where description like '$b%' or description like '%$b%'";
				$sql1=mysql_query($query);
	
				if(mysql_num_rows($sql1)>0)
				{
					while($res1=mysql_fetch_array($sql1))
					{
						$r1[]=$res1['description'];
					
					}
				}
				$count++;
				}
				
			}
			
			
			else
			{
				echo "...................";
			}
		
	}
		
	
	//echo  "<br/>";
	$u=array_unique($r1);
//print_r($u);

$aw='';
	$arr=array_count_values($r1);
	$d=$count-10;
	foreach ($arr  as $key => $value) 
	{	
   //echo $key.' - '.$value.'<br>'; 
	$c=$value;
	
	//echo '<br/>';
			if($c>$d)
			{
				
				$aw[]=$key;
				
			}
			else
			{
				continue;
			}
			
				}
				
/*print_r($aw);
$lz=count($aw);
echo $lz;
for($i=0;$i<$lz;$i++)
{
	$imgs=$aw[i];
	echo $imgs;
	$imgs1="select image from newscollection where description ='$imgs'";
	$im=mysql_query($imgs1);
	
				if(mysql_num_rows($im)>0)
				{
					while($rs1=mysql_fetch_array($im))
					{
						$re1[]=$rs1['image'];
					
					}
				}
			print_r($re1);
}*/
				

$news1=implode(".",$aw);
$long_article=str_replace("..",".",$news1);
//echo $long_article.'<br/>';
$ch = curl_init("http://api.smmry.com/&SM_API_KEY=B99EFC19C9&SM_LENGTH=2&SM_WITH_BREAK");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:")); // Important do not remove
curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch, CURLOPT_POSTFIELDS, "sm_api_input=".$long_article);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$return = json_decode(curl_exec($ch), true);
curl_close($ch);
$g= $return['sm_api_content']; 
$sp=str_replace('[BREAK]','.',$g);
$sp=str_replace("'",'',$sp);
		$sp=str_replace(",",' ',$sp);
echo $sp;
if($sp == '')
{
	$sp=$long_article;
}
$sum="insert into summary(summary)values('$sp')";
	$sum1=mysql_query($sum);
	
	if($sum1)
	{
		echo "success";
		
	
	}
					}
				}
				$ty="update summary,manorama set summary.title =manorama.title where manorama.id=summary.id";
		$ty1=mysql_query($ty);
		
	
?>