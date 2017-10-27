function find_people(){
	var count=0;
	var keyword=document.getElementById("keyword").value;
	var lst_name=document.getElementById("last_name").value;
	var school=document.getElementById("school").value;
	var url = "result.php?page=1&type=people&keyword="+keyword+"&last_name="+lst_name+"&school="+school;
	if(keyword!=''||lst_name!=''||school!=''){
		window.location.href = url;
	}	
}
function keyword(){
	var keyword=document.getElementById("keyword").value;
	var url = "result.php?page=1&type=keyword&keyword="+keyword;
	if(keyword!=''){
		window.location.href = url;
	}
}


function names(){
	var fst_name=document.getElementById("first_name").value;
	var lst_name=document.getElementById("last_name").value;
	var school=document.getElementById("school").value;
	var url = "result.php?page=1&type=name&first_name="+fst_name+"&last_name="
					+lst_name+"&school="+school;
	if(fst_name!=''||lst_name!=''||school!=''){
		window.location.href = url;
	}	
}
function concept(){
	var keyword=document.getElementById("keyword").value;
	var url = "concept-result.php?page=1&type=concept&keyword="+keyword;
	if(keyword!=''){
		window.location.href = url;
	}
}

function GetRequest() {
	var url = location.search;
	if (url.indexOf("?") != -1) {    
      var str = url.substr(1); //从第一个字符开始 因为第0个是?号 获取所有除问号的所有符串
      strs = str.split("&");   //用等号进行分隔 （因为知道只有一个参数 所以直接用等号进分隔 如果有多个参数 要用&号分隔 再用等号进行分隔）
	  var variables = strs[1].split("=");
       //get request type
	  var type = variables[1];
	  if (type=="people"){
		  criteria1.innerHTML=strs[2].split("=")[1];
		  criteria2.innerHTML=strs[3].split("=")[1];
		  criteria3.innerHTML=strs[4].split("=")[1];
	  }
	  else if (type=="keyword"){
		  criteria1.innerHTML=strs[2].split("=")[1];

		  
	  }
	  else if (type=="name"){
		  criteria1.innerHTML=strs[2].split("=")[1];
		  criteria2.innerHTML=strs[3].split("=")[1];
		  criteria3.innerHTML=strs[4].split("=")[1];
	  }
	  else if (type=="concept"){
		  criteria1.innerHTML=strs[2].split("=")[1];
	  }
	}
}





