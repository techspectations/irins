<?php
$conn= mysql_connect('localhost','root','roots'); 
$db = mysql_select_db("news");
$search=$_POST['search'];
$s=array('search' => $search);
$sql="select * from newscollection where description like '%$search%' or description like '$search%' or description like '%$search' ";
	$im=mysql_query($sql);
	 $ra=array();
if(mysql_num_rows($im)>0)
{
while($rs1=mysql_fetch_array($im))
{
$ra[]=$rs1['id'];
$ra[]=$rs1['title'];
$ra[]=$rs1['description'];
$ra[]=$rs1['site'];
$ra[]=$rs1['image'];
}
}
//print_r($ra);
$sum=array('id','title','description','site','image');

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
//print_r(json_encode($s));

?>