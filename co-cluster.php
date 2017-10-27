<!docfield html>
<html>
<head>
<link rel="stylesheet" href="main.css" />
<script field = "text/javascript" src = "jquery-1.11.2.min.js"></script>
<script src="pass_variables.js" field="text/javascript"></script>
<script src="ajex.js" field="text/javascript"></script>
<script src="https://d3js.org/d3.v4.min.js"></script>

<meta charset="utf-8">
<title>co-cluster</title>
<style>
#map {
	height: 600px;
	width: 800px;
	}
.links line {
  stroke: #999;
  stroke-opacity: 0.8;
}

.nodes circle {
  stroke: #fff;
  stroke-width: 2px;
}
</style>
<?php
	function inarray($value,$field,$array){
		$len=sizeof($array);
		for($i=0;$i<$len;$i++){
			if($array[$i][$field]==$value){return(true);}
		}
		return(false);
	}
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
	$totalnum_similar=mysqli_num_rows($res_similar_author);
	$totalnum_concept=mysqli_num_rows($res_concept);
	$totalnum_co=mysqli_num_rows($res_co_author);
	
	$sql_cluster_personal="select fst_name,lst_name,staff.id as id, count(staff1) as staff_count from staff join staffrelationshipstaff on staff.id=staff1 where staff1=$id";
	$sql_cluster="select fst_name,lst_name,staff.id as id, count(staff1) as staff_count from staff join staffrelationshipstaff on staffrelationshipstaff.staff2=staff.id where staffrelationshipstaff.staff2 in (select distinct staff2 from staffrelationshipstaff where staff1=$id) group by staff.id";
	
	$res_cluster_personal=mysqli_query($conn,$sql_cluster_personal);
	$res_cluster=mysqli_query($conn,$sql_cluster);
	$res_cluster1=mysqli_query($conn,$sql_cluster);
	$parent_id=0;
	$parent_name="null";
	while($cluster_personal=mysqli_fetch_assoc($res_cluster_personal)){
		$parent_id=$cluster_personal['id'];
		$parent_name="{$cluster_personal['fst_name']} {$cluster_personal['lst_name']}";
		$parent_count=$cluster_personal['staff_count'];
	}
	$data=array();
	$nodes=array();
	$links=array();
	$nodes[0]=array(
						'id' => $parent_name,
						'group' => 0
					);
	$index=1;
	while($cluster=mysqli_fetch_assoc($res_cluster)){
		$children_id=$cluster['id'];
		$children_name="{$cluster['fst_name']} {$cluster['lst_name']}";
		$children_count=$cluster['staff_count'];

		$nodes[]=array(
						'id' => $children_name,
						'group' => $index
					);
		$index++;
		$links[]=array(
						'source' => $parent_name,
						'target' =>	$children_name,
						'value' => $children_count
					);
	}
	$nodes1=$nodes;
	$links1=$links;
	while($cluster1=mysqli_fetch_assoc($res_cluster1)){

		$children_id=$cluster1['id'];
		$children_name="{$cluster1['fst_name']} {$cluster1['lst_name']}";
		
		$sql_cluster_children="(select CONCAT(fst_name,' ', lst_name) as author, count(staff1) as count 
		from staff join staffrelationshipstaff on staff.id=staff2 where staff2 in (select distinct staff2 
		from staffrelationshipstaff where staff1=$children_id) group by author) union 
		(select author, count(staff) as count from staffrelationshipauthor where
		staff=$children_id group by author limit 10)";
		$res_cluster_children=mysqli_query($conn,$sql_cluster_children);
		if(mysqli_num_rows($res_cluster_children)>0){
			while($cluster_children=mysqli_fetch_assoc($res_cluster_children)){
				$sub_name=$cluster_children['author'];
				$sub_count=$cluster_children['count'];
				if(!inarray($sub_name,'id',$nodes1)){
					$sub_index=array_search($children_name, array_column($nodes1, 'id'));
					$nodes1[]=array(
								'id' => $sub_name,
								'group' => $nodes1[$sub_index]['group']
								);
				}
					$links1[]=array(
									'source' => $children_name,
									'target' =>	$sub_name,
									'value' => $sub_count
								);
			}
		}	
	}
	
	$data=array(
					'nodes' =>$nodes1,
					'links' =>$links1
				);
	$json=json_encode($data);
	$myfile=fopen("json.json","w") or die("unable to open");
	fwrite($myfile,$json);
	fclose($myfile);
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
						<li><a href="co-map.php?id=<?php echo $id ?>">map</a></li>
						<li><a href="co-timeline.php?id=<?php echo $id ?>">timeline</a></li>
						<li><a href="co-cluster.php?id=<?php echo $id ?>" id="onlink">cluster</a></li>
					</ul>
				</div>
				<div class="main_container">
					<div class="index">
						<?php 
							$personal=mysqli_fetch_assoc($res_personal);
						?>
						<p style="text-decoration: underline; margin-left: 10px;">
							<?php echo"{$personal['fst_name']} {$personal['lst_name']}"?>/ all co-authors/ cluster/
						</p>
						<p  style="margin-left: 10px;">
							<?php echo"<a href='personal-main.php?id={$id}'>click</a>" ?> to return.
						</p>
						
																
					</div>
					
					<div class="co-cluster">
						<svg width="600" height="450"></svg>
						<script>

						var svg = d3.select("svg"),
							width = +svg.attr("width"),
							height = +svg.attr("height");

						var color = d3.scaleOrdinal(d3.schemeCategory20);

						var simulation = d3.forceSimulation()
							.force("link", d3.forceLink().id(function(d) { return d.id; }))
							.force("charge", d3.forceManyBody())
							.force("center", d3.forceCenter(width / 2, height / 2));

						d3.json("json.json", function(graph) {
						  //if (error) throw error;

						  var link = svg.append("g")
							  .attr("class", "links")
							.selectAll("line")
							.data(graph.links)
							.enter().append("line")
							  .attr("stroke-width", function(d) { return Math.sqrt(d.value); });

						  var node = svg.append("g")
							  .attr("class", "nodes")
							.selectAll("circle")
							.data(graph.nodes)
							.enter().append("circle")
							  .attr("r", 7)
							  .attr("fill", function(d) { return color(d.group); })
							  .call(d3.drag()
								  .on("start", dragstarted)
								  .on("drag", dragged)
								  .on("end", dragended));

						  node.append("title")
							  .text(function(d) { return d.id; });

						  simulation
							  .nodes(graph.nodes)
							  .on("tick", ticked);

						  simulation.force("link")
							  .links(graph.links);

						  function ticked() {
							link
								.attr("x1", function(d) { return d.source.x; })
								.attr("y1", function(d) { return d.source.y; })
								.attr("x2", function(d) { return d.target.x; })
								.attr("y2", function(d) { return d.target.y; });

							node
								.attr("cx", function(d) { return d.x; })
								.attr("cy", function(d) { return d.y; });
						  }
						});

						function dragstarted(d) {
						  if (!d3.event.active) simulation.alphaTarget(0.3).restart();
						  d.fx = d.x;
						  d.fy = d.y;
						}

						function dragged(d) {
						  d.fx = d3.event.x;
						  d.fy = d3.event.y;
						}

						function dragended(d) {
						  if (!d3.event.active) simulation.alphaTarget(0);
						  d.fx = null;
						  d.fy = null;
						}

						</script>
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