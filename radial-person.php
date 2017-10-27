<?php 
	$id=$_GET["id"];

	$conn=mysqli_connect('localhost','root','','projecttest') or die("failed to connect");
	$sql="select fst_name, lst_name from staff where id=$id";
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