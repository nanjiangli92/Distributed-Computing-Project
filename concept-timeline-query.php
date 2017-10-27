<?php 
	$id=$_GET["id"];

	$conn=mysqli_connect('localhost','root','','projecttest') or die("failed to connect");
	$sql="select max(publishedtime) as latest, min(publishedtime) as earliest, field from paper join paperrelationshipstaff on paper.id=paperrelationshipstaff.paper where field in (select distinct paper.field from paper join paperrelationshipstaff join staff on paper.id=paperrelationshipstaff.paper and paperrelationshipstaff.staff=staff.id where staff.id=$id) group by field";
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