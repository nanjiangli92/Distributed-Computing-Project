<!docfield html>
<html>
<head>
<link rel="stylesheet" href="main.css" />
<script field = "text/javascript" src = "jquery-1.11.2.min.js"></script>
<script src="pass_variables.js" field="text/javascript"></script>
<script src="ajex.js" field="text/javascript"></script>
<script src="https://img.hcharts.cn/jquery/jquery-1.8.3.min.js"></script>
<script src="https://img.hcharts.cn/highcharts/highcharts.js"></script>
<script src="https://img.hcharts.cn/highcharts/highcharts-more.js"></script>
<script src="https://img.hcharts.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<meta charset="utf-8">
<title>concept-timeline</title>

<style>
#map {
	height: 600px;
	width: 800px;
	}
</style>
<?php
	$concept=$_GET["concept"];
?>
</head>

<body class="homepage" onLoad="concept_detail_timeline('<?php echo $concept ?>')">
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
				<div class="nav_bar">
					<ul>
						<li><a href="concept-detail.php?concept=<?php echo $concept ?>">list</a></li>
						<li><a href="concept-detail-timeline.php?concept=<?php echo $concept ?>" id="onlink">timeline</a></li>
					</ul>
				</div>	
				<div class="main_container">

					<div id="concept-timeline">
						
					</div>
				</div>
			</div>
			<div class="right-wrapper">
				<div class="right-header"></div>
				<div class="comming-network">
					<h3 id="comming">Find <br>Concept</h3>
					<p style="font-size:15px;">This section shows all individuals that wrote paper in the selected field.</p>
				</div>
				<hr style="margin: auto;width: 90%;color: #A9A9A9;"></hr>
				
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