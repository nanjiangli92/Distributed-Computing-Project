
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="main.css" />
<meta charset="utf-8">
<script src="echarts.min.js"></script>
<script type = "text/javascript" src = "jquery-1.11.2.min.js"></script>
<script src="pass_variables.js" type="text/javascript"></script>
<script src="ajex.js" type="text/javascript"></script>

<title>result</title>
<?php
	$wherelist=array();
	$conn=mysqli_connect('localhost','root','','projecttest') or die("failed to connect");
	
	$type=$_GET["type"];
	$sql='';
	$sql1='';
	$keyword_str='';
	$name_str='';
	switch ($type){
		case "keyword": 	$keyword=$_GET["keyword"];
							$keyword_str="select distinct staff.id as id, fst_name, lst_name, departmentname from staff join department join paper join paperrelationshipstaff on staff.department=department.id and paper.id=paperrelationshipstaff.paper and paperrelationshipstaff.staff=staff.id where departmentname like '%$keyword%' or fst_name like '%$keyword%' or lst_name like '%$keyword%' or staff.id in (select distinct staff.id from staff join paperrelationshipstaff join paper on staff.id=paperrelationshipstaff.staff and paperrelationshipstaff.paper=paper.id where field like '%$keyword%')";
							$url="type=keyword&keyword={$keyword}";
							break;
			
		case "name": 		
							$fst_name=$_GET["first_name"];
							$lst_name=$_GET["last_name"];
							$school=$_GET["school"];
							if(!empty($fst_name)){
								$wherelist[]="fst_name like '%$fst_name%'";								
							}
							if(!empty($lst_name)){
								$wherelist[]="lst_name like '%$lst_name%'";
							}
							if(!empty($school)){
								$wherelist[]="departmentname like '%$school%'";
							}
							$url="type=name&first_name={$fst_name}&last_name={$lst_name}&school={$school}";
							$name_str="select * from staff join department on staff.department=department.id";
							
							break;
			
		case "people":		$keyword=$_GET["keyword"];
							$lst_name=$_GET["last_name"];
							$school=$_GET["school"];
							if(!empty($keyword)){
								$wherelist[]="(departmentname like '%$keyword%' or fst_name like '%$keyword%' or lst_name like '%$keyword%' or staff.id in (select distinct staff.id from staff join paperrelationshipstaff join paper on staff.id=paperrelationshipstaff.staff and paperrelationshipstaff.paper=paper.id where field like '%$keyword%'))";
							}
							if(!empty($lst_name)){
								$wherelist[]="lst_name like '%$lst_name%'";
							}
							if(!empty($school)){
								$wherelist[]="departmentname like '%$school%'";
							}
							$url="type=people&keyword={$keyword}&last_name={$lst_name}&school={$school}";
							$people_str="select distinct staff.id as id, fst_name, lst_name, departmentname from staff join department join paper join paperrelationshipstaff on staff.department=department.id and paper.id=paperrelationshipstaff.paper and paperrelationshipstaff.staff=staff.id";
							break;
			
		case "everything":	$keyword=$_GET["keyword"];
							break;
	}
	$where="";
	if(count($wherelist)>0)
	{
		$where=" where ".implode(' and ',$wherelist);
	}
	
	switch ($type){
		case "keyword": 	
							$sql=$keyword_str;
							break;
			
		case "name": 		
							$sql=$name_str." ".$where;
							break;
			
		case "people":		
							$sql=$people_str." ".$where;
							break;
			
		case "everything":	
							$sql=$concept_str;
							break;
	}
	
	$result=mysqli_query($conn,$sql);
	$totalnum=mysqli_num_rows($result);
	$pagesize=10;
	$maxpage=ceil($totalnum/$pagesize);
	$page=$_GET["page"];
	if($page <1)
	{
		$page=1;
	}
	if($page>$maxpage)
	{
		$page=$maxpage;
	}
	$limit=" limit ".($page-1)*$pagesize.",$pagesize";
	switch ($type){
		case "keyword": 	$sql1=$keyword_str." order by staff.id asc $limit"; 
							break;
			
		case "name": 		
							$sql1=$name_str."$where order by staff.id asc $limit"; 
							
							break;
			
		case "people":		
							$sql1=$people_str."$where order by staff.id asc $limit";
							break;
			
		case "everything":	
							$sql1=$concept_str." order by staff.id asc $limit";
							break;
	}
	
	$res=mysqli_query($conn,$sql1);
	mysqli_close($conn);
	
	
?>

</head>

