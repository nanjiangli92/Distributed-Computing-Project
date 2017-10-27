
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
	$keyword=$_GET["keyword"];
	$sql="select distinct field from paper where field like '%$keyword%'";
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
	$url="type=concept&keyword={$keyword}";
	$sql1=$sql." order by paper.id asc $limit";
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
			<div class="search-concept">
				<div class="search-header"></div>
				<label>Keywords</label><br>
				<input type="text" id="keyword" placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
				<input type="submit" id="findconcept" value="Find Concept" onClick="concept()"/><br>	
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
					<?php 
						if($totalnum!=0){
					?>
					<table width=97% class="result" style="border: 1px solid #A2A2A2; margin-left: 1.5%;">
						<tr class="title" style="height: 30px; font-size: 18px;">
							<td style="border-bottom: 1px solid #959595; background: #F4F4F4;">Selected Concept</td>
						</tr>
					<?php		
							while($row=mysqli_fetch_assoc($res)){
					?>
							<tr class="concept-row" style="height: 26px; font-size: 16px;">
								<td style="margin-left: 10px;">
						        	<?php echo "<a href='concept-detail.php?concept={$row['field']}'>◈ {$row['field']}</a>"; ?>		
 								</td>
							</tr>
 					<?php
							}
					?>
					</table>
					<table width=97% class="page" style="border: 1px solid #A2A2A2; margin-left: 1.5%; font-size: 18px;">
						<tr>
						 	<div style="background: #F4F4F4; float: left; width: 97%; margin-left: 1.5%;">
								
 								<div style="float: left; margin-left: 140px;font-size: 18px;"><?php echo "<a href='concept-result.php?page=1&{$url}'><img src='arrow_first.gif' style='height: 10px;'></a> ";?></div>
								<div style="float: left; margin-left: 25px; font-size: 18px;"><?php echo "<a href='concept-result.php?page=".($page-1)."&{$url}'>prev</a>";?></div>
								<div style="float: left; margin-left: 25px; font-size: 18px;"><?php echo " {$page} of {$maxpage}    ";?></div>
								<div style="float: left; margin-left: 25px; font-size: 18px;"><?php echo "<a href='concept-result.php?page=".($page+1)."&{$url}'>next</a>";?></div>
								<div style="float: left; margin-left: 25px; font-size: 18px;"><?php echo "<a href='concept-result.php?page={$maxpage}&{$url}'><img src='arrow_last.gif' style='height: 10px;'></a> ";?></div>

							</div>
						</tr>
					</table>
					<?php 
						}
					?>
				</div>
			</div>
			<div class="right-wrapper">
				<div class="right-header"></div>
				<div class="current">
					<p style="font-size:18px; font-family: Arial; color: #014085; "> Search Criteria: </p>
					<p id="criteria1" style="font-size:16px; font-weight: 700; font-family: Arial; color: #840608; "></p>
					<hr style="margin: auto;width: 95%;color:#7D7D7D;"></hr>
					
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
