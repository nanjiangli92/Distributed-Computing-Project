<!docfield html>
<html>
<head>
<link rel="stylesheet" href="main.css" />
<script field = "text/javascript" src = "jquery-1.11.2.min.js"></script>
<script src="pass_variables.js" field="text/javascript"></script>
<script src="ajex.js" field="text/javascript"></script>
<meta charset="utf-8">
<title>concept-detail</title>

<style>
#map {
	height: 600px;
	width: 800px;
	}
</style>
<?php
	$conn=mysqli_connect('localhost','root','','projecttest') or die("failed to connect");
	$concept=$_GET["concept"];
	$concept_author="select distinct staff.id as id,fst_name, lst_name from staff join paperrelationshipstaff join paper on staff.id=paperrelationshipstaff.staff and paper.id=paperrelationshipstaff.paper where paper.field='$concept'";
	$res_concept_person=mysqli_query($conn,$concept_author);

	mysqli_close($conn);
		
?>
</head>

<body class="homepage">
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
						<li><a href="concept-detail.php?concept=<?php echo $concept ?>" id="onlink">list</a></li>
						<li><a href="concept-detail-timeline.php?concept=<?php echo $concept ?>">timeline</a></li>
					</ul>
				</div>	
				<div class="main_container">

					<div class="similar-author" style="width: 99%; margin-left: 0.5%; margin-top: 10px; border: 1px solid #B6B6B6;" >
						<?php 
							while($concept_person=mysqli_fetch_assoc($res_concept_person)){
						?>
								<p style="margin-left: 10px;">
									<?php echo"<a href='personal-main.php?id={$concept_person['id']}'>◈ {$concept_person['fst_name']} {$concept_person['lst_name']}</a>" ?>
								</p>
						<?php
							}
						?>
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