<body class="homepage" onLoad="GetRequest()">
	<div id="header-wrapper">
		<a href="http://www.unimelb.edu.au/"><img id="unimelb" src="unimelb1.jpg"></a>
		<div style="width: 50%; height: 5px; margin-left: 30%;"></div>
		<h2 style="margin-left: 37%;">Unimelb Academic Profile</h2>
	</div>
	<div id="content">
		<div class="left-wrapper">
			<div class="search-box">
				<div class="search-header"></div>
				<label>Keywords</label><br>
				<input type="text" id="keyword" placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
				<label>Last name</label><br>
				<input type="text" id="last_name" placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
				<label>School/Department</label><br>
				<input type="text" id="school" placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
				<input type="submit" id="findpeople" value="Find People" onClick="find_people()"/><br>	
				<div class="search-bottom"></div>
			</div>
			<div class="menu">
				<div class="menu-header"></div>
				<ul>
					<li><a href="index.php">Find people</a></li>
					<li><a href="index-concept.php">Find concept</a></li>
				</ul>
				<div class="menu-bottom"></div>
			</div>
		</div>
		<div class="main-wrapper">
			<div class="centre-wrapper">
				<div class="main-header"></div>
				<div class="title">
					<p style="margin-left: 20px; font-size: 18px; font-weight: 500;">Search result: <?php echo $totalnum ?></p>
				</div>
				<div class="result-table">
					
				</div>
				<div class="main_container">
					<?php if($totalnum>0){ ?>
					<table width=97% class="result" style="border: 1px solid #A2A2A2; margin-left: 1.5%;">
 						<tr class="title" style="height: 30px; font-size: 18px;">
 							<td style="border-right: 1px solid #959595; border-bottom: 1px solid #959595; background: #F4F4F4;"> Name</td>
							<td style="border-bottom: 1px solid #959595;background: #F4F4F4;"> Institution</td>
 						</tr>
						<?php 
							$index=0;
							while($row=mysqli_fetch_assoc($res)){
								$index=$index+1;
						?>
								<tr class="person" style="height: 26px; font-size: 16px;">
 									<td style="border-right: 1px solid #959595; border-bottom: 1px solid #959595;" onMouseOver="showDetail('personal-query.php',<?php echo"'".($index)."'" ?>)" onMouseOut="clean()"><?php echo "<a id='href".($index)."' href='personal-main.php?id={$row['id']}'>{$row['fst_name']} {$row['lst_name']}</a>"; ?>
									</td>
 									<td style="border-bottom: 1px solid #959595; " onMouseOver="showDetail('personal-query.php',<?php echo"'".($index)."'" ?>)" onMouseOut="clean()"><?php echo $row['departmentname']; ?></td>
 								</tr>
						<?php 
							} 
						?>
						
					</table>
					<table width=97% class="page" style="border: 1px solid #A2A2A2; margin-left: 1.5%; font-size: 18px;">
						<tr>
						 	<div style="background: #F4F4F4; float: left; width: 97%; margin-left: 1.5%;">
								
 								<div style="float: left; margin-left: 140px;font-size: 18px;"><?php echo "<a href='result.php?page=1&{$url}'><img src='arrow_first.gif' style='height: 10px;'></a> ";?></div>
								<div style="float: left; margin-left: 25px; font-size: 18px;"><?php echo "<a href='result.php?page=".($page-1)."&{$url}'>prev</a>";?></div>
								<div style="float: left; margin-left: 25px; font-size: 18px;"><?php echo " {$page} of {$maxpage}    ";?></div>
								<div style="float: left; margin-left: 25px; font-size: 18px;"><?php echo "<a href='result.php?page=".($page+1)."&{$url}'>next</a>";?></div>
								<div style="float: left; margin-left: 25px; font-size: 18px;"><?php echo "<a href='result.php?page={$maxpage}&{$url}'><img src='arrow_last.gif' style='height: 10px;'></a> ";?></div>
							 	
							
							</div>
						</tr>
					</table>
					<?php } ?>
				</div>
			</div>
			<div class="right-wrapper">
				<div class="right-header"></div>
				<div class="current">
					<p style="font-size:18px; font-family: Arial; color: #014085; "> Search Criteria: </p>
					<p id="criteria1" style="font-size:16px; font-weight: 700; font-family: Arial; color: #840608; "></p>
					<p id="criteria2" style="font-size:16px; font-weight: 700; font-family: Arial; color: #840608; "></p>
					<p id="criteria3" style="font-size:16px; font-weight: 700; font-family: Arial; color: #840608; "></p>
					<hr style="margin: auto;width: 95%;color:#7D7D7D;"></hr>
					
				</div>
				<div class="personal-detail" style="width: 95%;">
					<p id="detail1" style="text-decoration: underline;font-size: 15px; font-weight: 600;  color: #840608;"></p>
					<p id="detail2" style="font-size: 15px;"></p>
					<p id="detail3" style="text-decoration: underline;font-size: 15px;  font-weight: 600; color: #840608;"></p>
					<p id="detail4" style="font-size: 15px;"></p>
					<p id="detail5" style="text-decoration: underline;font-size: 15px;  font-weight: 600; color: #840608;"></p>
					<p id="detail6" style="font-size: 15px;"></p>
					<p id="detail7" style="text-decoration: underline;font-size: 15px;  font-weight: 600; color: #840608;"></p>
					<p id="detail8" style="font-size: 15px;"></p>
				</div>
				
        	</div> 
		</div>
		    
	</div>
    <div id="bottom">
    	<div style="height: 5px;"></div>
		<h4>Nanjiang Li, UniMelb</h4>
		<h4>Kaiqing Wang, Unimelb</h4>
	</div>       
       	
</body>
</html>
