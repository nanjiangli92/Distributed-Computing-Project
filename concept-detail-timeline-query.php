<?php 
	$concept=$_GET["concept"];

	$conn=mysqli_connect('localhost','root','','projecttest') or die("failed to connect");
	$sql="select distinct staff.id, max(publishedtime) as latest, min(publishedtime) as earliest, fst_name, lst_name from paper join paperrelationshipstaff join staff on paper.id=paperrelationshipstaff.paper and paperrelationshipstaff.staff=staff.id where staff.id in (select distinct staff.id from paper join paperrelationshipstaff join staff on paperrelationshipstaff.paper=paper.id and paperrelationshipstaff.staff=staff.id where paper.field='$concept') group by staff.id";
	$result=mysqli_query($conn,$sql);
	$jarr = array();
	while ($rows=mysqli_fetch_array($result,MYSQL_ASSOC)){
    	$count=count($rows);//不能在循环语句中，由于每次删除 row数组长度都减小  
    	for($i=0;$i<$count;$i++){  
        	unset($rows[$i]);//删除冗余数据  
    	}
    	array_push($jarr,$rows);
	}
	$json= json_encode($jarr);
	echo $json;
	
?>