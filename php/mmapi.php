<?php
$conn= mysql_connect('localhost','root','roots'); 
    $db = mysql_select_db("news");
	$s="select * from summary";
	$im=mysql_query($s);
	$ra=array();
if(mysql_num_rows($im)>0)
				{
					while($rs1=mysql_fetch_array($im))
					{
						$ra[]=$rs1['id'];
						$ra[]=$rs1['title'];
						$ra[]=$rs1['summary'];
					}
				}
				//print_r($ra);
				$sum=array('id','title','summary');

$count1 = count($sum);
           $count2 = count($ra);
           $numofloops = $count2/$count1;
               
           $i = 0;
           while($i < $numofloops){
               $arr3 = array_slice($ra, $count1*$i, $count1);
               $arr4[] = array_combine($sum,$arr3);
               $i++;
           }
				
	print_r(json_encode( $arr4));
	?>
					