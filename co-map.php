<!docfield html>
<html>
<head>
<link rel="stylesheet" href="main.css" />
<script field = "text/javascript" src = "jquery-1.11.2.min.js"></script>
<script src="pass_variables.js" field="text/javascript"></script>
<script src="ajex.js" field="text/javascript"></script>
<meta charset="utf-8">
<title>co-map</title>
<style>
	#map {
	margin-top: 5px;
	height: 480px;
	width: 98%;
	margin-left: 1%;
	border: 1px solid #B6B6B6;
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
				<div class="nav_bar">
					<ul>
						<li><a href="co-list.php?id=<?php echo $id ?>">list</a></li>
						<li><a href="co-radial.php?id=<?php echo $id ?>">radial</a></li>
						<li><a href="co-map.php?id=<?php echo $id ?>" id="onlink">map</a></li>
						<li><a href="co-timeline.php?id=<?php echo $id ?>">timeline</a></li>
						<li><a href="co-cluster.php?id=<?php echo $id ?>">cluster</a></li>
					</ul>
				</div>
				<div class="main_container">
					<div class="index">
						<?php
							$personal=mysqli_fetch_assoc($res_personal);
						?>
						<p style="text-decoration: underline; margin-left: 10px;">
							<?php echo"{$personal['fst_name']} {$personal['lst_name']}"?>/ all co-authors/ map/
						</p>
						<p  style="margin-left: 10px;">
							<?php echo"<a href='personal-main.php?id={$id}'>click</a>" ?> to return.
						</p>
						<p id="as"></p>							
					</div>
					
					<div id="map">
						<script>
							function mapInit(){
								//document.getElementById("as").innerHTML="1";
								var id=<?php echo $id?>;
								var XMLHttp;
								var url="map-query.php?id="+id;
	
								if (window.XMLHttpRequest)
  								{// code for IE7+, Firefox, Chrome, Opera, Safari
  									XMLHttp=new XMLHttpRequest();
  								}
								else
  								{// code for IE6, IE5
  									XMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
  								}
								XMLHttp.onreadystatechange=function(){
									if(XMLHttp.readyState==4||XMLHttp.status==200){
										var json=XMLHttp.responseText;
										obj=JSON.parse(json);		
										var length=count(obj);
										
										var self=new Object();
										for(var s=0;s<length;s++){
											if(id==obj[s].id){
												self={lat:parseFloat(obj[s].latitude),lng:parseFloat(obj[s].longtitude)};
											}
										}
										
										var locArray=[];
										var info=[];
										for(var l=0;l<length;l++){
											var latitude=parseFloat(obj[l].latitude);
											var longtitude=parseFloat(obj[l].longtitude);
											var latlng={lat:latitude,lng:longtitude};
											locArray.push(latlng);
											var fst_name=obj[l].fst_name;
											var lst_name=obj[l].lst_name;
											var position=obj[l].position;
											var personal={fst:fst_name,lst:lst_name,pos:position};
											info.push(personal);
										}
										
										var center_location={lat:-37.797044,lng:144.964738};
										var options={
											zoom: 14,
											center: center_location
										};
										var map= new google.maps.Map(document.getElementById("map"),options);
										var pathArray=[];
										var markerArray=[];
										
										for(var i=0;i<locArray.length;i++){
											var location_pair=[];
											location_pair.push(self);
											location_pair.push(locArray[i]);
											pathArray.push(location_pair);

											var loc=locArray[i];
											var con=info[i].fst+" "+info[i].lst+", "+info[i].pos;										
											var marker_obj={location:loc,content:con};							
											markerArray.push(marker_obj);
											
										}
										
										for(var p=0;p<pathArray.length;p++){
											addPath(pathArray[p]);
										}
										
										for(var m=0;m<markerArray.length;m++){
											
											addMarker(markerArray[m]);
										}
										function addPath(location_pair){
											var flightPath = new google.maps.Polyline({
												path: location_pair,
												geodesic: true,
												strokeColor: '#FF0000',
												strokeOpacity: 1.0,
												strokeWeight: 2
											});
											flightPath.setMap(map);
										}
										function addMarker(markerArray){
											var marker=new google.maps.Marker({
												position: markerArray.location,
												map: map
											});
											
											marker.addListener('click',function(){
												
												//infowindow.close();
												var infowindow=new google.maps.InfoWindow({
													content:markerArray.content
												});
												infowindow.open(map,marker);
											});
										}
										

									}
							}
							XMLHttp.open("GET",url,true);
							XMLHttp.send();
						}
						</script>
						<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpISKuu6Mk2LIXoNElnAwbeQZQ6sSAeEs&callback=mapInit"></script>
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