<!doctype html>
<html>
<head>
<link rel="stylesheet" href="index.css" />
<meta charset="utf-8">
<title>welcome page</title>
<script type = "text/javascript" src = "jquery-1.11.2.min.js"></script> 
<script src="pass_variables.js" type="text/javascript"></script>
</head>

<body>
	<div id="header-wrapper">
		<a href="http://www.unimelb.edu.au/"><img id="unimelb" src="unimelb1.jpg"></a>
		<div style="width: 50%; height: 5px; margin-left: 30%;"></div>
		<h2 style="margin-left: 38%;">Unimelb Academic Profile</h2>
	</div>
	<div id="content">
		<div class="menu">
			<div class="menu-header"></div>
				<ul>
					<li><a href="index.php">Find people</a></li>
					<li><a href="index-concept.php">Find concept</a></li>
				</ul>
			<div class="menu-bottom"></div>
		</div>
		<div class="main-wrapper">
			<div class="main-header"></div>
			<p style="margin-left: 15px; font-size: 20px; color: #0C304A; font-weight: 600;">♚Search</p>
			<div class="nav_bar">
				<ul>
					<li><a href="index.php" id="onlink">Find people</a></li>
					<li><a href="index-concept.php">Find concept</a></li>				
				</ul>
			</div>
			<div class="main_container">
				<div class="search-box">
					<h3 style="color: #0C304A">Search people by keywords:</h3>
					<div class="keywords">
						
						<label style="margin-left: 93px; margin-top: 10px;">Keywords</label>
						<input style="margin-left: 5px; margin-top: 10px; height: 20px;" type="text" id="keyword"  placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
						<input type="submit" id="keywordSubmit" value="Search" onClick="keyword()" style="margin-left: 170px; margin-top: 10px;" />
					</div>
					<h3 style="color: #0C304A">Search people by names:</h3>
					<div class="name">
						
						<label style="margin-left: 90px; margin-top: 10px;">First name</label><input style="margin-left: 5px; margin-top: 10px; height: 20px;" type="text" id="first_name" placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
						<label style="margin-left: 91px; margin-top: 10px;">Last name</label><input style="margin-left: 5px; margin-top: 10px; height: 20px;" type="text" id="last_name" placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
						<label style="margin-left: 30px; margin-top: 10px;">School/Department</label><input style="margin-left: 5px; margin-top: 10px; height: 20px;" type="text" id="school" placeholder="" maxlength="25" autocomplete="off" onMouseDown="" onBlur="" /><br>
						<input type="submit" id="nameSubmit" value="Search" onClick="names()" style="margin-left: 170px; margin-top: 10px;"/>
					</div>
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
