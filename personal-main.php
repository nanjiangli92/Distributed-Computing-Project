<!docfield html>
<html>
<head>
<link rel="stylesheet" href="main.css" />
<script field = "text/javascript" src = "jquery-1.11.2.min.js"></script>
<script src="pass_variables.js" field="text/javascript"></script>
<meta charset="utf-8">
<title>personal</title>

<style>
#map {
	height: 600px;
	width: 800px;
	}
</style>
<?php
	$conn=mysqli_connect('localhost','root','','projecttest') or die("failed to connect");
	$id=$_GET["id"];
	$sql_brief="select * from staff join department on staff.department=department.id where staff.id=$id";
	$sql_paper="select author,title,publishedtime,citedcount,paper.source as source,url from paper join paperrelationshipstaff join sourceurl on paper.id=paperrelationshipstaff.paper and sourceurl.source=paper.source where paperrelationshipstaff.staff=$id order by citedcount desc";
	$sql_similar_author="select distinct staff.id,fst_name,lst_name from staff join paperrelationshipstaff join paper on staff.id=paperrelationshipstaff.staff and paperrelationshipstaff.paper=paper.id where paper.field in (select distinct paper.field from staff join paperrelationshipstaff join paper on staff.id=paperrelationshipstaff.staff and paperrelationshipstaff.paper=paper.id where staff.id=$id)";
	$sql_concept="select distinct paper.field from paper join paperrelationshipstaff join staff on paper.id=paperrelationshipstaff.paper and paperrelationshipstaff.staff=staff.id where staff.id=$id";
	$sql_co_author="select distinct staff.id, fst_name, lst_name from staff join paperrelationshipstaff on staff.id=paperrelationshipstaff.staff where paperrelationshipstaff.paper in (select paperrelationshipstaff.paper from staff join paperrelationshipstaff on staff.id=paperrelationshipstaff.staff where staff.id=$id)";
	$res_brief=mysqli_query($conn,$sql_brief);
	$res_paper=mysqli_query($conn,$sql_paper);
	$res_similar_author=mysqli_query($conn,$sql_similar_author);
	$res_concept=mysqli_query($conn,$sql_concept);
	$res_co_author=mysqli_query($conn,$sql_co_author);
	$totalnum_similar=mysqli_num_rows($res_similar_author);
	$totalnum_concept=mysqli_num_rows($res_concept);
	$totalnum_co=mysqli_num_rows($res_co_author);
	$totalnum_paper=mysqli_num_rows($res_paper);
	
	mysqli_close($conn);
	$brief=mysqli_fetch_assoc($res_brief);
	
	
	
	
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
				<input field="text" id="keyword" placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
				<label>Last name</label><br>
				<input field="text" id="last_name" placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
				<label>School/Department</label><br>
				<input field="text" id="school" placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
				<input field="submit" id="findpeople" value="Find People" onClick="find_people()"/><br>	
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
					
				<div class="main_container">
					<div class="brief" style="width: 97%; margin-left: 1.5%; margin-top: 10px; border: 1px solid #848484; color: #0C304A;">
						<p style="margin-left: 10px; margin-right:10px;font-size: 17px;">Name:  <?php echo "{$brief['fst_name']} {$brief['lst_name']}"?></p>
						<p style="margin-left: 10px; margin-right:10px;font-size: 17px;">Title:  <?php echo $brief['title']?></p>
						<p style="margin-left: 10px; margin-right:10px;font-size: 17px;">Position:  <?php echo $brief['position']?></p>
						<p style="margin-left: 10px; margin-right:10px;font-size: 17px;">Institution/Department:  <?php echo $brief['departmentname']?></p>
						<?php 
							if(!empty($brief['address1'])&&!empty($brief['address2'])&&!empty($brief['address3'])){ 
						?>
							<p style="margin-left: 10px; margin-right:10px;font-size: 17px;">Address:  <?php echo "{$brief['address1']}, {$brief['address2']}, {$brief['address3']}" ?></p>
						<?php 
							}
						?>
						<?php 
							if(!empty($brief['phone'])){ 
						?>
							<p style="margin-left: 10px; margin-right:10px;font-size: 17px;">Phone:  <?php echo $brief['phone']?></p>
						<?php 
							}
						?>
						
						<?php 
							if(!empty($brief['email'])){ 
						?>
							<p style="margin-left: 10px; margin-right:10px;font-size: 17px;">e-mail: <?php echo $brief['email']?></p>
						<?php 
							}
						?>
						
						<?php 
							if(!empty($brief['info'])){ 
						?>
							<p style="margin-left: 10px; margin-right:10px;font-size: 16px;"><?php echo $brief['info']?></p>
						<?php 
							}
						?>					
					</div>
					
					<div class="paper" style="border: 1px solid #848484; width: 97%; margin-left: 1.5%; margin-top: 10px;">
						<div class="header" style="height: 30px; background: #F4F4F4; border-bottom: 1px solid #848484; border-top: 1px solid #F4F4F4;">
							<span style="margin-left: 15px; color: #0C304A; font-size: 18px; margin-top: 3px;">selected publicants: </span>
							<span style="font-weight: 600;"><?php echo $totalnum_paper ?></span>
						</div>
						<div class="main-paper" style="width: 97%; margin-left: 1.5%;">
						<?php
							$index=0;
							while($paper=mysqli_fetch_assoc($res_paper)){
								$index+=1;
						?>
								<span style="margin-left: 5px; font-size: 14px; font-size: 15px;">
 									<?php echo "{$index}. {$paper['author']} {$paper['title']} {$paper['publishedtime']} " ?>
 								</span>
 								<span style="font-weight: 600;">
 									<?php echo "  [cited: {$paper['citedcount']}]" ?>
 								</span>
 								<br>
 								<span style="margin-left: 5px; font-size: 14px;">viewed in:</span>
 								<span>
 									<?php echo "<a href='{$paper['url']}'> {$paper['source']}</a>"; ?>
 								</span>
 								<hr style="margin-top: 5px;width: 97%;color:#B6B6B6"></hr>
 								
						<?php 
							} 
						?>
						</div>
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
					                	<?php echo "<a href='concept-detail.php?concept={$concept['field']}'>{$concept['field']}</a>"; ?>		
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
						<p style="text-align: center;">
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