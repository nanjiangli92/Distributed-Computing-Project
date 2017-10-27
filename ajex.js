
function showDetail(source_url,index){
	var XMLHttp;
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		XMLHttp=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		XMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	var href_url=document.getElementById('href'+index).href;
    var str = href_url.substr(1); 
    var strs = str.split("=");  
	var id=strs[1];
	var url=source_url+"?id="+id;
	//detail2.innerHTML=url;
	XMLHttp.onreadystatechange=function(){
		if(XMLHttp.readyState==4||XMLHttp.status==200){
		var json=XMLHttp.responseText;
		obj=JSON.parse(json);
		document.getElementById("detail1").innerHTML="name";
		document.getElementById("detail2").innerHTML=obj[0].fst_name+' '+obj[0].lst_name;
		document.getElementById("detail3").innerHTML="title";
		document.getElementById("detail4").innerHTML=obj[0].title;
		document.getElementById("detail5").innerHTML="position";
		document.getElementById("detail6").innerHTML=obj[0].position;
		document.getElementById("detail7").innerHTML="institution";
		document.getElementById("detail8").innerHTML=obj[0].departmentname;
		
		}
	}
	XMLHttp.open("GET",url,true);
	XMLHttp.send();
}


function clean(){
	detail1.innerHTML='';
	detail2.innerHTML='';
	detail3.innerHTML='';
	detail4.innerHTML='';
	detail5.innerHTML='';
	detail6.innerHTML='';
}
function count(obj){
    var objType = typeof obj;
    if(objType == "string"){
        return obj.length;
    }else if(objType == "object"){
        var objLen = 0;
        for(var i in obj){
            objLen++;
        }
        return objLen;
    }
    return false;
}

function co_timeline_chart(id,obj,length) {
	var categories_arr=[];
	var obj1=[];
	var index=0;
	for(var k=0;k<length;k++){
		if(obj[k].id!=id){
			obj1[index]=obj[k];
			index++;
		}
	}
	for(var i=0;i<length-1;i++){
		categories_arr[i]=obj1[i].fst_name+" "+obj1[i].lst_name;
	}
    var publishedtime_arr=new Array();
	for(var j=0;j<length-1;j++){
		publishedtime_arr[j]=new Array();
		for(var k=0;k<1;k++){}
			publishedtime_arr[j][k]=0;
	}
	
	for(var l=0;l<length-1;l++){
		publishedtime_arr[l][0]=parseInt((obj1[l].earliest).substr(0, 4));
		publishedtime_arr[l][1]=parseInt((obj1[l].latest).substr(0, 4));
	}
    $('#co-timeline').highcharts({
        chart: {
            type: 'columnrange',
            inverted: true
        },
		title: {
            text: 'Co-author timeline'
        },
        subtitle: {
            text: 'changes by year'
        },
        xAxis: {
            categories: categories_arr
        },
        yAxis: {
			allowDecimals: false,
            title: {
				text: 'year'
            }
        },
        tooltip: {
            valueSuffix: ''
        },
        plotOptions: {
            columnrange: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return this.y;
                    }
                }
            }	
        },
        legend: {
            enabled: false
        },
        series: [{
            name: 'publishedtime',
            data: publishedtime_arr
        	}]
    });
}
function co_timeline(id){
	var XMLHttp;
	var url="co-timeline-query.php?id="+id;
	
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
			co_timeline_chart(id,obj,length);
		}
	}
	XMLHttp.open("GET",url,true);
	XMLHttp.send();
}
function concept_timeline_chart(obj,length) {
	var categories_arr=[];
	for(var i=0;i<length;i++){
		categories_arr[i]=obj[i].field;
	}
    var publishedtime_arr=new Array();
	for(var j=0;j<length;j++){
		publishedtime_arr[j]=new Array();
		for(var k=0;k<1;k++){
			publishedtime_arr[j][k]=0;
		}		
	}
	
	for(var l=0;l<length;l++){
		publishedtime_arr[l][0]=parseInt((obj[l].earliest).substr(0, 4));
		publishedtime_arr[l][1]=parseInt((obj[l].latest).substr(0, 4));
	}
    $('#concept-timeline').highcharts({
        chart: {
            type: 'columnrange',
            inverted: true
        },
		title: {
            text: 'concept timeline'
        },
        subtitle: {
            text: 'changes by year'
        },
        xAxis: {
            categories: categories_arr
        },
        yAxis: {
			allowDecimals: false,
            title: {
				text: 'year',
				
            }
        },
        tooltip: {
            valueSuffix: ''
        },
        plotOptions: {
            columnrange: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return this.y;
                    }
                }
            }	
        },
        legend: {
            enabled: false
        },
        series: [{
            name: 'concept_year',
            data: publishedtime_arr
        	}]
    });
}
function concept_timeline(id){
	var XMLHttp;
	var url="concept-timeline-query.php?id="+id;
	
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
			concept_timeline_chart(obj,length);
		}
	}
	XMLHttp.open("GET",url,true);
	XMLHttp.send();
}
function map_initial(obj,length){
	
	var locArray=new Array();
	//document.getElementById("try").innerHTML=locArray[1].lat;
	for(var j=0;j<length;j++){
		locArray[j]={lat:0,lng:0};	
	}
	
	for(var l=0;l<length;l++){
		locArray[l].lat=obj[l].latitude;
		locArray[l].lng=obj[l].longtitude;
	}
	
	var location={lat:-37.817044,lng:144.974738};
	var options={
		zoom: 10,
		center: location};
	
	var map= new google.maps.Map(document.getElementById("map"),options);
	document.getElementById("try").innerHTML=locArray[1].lat;
	for(var i=0;i<=locArray.length;i++){
		var marker = new google.maps.Marker({
			position: locArray[i],
			map: map
		});
	}						
}
function map(id){
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
			
			map_initial(obj,length);
			
		}
	}
	XMLHttp.open("GET",url,true)
	XMLHttp.send();
}
function concept_detail_timeline_chart(obj,length) {
	
	var categories_arr=[];
	for(var i=0;i<length;i++){
		categories_arr[i]=obj[i].fst_name+" "+obj[i].lst_name;
	}
    var publishedtime_arr=new Array();
	for(var j=0;j<length;j++){
		publishedtime_arr[j]=new Array();
		for(var k=0;k<1;k++){
			publishedtime_arr[j][k]=0;
		}		
	}
	
	for(var l=0;l<length;l++){
		publishedtime_arr[l][0]=parseInt((obj[l].earliest).substr(0, 4));
		publishedtime_arr[l][1]=parseInt((obj[l].latest).substr(0, 4));
	}
    $('#concept-timeline').highcharts({
        chart: {
            type: 'columnrange',
            inverted: true
        },
		title: {
            text: 'concept timeline'
        },
        subtitle: {
            text: 'changes by year'
        },
        xAxis: {
            categories: categories_arr
        },
        yAxis: {
			allowDecimals: false,
            title: {
				text: 'year',
				
            }
        },
        tooltip: {
            valueSuffix: ''
        },
        plotOptions: {
            columnrange: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        return this.y;
                    }
                }
            }	
        },
        legend: {
            enabled: false
        },
        series: [{
            name: 'concept_year',
            data: publishedtime_arr
        	}]
    });
}
function concept_detail_timeline(concept){
	var XMLHttp;
	var url="concept-detail-timeline-query.php?concept="+concept;
	
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
			concept_detail_timeline_chart(obj,length);
		}
	}
	XMLHttp.open("GET",url,true);
	XMLHttp.send();
}
