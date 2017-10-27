<!doctype html>
<html>
<head>
<link rel="stylesheet" href="main.css" />
<script type = "text/javascript" src = "jquery-1.11.2.min.js"></script>
<script src="pass_variables.js" type="text/javascript"></script>
<script src="ajex.js" type="text/javascript"></script>
<meta charset="utf-8">
<title>co-list</title>
<style>
#map {
	height: 600px;
	width: 800px;
	}
</style>
<?php
	$conn=mysqli_connect('localhost','root','','projecttest') or die("failed to connect");
	$id=$_GET["id"];
	$sql_personal="select fst_name,lst_name from staff where id=$id";
	$sql_similar_author="select distinct staff.id,fst_name,lst_name from staff join paperrelationshipstaff join paper on staff.id=paperrelationshipstaff.staff and paperrelationshipstaff.paper=paper.id where paper.field in (select distinct paper.field from staff join paperrelationshipstaff join paper on staff.id=paperrelationshipstaff.staff and paperrelationshipstaff.paper=paper.id where staff.id=$id)";
	$sql_concept="select distinct paper.field from paper join paperrelationshipstaff join staff on paper.id=paperrelationshipstaff.paper and paperrelationshipstaff.staff=staff.id where staff.id=$id";
	$sql_co_author="select distinct staff.id, fst_name, lst_name from staff join paperrelationshipstaff on staff.id=paperrelationshipstaff.staff where paperrelationshipstaff.paper in (select paperrelationshipstaff.paper from staff join paperrelationshipstaff on staff.id=paperrelationshipstaff.staff where staff.id=$id)";
	$res_personal=mysqli_query($conn,$sql_personal);
	$res_similar_author=mysqli_query($conn,$sql_similar_author);
	$res_concept=mysqli_query($conn,$sql_concept);
	$res_co_author=mysqli_query($conn,$sql_co_author);
	$res_co_author1=mysqli_query($conn,$sql_co_author);
	$totalnum_similar=mysqli_num_rows($res_similar_author);
	$totalnum_concept=mysqli_num_rows($res_concept);
	$totalnum_co=mysqli_num_rows($res_co_author);
	
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
				<div class="nav_bar">
					<ul>
						<li><a href="co-list.php?id=<?php echo $id ?>" id="onlink">list</a></li>
						<li><a href="co-radial.php?id=<?php echo $id ?>">radial</a></li>
						<li><a href="co-map.php?id=<?php echo $id ?>">map</a></li>
						<li><a href="co-timeline.php?id=<?php echo $id ?>">timeline</a></li>
						<li><a href="co-cluster.php?id=<?php echo $id ?>">cluster</a></li>
					</ul>
				</div>
				<div class="main_container">
					<div class="index">
						<?php
							$personal=mysqli_fetch_assoc($res_personal);
						?>
						<p style="text-decoration: underline; margin-left: 15px;">
							<?php echo"{$personal['fst_name']} {$personal['lst_name']}"?>/ all co-authors/ list/
						</p>
						<p  style="margin-left: 15px;">
							<?php echo"<a href='personal-main.php?id={$id}'>click</a>" ?> to return.
						</p>
																
					</div>
					
					<div class="co" style="width: 99%; margin-left: 0.5%; margin-top: 10px; border: 1px solid #B6B6B6;" >
						<?php 
								$index=0;
								while($co1=mysqli_fetch_assoc($res_co_author1)){
						?>
									<p style="margin-left: 10px;">
										<?php 
											echo "<a href='personal-main.php?id={$co1['id']}'>◈ {$co1['fst_name']} {$co1['lst_name']}</a>"; 
										?>
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
					<h3 id="comming">Comming's <br>Network</h3>
					<p style="font-size:15px;">Click the "See All" links for more information and interactive visualizations!</p>
				</div>
				<hr style="margin: auto;width: 90%;color: #A9A9A9;"></hr>
				<div class="similar">
					<p style="font-size: 16px;"><strong>Similar authors</strong></p>
					<?php 
							$index=0;
							while($similar=mysqli_fetch_assoc($res_similar_author)){
								$index=$index+1;
								if($totalnum_similar>4){
									if($index<5){ 
					?>
										<p style="margin:0 auto;">
						                	<?php echo "<a href='personal-main.php?id={$similar['id']}'>{$similar['fst_name']} {$similar['lst_name']}</a>"; ?>		
 										</p>
 										
					<?php			
									}
								}
								else{
					?>
									<p style="margin:0 auto;">
						                <?php echo "<a href='personal-main.php?id={$similar['id']}'>{$similar['fst_name']} {$similar['lst_name']}</a>"; ?>		
 									</p>
 					<?php		
								}
							} 
					?>
							<p style="text-align: center">
 								<?php echo "<a href='similar-list.php?id={$id}'>(see all {$totalnum_similar} result)</a>"; ?>
 							</p>
				</div>
				<hr style="margin: auto;width: 90%;color: #A9A9A9;"></hr>
				<div class="concept">
					<p style="font-size: 16px;"><strong>Concepts</strong></p>
					<?php 
						$index=0;
						while($concept=mysqli_fetch_assoc($res_concept)){
							$index=$index+1;
							if($totalnum_concept>4){
								if($index<5){ 
					?>
									<p style="margin:0 auto;">
					                	<?php echo "<a href='concept-detail.php?id={$id}&concept={$concept['field']}'>{$concept['field']}</a>"; ?>		
 									</p>
 										
					<?php			
								}
							}
							else{
					?>
								<p style="margin:0 auto;">
							        <?php echo "<a href='concept-detail.php?id={$id}&concept={$concept['field']}'>{$concept['field']}</a>"; ?>		
 								</p>
 					<?php		
							}
						} 
					?>
						<p style="text-align: center">
 							<?php echo "<a href='concept-list.php?id={$id}'>(see all {$totalnum_concept} result)</a>"; ?>
 						</p>
				</div>
				<hr style="margin: auto;width: 90%;color: #A9A9A9;"></hr>
				<div class="co-author">
					<p style="font-size: 16px;"><strong>Co-authors</strong></p>
					<?php 
						$index=0;
						while($co=mysqli_fetch_assoc($res_co_author)){
							$index=$index+1;
							if($totalnum_co>4){
								if($index<5){ 
					?>
									<p style="margin:0 auto;">
					                	<?php echo "<a href='personal-main.php?id={$co['id']}'>{$co['fst_name']} {$co['lst_name']}</a>"; ?>		
 									</p>
 										
					<?php			
								}
							}
							else{
					?>
								<p style="margin:0 auto;">
							        <?php echo "<a href='personal-main.php?id={$co['id']}'>{$co['fst_name']} {$co['lst_name']}</a>"; ?>		
 								</p>
 					<?php		
							}
						} 
					?>
						<p style="text-align: center">
 							<?php echo "<a href='co-list.php?id={$id}'>(see all {$totalnum_co} result)</a>"; ?>
 						</p>
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