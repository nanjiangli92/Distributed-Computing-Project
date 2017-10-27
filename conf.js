var labelType, useGradients, nativeTextSupport, animate, rgraph, sign;
var newPalette = new Array("#B5B5B5", "CD1215", "CCDD11", "2C3960", "2C3960");
var newFont = new Array("#000000","#CD1215","#999","#666", "#333","#2C3960");


(function() {
  var ua = navigator.userAgent,
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
  labelType = (!nativeCanvasSupport)? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(!nativeCanvasSupport);
})();
function radialgraph(json){
		//document.getElementById("constrain").innerHTML='';
		rgraph = new $jit.RGraph({
		
        injectInto: 'constrain',
		
        'width': document.getElementById("constrain").clientWidth,
		'height': document.getElementById("constrain").clientHeight,
		
        
				background: {	},

        Navigation: {
          enable: false,
          panning: false,
        },
        Node: {
            color: '#E6BBC1',
            'overridable': true,
            'strokeStyle': '#555',
            dim: 0
        },
        
        Edge: {
          'overridable': true,
        },
        
        Label: {
					family: 'Verdana',
					'overridable': true,
				},
        
        
				onCreateLabel: function(domElement, node){
						domElement.innerHTML = node.name;
						domElement.onclick = function(){rgraph.onClick(node.id);
						};
				},
					
				onBeforePlotLine: function(adj){
				adj.data.$lineWidth = 6/(adj.nodeFrom._depth+1);//调节连线粗细
				if (adj.nodeFrom._depth == 0 ){
					adj.data.$color = "#B3B3B3";
				} else if(adj.nodeFrom._depth == 1){
					adj.data.$color = "#B3B3B3";
				} else if(adj.nodeFrom._depth == 2){
					adj.data.$color = "#B3B3B3";
				} else{
					adj.data.$color = "#B3B3B3";
				}
          
        },
				duration:750,
        fps: 20, //帧数
        interpolation: 'polar',
        levelDistance: 100, //调节间隔

        onPlaceLabel: function(domElement, node){
            var style = domElement.style;
            style.display = '';
            style.cursor = 'pointer';
			
            if (node._depth == 0) {
                style.fontSize = "1.1em";
				style.fontWeight = 700;
                style.color = "#000000";                
				node.data.$dim = 11; //dim is size of spot;
                node.data.$color = "#AC1B30";
            } else if(node._depth == 1){
                style.fontSize = "1.0em";
                 style.color = "#000000";                
                node.data.$dim = 9;
				node.data.$color = "#AC1B30";
            } else if(node._depth == 2){
                style.fontSize = "0.8em";
                 style.color = "#000000";    
                node.data.$dim = 7;;
				node.data.$color = "#AC1B30";
            } else if(node._depth == 3){
                style.fontSize = "1em";
                 style.color = "#000000";          
                node.data.$dim = 4;
                node.data.$color = newPalette[3];
            }else {
                style.fontSize = "0.8em";
                style.color = newFont[4];          
                node.data.$dim = 4;
                node.data.$color = newPalette[4];
            }

            var left = parseInt(style.left);
            var w = domElement.offsetWidth;
            style.left = (left - w / 2) + 'px';
        }
    });
    
    rgraph.loadJSON(json);
    
    rgraph.graph.eachNode(function(n) {
      var pos = n.getPos();
      pos.setc(0, 0);
    });
    rgraph.compute('end');
    rgraph.fx.animate({
      modes:['polar'],
      duration: 1200
    });
        
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
/*
function init(id){
	var XMLHttp;
	var url="radial-person.php?id="+id;
	
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
			
			var name=obj[0].fst_name+" "+obj[0].lst_name;
			
			show(id,name);
		}
	}
	XMLHttp.open("GET",url,true)
	XMLHttp.send();
}
function show(main_id,main_name){
	
	var XMLHttp;
	var url="radial-query.php?id="+main_id;
	
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
			
			var data;
			var json=XMLHttp.responseText;
			//var main_children=[];
			main_children=[];
			var obj=JSON.parse(json);
			var length=count(obj);
			for(var i=0;i<length;i++){
				main_children[i]={id:"null",name:"null",children:[]};
			}
			for(var d=0;d<length;d++){
				var children;
				var id=obj[d].id;
				var name=obj[d].fst_name+" "+obj[d].lst_name;
				main_children[d].id=id;
				subChildren(id,d);
				main_children[d].name=name;
				
				
				
				
			}
			
			data={id:main_id,
				  name:main_name,			  
				  children:main_children};
			
			radialgraph(data);
		}
	}
	XMLHttp.open("GET",url,true);
	XMLHttp.send();
}
function subChildren(id,index){
	
	var XMLHttp;
	var url="radial-sub-query.php?id="+id;
	
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
			var sub_children=[];
			var data;
			var json=XMLHttp.responseText;
			var obj=JSON.parse(json);
			var length=count(obj);
			
			for(var i=0;i<length;i++){
				sub_children[i]={id:"null",name:"null",children:[]};
			}
			for(var d=0;d<length;d++){
				var children;
				var id=obj[d].id;
				var name=obj[d].fst_name+" "+obj[d].lst_name;
				sub_children[d].id=id;
				sub_children[d].name=name;
				sub_children[d].children=[];
				//alert(sub_children.length);
			}
			main_children[index].children=sub_children;
		}
	}
	XMLHttp.open("GET",url,true);
	XMLHttp.send();
}*/













