var one_day = 1000*3600*24;
var one_hour = 1000*3600;
var one_min = 1000*60;	
//all of global variables
var map;
var yellow_pin = "images/pin/yellowR.png";
var gray_pin = "images/pin/grayR.png";
var red_pin = "images/pin/redR.png";
var green_pin = "images/pin/greenR.png";
var blue_pin = "images/pin/blueR.png";
var blueP_pin = "images/pin/blueP.png";
var yellowP_pin = "images/pin/yellowP.png";
var grayP_pin = "images/pin/grayP.png";
var redP_pin = "images/pin/redP.png";
var greenP_pin = "images/pin/greenP.png";

var User_Array=[];    //user array with ascending userID
var Pin_Array=[]; 	//pin Array (user with sent message)
var Message_Array=[]; //arrived message array
var currentPin = -1;
var priorityArray = [];
var idLeft = 0;
var idRight = 0;	
var currentInfoWin=null;	
var markersArray=[];	
var AreasDef = [];
		
function pinObj(User_details, Message_details, map, show){

    this.marker = new google.maps.Marker({
        map: map,
        icon: gray_pin,
        //animation: google.maps.Animation.DROP,
        //position: new google.maps.LatLng(Message_details.latitude, Message_details.longitude),
        draggable: false,
    });

	this.infoWin = new ManageWindow();
    this.User_details = User_details;
	this.Message_details = Message_details;	
	this.show = show;
	this.wait = null;
	this.recommend = 1;
	this.click = 0;
	
    //get details of Pin
    this.get_position_details = function () 
    {
		return {longitude:this.longitude, latitude:this.latitude, elevation:this.elevation, address:this.address, areaDesc: this.areaDesc, IPAddress:this.IPAddress};				
	};
    this.get_status_details = function()
    {
		return {category:this.category, event:this.event, urgency:this.urgency, severity:this.severity, certainty:this.certainty, messType:this.messType, messStatus:this.messStatus};
	};
	this.get_time_details = function()
	{
		return {sentDate:this.sentDate, addToDbTime:this.addToDbTime};
	};
	this.get_reference_details = function()
	{
		return {reference:this.reference, note:this.note, solved:this.solved, language:this.language};		
	};
	this.showOnMap = function(){
		if(this.show==1 && this.marker.getMap()==null){  //avoiding double marker set
 			this.marker.setMap(window.map);
			//alert("This is inside show Map function "+this.User_details.userID);
		}
	};
	this.hideOnMap = function(){
		this.marker.setMap(null);
	};

	this.set_Icon = function(){
		if(this.recommend==1){
		switch(this.Message_details.urgency){
			case "Immediate":
				this.marker.setIcon(red_pin);
				break;
			case "Expected":
				this.marker.setIcon(yellow_pin);
				break;
			case "Future":
				this.marker.setIcon(blue_pin);
				break;
			case "Past":
				this.marker.setIcon(green_pin);
				break;
			default:
				this.marker.setIcon(gray_pin);
		}					
		}else{
		switch(this.Message_details.urgency){
			case "Immediate":
				this.marker.setIcon(redP_pin);
				break;
			case "Expected":
				this.marker.setIcon(yellowP_pin);
				break;
			case "Future":
				this.marker.setIcon(blueP_pin);
				break;
			case "Past":
				this.marker.setIcon(greenP_pin);
				break;
			default:
				this.marker.setIcon(grayP_pin);
		}	
		}
	};
	
	
	// initial detials for Pin if message is not null, put after defining set_Icon method
	if(Message_details!=null){
		this.marker.setPosition(new google.maps.LatLng(Message_details.latitude,Message_details.longitude));
		this.set_Icon();
		makeManageWin(this);
		addEvents(this, window.map); 
		if(this.wait==null){
			//alert(Message_details.sentDate);
			var firsttime = Message_details.sentDate.split(/[- :]/);
			//alert(firsttime[0]+firsttime[1]+firsttime[2]+firsttime[3]+firsttime[4]+firsttime[5]);
			this.wait = new Date(firsttime[0],firsttime[1]-1,firsttime[2],firsttime[3],firsttime[4],firsttime[5]);
			//alert(this.wait);
		} 
	}
	if(this.show==1){
		window.markersArray.push(this.marker);
	}
    this.updateMessage = function (Message){
		this.Message_details = Message;
		this.marker.setPosition(new google.maps.LatLng(Message.latitude, Message.longitude));
		this.set_Icon();
		makeManageWin(this);
		addEvents(this, window.map);  
	};
}//end pinObj
 
 				
				//manageWindow
				function ManageWindow(){
					this.tab = new google.maps.InfoWindow({});
					//this.deleteB = document.createElement("div");
					//this.deleteB.innerHTML = "<div id =\"deleteB\"><button type=\"button\" id=\"deletePin\"> Delete </button></div>";
					//this.editB = document.createElement("div");
					//this.editB.innerHTML = "<div id = \"editB\" ><button type = \"button\" id=\"edit\"> Edit </button></div>";
					//this.saveB = document.createElement("div");
					//this.saveB.innerHTML = "<div id = \"saveB\" > <input type = \"submit\" id=\"save\" /></div>";
				}
				
				
				//details of pin
				function User_details(userID,name, gender, birthday, address, IPAddress, phoneNum, email, bloodType, medicalCond){
					this.name = name;
					this.userID = userID;
					this.gender = gender;
					this.birthday  = birthday;
					this.bloodType = bloodType;
					this.phoneNum = phoneNum;
					this.email = email;
					this.medicalCond = medicalCond;
					this.address = address;
					this.IPAddress = IPAddress;
				}
				//details of Message 
				function Message_details(userID, sentDate,messageType,messageStatus,scope,restriction,address,reference,note,clientMessageID,addtodbtime,  language,event, category,urgency,severity,certainty,instruction,  areaDesc,longitude,latitude,elevation,IPAddress,changed){ 
					this.userID = userID;
					this.sentDate = sentDate;
					this.longitude = longitude;
					this.latitude = latitude;
					this.elevation = elevation;
					this.addtodbtime = addtodbtime;
					this.category = category;
					this.event = event;
					this.urgency = urgency;
					this.severity = severity;   //10
					this.certainty = certainty; 
					this.messType = messageType;
					this.messStatus = messageStatus;
					this.scope = scope;
					this.restriction = restriction; 
					this.address = address;
					this.reference = reference;
					this.note = note;
					this.clientMessID = clientMessageID;
					this.language = language;      //20
					this.areaDesc = areaDesc;  
					this.IPAddress = IPAddress;	
					this.instruction = instruction;		
					this.changed = changed;
				}
				
				
				/* deprecated
				function set_pin_icon(pin,urgency){
					switch(urgency){
						case "Immediate":
							pin.marker.setIcon(red_pin);
							break;
						case "Expected":
							pin.marker.setIcon(yellow_pin);
							break;
						case "Future":
							pin.marker.setIcon(yellow_pin);
							break;
						case "Past":
							pin.marker.setIcon(green_pin);
							break;
						default:
							pin.marker.setIcon(gray_pin);
					}					
				} */
							
				
						
				function addEvents(pin, map){
					
					google.maps.event.addListener(pin.marker,"mouseover",function(){
						pin.infoWin.tab.open(map, pin.marker);		
					});
					google.maps.event.addListener(pin.marker,"mouseout",function(){
						if(pin.click==0)
							pin.infoWin.tab.close();		
					});
					
					google.maps.event.addListener(pin.marker, "click", function(){
						pin.infoWin.tab.open(map, pin.marker);
						showPinDetails(pin);
						pin.click = 1;
						//show_victim_detail();							
					});
					
					google.maps.event.addListener(pin.infoWin.tab, "closeclick",function(){
						pin.click = 0;
					});
					
				} 
				//end of addEvent()
	
	
	
		function setInfoWin(pin){
					var pinDetail = document.createElement("div");

					pinDetail.innerHTML = '<div>' +
					'<p id=\"IWC\"> ID:    ' +
					pin.User_details.userID +
					'</p>' +
					'<p id=\"IWC\"> Name:    ' +
					pin.User_details.name +
					'</p>' +
					'<p id=\"IWC\"> Sent on:    ' +
					pin.Message_details.sentDate +
					'</p>' +
					'<p id=\"IWC\"> Latitude: ' +
					pin.Message_details.latitude +
					'</p>' +
					'<p id=\"IWC\"> Logitude: ' +
					pin.Message_details.longitude +
					' </p>' +
					'</div>';
					return pinDetail;
		}

				
		function addPin(pinObj){
					pinObj.marker.setMap(window.map);
		}
		function makeManageWin(pin){
					var detail = setInfoWin(pin);
					//detail.appendChild(manaWin.deleteB);
					//detail.appendChild(manaWin.editB);
					pin.infoWin.tab.setContent(detail);
		}
		

		function showInfo(pin){
					pin.infoWin.tab.open(window.map, pin.marker);
		}
				
	
					// Deletes all markers in the array by removing references to them
		function deleteOverlays(){
					for (i in markersArray) {
						if (markersArray) {
							markersArray[i].setMap(null);
							markersArray[i] = null;
						}
					}
		}
				
		function dropPin_Array(){
			
		}
				
				
		function setupInfoW(marker){
					var content = document.createElement("div");
					content.innerHTML = '<div' + div +
					'</div>';
					content.appendChild(div);
					return content;
		}
						
				
				
				
	 /*function appearInfo(marker){
	 var infoString =  setupInfoW(marker);
	 infoWindow.setContent(infoString);
	 infoWindow.open(map,marker);
	 }*/
	 

				// Removes the overlays from the map, but keeps them in the array
				function clearOverlay(marker){
					marker.setMap(null);
				}
				
				
				
				// Shows any overlays currently in the array
				function showOverlays(){
					if (markersArray) {
						for (i in markersArray) {
							markersArray[i].setMap(map);
						}
					}
				}
	
				//performance of save button
				function savePerformance(form, details, manaWin){
					details.name = form.PName.value;
					details.position = form.PPosition.value;
					details.created = form.PCreated.value;
					makeManaWin(manaWin, details);
				}
				
				function addUser (User){
					window.User_Array.push(User);
				}
				function addMessage (Message){
					window.Message_Array.push(Message);
				}
				
				function testUserArray(){
					for(var i in window.User_Array){
						alert(window.User_Array[i].userID+"  "+window.User_Array[i].name+ "  "+window.User_Array[i].gender);
					}
					
				}
				function testMessageArray(){
					alert("on Message array there are "+ window.Message_Array.length);
					for(var i in window.Message_Array){
						//alert(window.Message_Array[i].userID+"  "+window.Message_Array[i].latitude+ "  "+window.Message_Array[i].longitude);
					}
				}
				function testPinArray(){
					alert("on Pin array there are "+ window.Pin_Array.length);
					for(var i in window.Pin_Array){
						//alert("In Pin Array "+window.Pin_Array[i].show+"  ");
					}
				}
				/*
				function cmp(){
					var a = "IZU0010".localeCompare("AIZU0010");
					alert("Why does this call cmp function?");		
				}*/
				
 
 //------------------------------
	function initialize(){
		var myLatlng = new google.maps.LatLng(37.524163, 139.93726);
		//var myLatlng = new google.maps.LatLng(37.508292, 139.930158);
		var myOptions = {
	  		zoom: 16,
	  		center: myLatlng,
	  		mapTypeId: google.maps.MapTypeId.ROADMAP,
	  		scrollwheel: false,
	  	};
	  	
	  	window.map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);


		var borderCoordinates = [
			new google.maps.LatLng(37.526128,139.931434),
			new google.maps.LatLng(37.526809,139.934117),
			new google.maps.LatLng(37.527745,139.938258),
			new google.maps.LatLng(37.526536,139.945532),
			new google.maps.LatLng(37.521346,139.944137),
			new google.maps.LatLng(37.521278,139.93137),
			new google.maps.LatLng(37.526128,139.931434)
		];
		var borderPath = new google.maps.Polyline({
			path: borderCoordinates,
			strokeColor: "#191968",
			strokeOpacity: 1.0,
			strokeWeight: 2
		});

		borderPath.setMap(map);
  	
		//var i;
		//for(i=0;i<logArray.length;i++){
		//	var detail = new details("aa", parseFloat(latArray[i]), parseFloat(logArray[i]), "home", "2011/08/01");
		//	var pin = new pinObj(detail,map);
		//	addPin(pin);
		//	window.pinArray.push(pin);
		//}
		
	};
 //---------------------------------
 
	// synchronized message and user
	function createPin (userA,messA){
		var i,j;
		//alert(userA.length +"    "+messA.length);
		if(messA.length==0){
			return;
		}
		

		for(i=0,j=0;i<userA.length && j<messA.length;){
			if(userA[i].userID.localeCompare(messA[j].userID) == 0){
				var check = FindOnPinA(messA[j].userID);
				if(check==-1){
					//alert(messA[j].userID);
					var pin = new pinObj(userA[i],messA[j],window.map,1);
					window.Pin_Array.push(pin);
					//alert(Pin_Array[Pin_Array.length-1].Message_details.userID);
					//var p = determinePoint(messA[j].urgency,messA[j].severity);
					//priorityArray.push(new priority(Pin_Array.length-1,p));
				}else{
					window.Pin_Array[check].updateMessage(messA[j]);
					window.Pin_Array[check].show = 1;
					UpdateProcessList(check); //Remember
				}
				i++;j++;
			}else if(userA[i].userID.localeCompare(messA[j].userID) < 0){
				//alert(userA[i].userID.localeCompare(messA[j].userID));
				for(;userA[i].userID.localeCompare(messA[j].userID) < 0 && i<userA.length;i++){
					var check = FindOnPinA(userA[i].userID);
					if(check==-1){
						var pin = new pinObj(userA[i],null,window.map,0); //user has no message, don't show on map
						window.Pin_Array.push(pin);
					}
					//var p = determinePoint(messA[j].urgency,messA[j].severity);
					//priorityArray.push(new priority(Pin_Array.length-1,p));
				}
			}else{
				for(;userA[i].userID.localeCompare(messA[j].userID)>0 && j<messA.length;j++){
					var check = FindOnPinA(messA[j].userID);
					if(check==-1){
						var pin = new pinObj(null,messA[j],window.map,1);
						window.Pin_Array.push(pin);
						//var p = determinePoint(messA[j].urgency,messA[j].severity);
						//priorityArray.push(new priority(Pin_Array.length-1,p));
					}else{
						window.Pin_Array[check].updateMessage(messA[j]);
						window.Pin_Array[check].show = 1;
						UpdateProcessList(check); //Remember 
					}
				}
			}
		}
		if(i<userA.length && j==messA.length){
			for(;i<userA.length;i++){
				var check = FindOnPinA(userA[i].userID);
				if(check==-1){
					var pin = new pinObj(userA[i],null,window.map,0); 
					window.Pin_Array.push(pin);
				}
				//var p = determinePoint(messA[j].urgency,messA[j].severity);
				//priorityArray.push(new priority(Pin_Array.length-1,p));
			}
		}
		if(i==userA.length && j<messA.length){
			for(;j<messA.length;j++){
				var check = FindOnPinA(messA[j].userID);
					if(check==-1){
						var pin = new pinObj(null,messA[j],window.map,1);
						window.Pin_Array.push(pin);
						//var p = determinePoint(messA[j].urgency,messA[j].severity);
						//priorityArray.push(new priority(Pin_Array.length-1,p));
					}else{
						window.Pin_Array[check].updateMessage(messA[j]);
						window.Pin_Array[check].show = 1;
						UpdateProcessList(check); //remember id on list bigger than index on array 
					}
			}
		}				
		
		
		var mcOptions = {gridSize: 50, maxZoom: 17};
		var cluster=new MarkerClusterer(window.map, window.markersArray, {maxZoom: 19, gridSize: 30});
		//cluster.fitMapToMarkers();		
	}// end of create Pin
	
	
	
	// carefully use this function
	function InitWait(userID, wait){
		var index = FindOnPinA(userID);
		//alert('userID: '+userID+'i=+'+index+' wait:'+wait);
		if(index!=-1){
			var firsttime = wait.split(/[- :]/);
			window.Pin_Array[index].wait = new Date(firsttime[0],firsttime[1]-1,firsttime[2],firsttime[3],firsttime[4],firsttime[5]);
		}
	}
	
	
	function dropArray(array){
		array = null;
		array = [];
	}
	
	function FindOnPinA(userID){
		for(var i=0;i<window.Pin_Array.length;i++){
			if(window.Pin_Array[i].Message_details==null) continue;
			if(window.Pin_Array[i].Message_details.userID==userID) break;
		}
		if(i<window.Pin_Array.length){
			return i;
		}else{
			return -1;
		}
	}
	
	function createList(pinA){
			//make recommend list
			createPriorityArray();
		
			var Rtable = document.getElementsByTagName('table')[0];
			//alert('pinArray has '+pinA.length);
			var tbody = Rtable.getElementsByTagName('tbody')[0];
			tbody.innerHTML = '';	 //temporarily comment out to fix createList when needed
			for(var i=0;i<window.priorityArray.length;i++){
				//alert('pinArray has '+pinA.length);	
				//alert('show = +'+i+'+ '+pinA[i].show);			
				idRight = 0;
				name = (pinA[window.priorityArray[i].index].User_details.name=="")? "Unknown":pinA[window.priorityArray[i].index].User_details.name;
				
				var item = createListItem(pinA[window.priorityArray[i].index].Message_details.urgency, name, pinA[window.priorityArray[i].index].wait, window.priorityArray[i].index);
				idRight = idRight + (window.priorityArray[i].index+1);          //remember that id list element is 1 bigger than array index 
				tbody.appendChild(item);				
			}
			updateDarkRows(); //keep selected row dark after refresh list
			var details = createListStatistic(0);
			sta = "Total: "+(details.Im+details.Ex+details.Fu+details.Pa+details.Un)+"<img id=\"SI\" src=\"images/pin/redR.png\" alt=\"Im\">"+details.Im+"</img><img id=\"SI\" src=\"images/pin/yellowR.png\" alt=\"Ex\">"+details.Ex+"</img><img id=\"SI\" src=\"images/pin/blueR.png\" alt=\"Fu\">"+details.Fu+"</img><img id=\"SI\" src=\"images/pin/greenR.png\" alt=\"Ex\">"+details.Pa+"</img><img id=\"SI\" src=\"images/pin/grayR.png\" alt=\"Ex\">"+details.Un +"</img>";		
			Rtable.getElementsByTagName('tfoot')[0].getElementsByTagName('tr')[0].getElementsByTagName('td')[0].innerHTML=sta;
		}

	//create rows for both tables
	function createListItem(urgency,name,wait,i){
		var item = document.createElement('tr');
		var waitTime = generateWaitTime(wait);
		var un = (name=='')? "Unknown":name;
		var pos =  whereVictim(window.Pin_Array[i].marker.getPosition().lat(),window.Pin_Array[i].marker.getPosition().lng());
		//item.setAttribute('class','sortable-item');
		
		//var nameEle = document.createElement('td');								 
		
		item.setAttribute('id',i+1);    //id in list is 1 bigger than array index
		var urgentIndex;
		switch(urgency){
			case "Immediate":
				urgentIndex = 4;
				break;
			case "Expected":
				urgentIndex = 3;
				break;
			case "Future":
				urgentIndex = 2;
				break;
			case "Past":
				urgentIndex = 1;
				break;
			default:
				urgentIndex = 0;;
		}	
		
		item.setAttribute('onclick','spanMap('+i+')');
		item.innerHTML = '<td class=\"c1\" sorttable_customkey=\"'+urgentIndex+'\"><img src=\"' +window.Pin_Array[i].marker.getIcon()+ '\" alt=\"Error\"></img>' +  
						 '<td class=\"c2\">' + un + '</td>' + 
						 '<td class=\"c3\" sorttable_customkey=\"'+dateFormat(wait,"yyyymmddHHMMss") +'\">' + waitTime +'</td>'+
						 '<td class=\"c4\">' + pos + '</td>';				 						 
		return item;						 
	}
	
	
	
	function changedListItem(id){
		var list = document.getElementById(id);
		var items = list.getElementsByTagName('li');
		var sum=0;
		for(var i=0;i<items.length;i++){
			sum = sum + parseInt(items[i].id);
		}
		var a = sum - window.idLeft;
		window.idLeft = sum;
		//alert('a = '+a);
		return a;
	}
	
	
	
	
	function showPinOnMap(){
		var i;
		for(i=0;i<window.Pin_Array.length;i++){
			window.Pin_Array[i].showOnMap();
		}
	}
	function spanMap(i){
		var pin = window.Pin_Array[i];
		window.map.panTo(new  google.maps.LatLng(pin.Message_details.latitude,pin.Message_details.longitude));
		if(currentInfoWin==null){
			pin.infoWin.tab.open(window.map, pin.marker);
			currentInfoWin = i;
		}else{
			window.Pin_Array[currentInfoWin].infoWin.tab.close();
			pin.infoWin.tab.open(window.map, pin.marker);
			currentInfoWin = i;
		}
		showPinDetails(Pin_Array[i]);
	}
	
	
	// set pin information to table but don't show it
	function showPinDetails(pin){
		if(window.currentPin==-10){
			return;
		}else{
			//var table = document.getElementById('alternatecolor');
			var db = document.getElementById('details-box');
			var table = db.getElementsByTagName('table')[0];
			table.innerHTML = victimDetailsBoxContent(pin);
		}
	}
	function victimDetailsBoxContent(pin){
		var specialMeCond = (pin.User_details.medicalCond=='') ? 'unknown': pin.User_details.medicalCond;
		var instruction = (pin.Message_details.instruction=='') ? 'none':pin.Message_details.instruction;
		var gender = (pin.User_details.gender=='M') ? 'Male':'Female';
		var name = (pin.User_details.name=='') ? 'Unknown Name':pin.User_details.name;
		var severity = (pin.Message_details.severity=='') ? 'unknown':pin.Message_details.severity;
		var category = (pin.Message_details.category=='') ? 'unknown':pin.Message_details.category;
		var waitTime= generateWaitTime(pin.wait);
		var bloodType = (pin.User_details.bloodType=='') ? 'unknow': pin.User_details.bloodType;
		var year = (pin.User_details.birthday=='') ? 'unknown': pin.User_details.birthday.split(/[- :]/)[0];
		year = (year=='0000')? 'unknown':year;
		var areaDesc = (pin.Message_details.areaDesc=='') ? 'no description': pin.Message_details.areaDesc;
		var event = (pin.Message_details.event=='') ? 'unknown': pin.Message_details.event;
		var ss = (pin.recommend==1) ? 'waiting':'processing';
		
		var htmlcont = 	'<tr>'+
							'<th title=\"Urgency of victim\'s situation. (i.e) Immediate, Expected, Future, Past, Unknown\">' +  pin.Message_details.urgency + '</th><th title=\" Time duration in which victim has been waiting (from the first message).\">' + waitTime +'</th>' +
							'</tr>'+
							'<tr>'+
							'<td width="110px"><ul>'+
								'<li>'+name+'</li>'+ 
								'<li>'+bloodType+'</li>'+
								'<li>'+year+'</li>'+
								'<li>'+gender+'</li>'+
							'</ul></td>'+
							'<td width="110px"><ul>'+
								'<li>'+severity+'</li>'+ 
								'<li>'+category+'</li>'+
								'<li>'+pin.Message_details.sentDate+'</li>'+
								'<li>'+ss+'</li>'+
							'</ul></td>'+
							'</tr>'+
							'<tr>'+
								'<td colspan="2"><ul>'+
									'<li>'+areaDesc+'</li>'+
									'<li>'+specialMeCond+'</li>'+
									'<li>'+instruction+'</li>'+
									'<li>'+event+'</li>'+
								'</ul></td>'+
							'</tr>';
		return htmlcont;
						
	}
	
	
	function generateWaitTime(wait){
		var waitTime;
		var difference = new Date() - wait;
		var d = Math.floor(difference/one_day);
		if(d>1){
			waitTime = d+' days '+ Math.floor((difference - d*one_day)/one_hour) + ' hour(s)';				
		}else if(d==1){
			waitTime = 1+' day '+ Math.floor((difference - d*one_day)/one_hour) + ' hour(s)';
		}else{
			var h = Math.floor(difference/one_hour);
			if(h>=1){
				waitTime = h+' h '+  Math.floor((difference - h*one_hour)/one_min) + ' m';
			}else{
				waitTime = Math.floor(difference/one_min)+' minutes';
			}
		}
		return waitTime;		
	}
	
	
	
	
	

	function show_map_explaination(){
		document.getElementById('map_expl').style.display = 'inline';
		document.getElementById('show_expl_link').innerHTML = '<a href=\"javascript:void(0);\"  onclick=\"hide_map_explaination();\" class=\"yl\">Hide map explaination</a>';
	}

	function hide_map_explaination(){
		document.getElementById('map_expl').style.display = 'none';
		document.getElementById('show_expl_link').innerHTML = '<a href=\"javascript:void(0);\"  onclick=\"show_map_explaination();\" class=\"yl\">Show map explaination</a>';	
	}
	function show_victim_detail(){
		document.getElementById('victim_detail_area').style.display = 'block';
		/*
		var listcol = document.getElementById('Two-lists');
		listcol.getElementsByTagName('ul')[0].style.maxHeight = '850px';
		listcol.getElementsByTagName('ul')[1].style.maxHeight = '850px';
		 */
		document.getElementById('victim_detail_link').innerHTML = '<a href=\"javascript:void(0);\"  onclick=\"hide_victim_detail();\" class=\"yl\">Hide victim details</a>';		
	}
	function hide_victim_detail(){
		document.getElementById('victim_detail_area').style.display = 'none';
		/*
		var listcol = document.getElementById('Two-lists');
		listcol.getElementsByTagName('ul')[0].style.maxHeight = '630px';
		listcol.getElementsByTagName('ul')[1].style.maxHeight = '630px';
		*/
		document.getElementById('victim_detail_link').innerHTML = '<a href=\"javascript:void(0);\"  onclick=\"show_victim_detail();\" class=\"yl\">Show victim details</a>';		
	}
	
	
				//alternate row color for table
				function altRows(table){
				if(document.getElementsByTagName){
					var rows = table.getElementsByTagName("tr"); 
					for(i = 0; i < rows.length; i++){          
						if(i % 2 == 0){
							rows[i].className = "evenrowcolor";
						}else{
							rows[i].className = "oddrowcolor";
						}      
					}
				}
			}
			
			//priory Object
			function priority(index,weight) {
				this.index = index;
				this.weight = weight;
			}
			function determinePoint(urgency,severity){
				var ur_p;
				var sev_p;
				switch(urgency){
					case "Immediate":
						ur_p = 20;
						break;
					case "Expected":
						ur_p = 16;
						break;
					case "Future":
						ur_p = 10;
						break;
					case "Past":
					case "Unknown":
						ur_p = 0;
						break;
					default:
						ur_p = -1;
				}
				switch(severity){
					case "Extreme":
						sev_p = 15;
						break;
					case "Severe":
						sev_p = 12;
						break;
					case "Moderate":
						ur_p = 8;
						break;
					case "Minor":
					case "Unknown":
						ur_p = 4;
						break;
					default:
						ur_p = -1;
				}
				return ur_p+sev_p;
			}
			function determineTimePoint(wait){
				var difference = new Date() - wait;
				var tp=0;
				if(difference < 30*one_min){
					tp = 1;
				}else if(difference <one_hour){
					tp = 2;
				}else if(difference < 2*one_hour){
					tp = 3;
				}else if(difference < 5*one_hour){
					tp = 4;
				}else if(difference < 12*one_hour){
					tp = 5;
				}else if(difference < one_day){
					tp = 6;
				}else if (difference < 5*one_day){
					tp =7;
				}else{
					tp = 0;
				}
				return tp;			
			}
			function SortPriorityArray(){
				for(var i=0;i<window.priorityArray.length;i++){
					var imax=i;
					for(var j=i;j<window.priorityArray.length;j++){
						if(window.priorityArray[j].weight>window.priorityArray[imax].weight){
							imax = j;
						}
					}
					
					//swap i and imax
					var temp = window.priorityArray[i];
					window.priorityArray[i] = window.priorityArray[imax];
					window.priorityArray[imax] = temp;
				}
			}
			function createPriorityArray (){
				window.priorityArray = [];
				for(var i=0;i<window.Pin_Array.length;i++){
					if(window.Pin_Array[i].show*window.Pin_Array[i].recommend==1){
						var p1 = determinePoint(window.Pin_Array[i].Message_details.urgency,window.Pin_Array[i].Message_details.severity);
						var p2 = determineTimePoint(window.Pin_Array[i].wait);
						window.priorityArray.push(new priority(i,p1+p2));
					}
				}
				SortPriorityArray();
			}
			function testPriorityArray(){
				for(var i=0;i<window.priorityArray.length;i++){
					alert('point of +'+i+'+ '+priorityArray[i].weight);
				}
			}
			
			function changeToProcess(id){
				//alert(window.Pin_Array[id].Message_details.userID+'  '+window.Pin_Array[id].User_details.name);
				window.Pin_Array[id].recommend = 0;
				window.Pin_Array[id].set_Icon();
			}
			function changeToRecommend(id){
				window.Pin_Array[id].recommend = 1;
				window.Pin_Array[id].set_Icon();
			}
			
			// update an item on process list (using for pin change on process list)
			function UpdateProcessList(index){
				var Ptable = document.getElementsByTagName('table')[1];
				//alert('pinArray has '+pinA.length);
				var tbody = Ptable.getElementsByTagName('tbody')[0];
				var rows = tbody.getElementsByTagName('tr');
				//alert('update +'+index+'+'+window.Pin_Array[index].User_details.name);	
				//alert('e.length +'+e.length);				
				for(var i=0;i<e.length;i++){
					if(rows[i].id==(index+1)){
						//alert('this id is: ' +e[i].id+'and urgency+ '+ window.Pin_Array[index].Message_details.urgency);
						var waitTime = generateWaitTime(window.Pin_Array[index].wait);
						var name = (window.Pin_Array[index].User_details.name=='') ? 'unknown':window.Pin_Array[index].User_details.name;
						var pos =  whereVictim(window.Pin_Array[index].marker.getPosition().lat,window.Pin_Array[index].marker.getPosition().lng);
						rows[i].innerHTML = '';
						rows[i].innerHTML = '<td>' + name + '</td>'+
								'<td>' + waitTime +'</td>'+
								'<td>' + pos +'</td>';
					}					
				}
			} 
			//creating process table use after creating Pin_Array
			function initRP(userID){
				var check = FindOnPinA(userID);
				window.Pin_Array[check].recommend = 0;
				window.Pin_Array[check].set_Icon();
				addProcessList(check);
			}
			
			//this function was deprecated 
			/*
			function createProcessList(){
				var list = document.getElementById('listcolumn left');
				for(var i=0;i<window.Pin_Array.length;i++){
					if(window.Pin_Array[i].show==1 && window.Pin_Array[i].recommend == 0){
						var item = createListItem(window.Pin_Array[i].Message_details.urgency, window.Pin_Array[i].User_details.name, window.Pin_Array[i].wait, i);
						idLeft = idLeft + i +1;      //remember that id list element is 1 bigger than array index 
						list.appendChild(item);	
					}				
				}
			}*/
			
			
			function addProcessList(index){
				var Rtable = document.getElementsByTagName('table')[1];
				//alert('Rtable id = '+Rtable.id);
				var tbody = Rtable.getElementsByTagName('tbody')[0];
				var name = (window.Pin_Array[index].User_details.name=="")? "Unknown":window.Pin_Array[index].User_details.name;				
				var item = createListItem(window.Pin_Array[index].Message_details.urgency, name, window.Pin_Array[index].wait, index);
				idLeft = idLeft + index +1;          //remember that id list element is 1 bigger than array index 
				tbody.appendChild(item);
				
				var details = createListStatistic(1);
				var sta = "Total: "+(details.Im+details.Ex+details.Fu+details.Pa+details.Un)+"<img id=\"SI\" src=\"images/pin/redP.png\" alt=\"Im\">"+details.Im+"</img><img id=\"SI\" src=\"images/pin/yellowP.png\" alt=\"Ex\">"+details.Ex+"</img><img id=\"SI\" src=\"images/pin/blueP.png\" alt=\"Fu\">"+details.Fu+"</img><img id=\"SI\" src=\"images/pin/greenP.png\" alt=\"Ex\">"+details.Pa+"</img><img id=\"SI\" src=\"images/pin/grayP.png\" alt=\"Ex\">"+details.Un +"</img>";		
				Rtable.getElementsByTagName('tfoot')[0].getElementsByTagName('tr')[0].getElementsByTagName('td')[0].innerHTML=sta;				
			}
			
			function getPList(){
				var listcontent="";
				var list = document.getElementsByTagName('table')[1];
				var body = list.getElementsByTagName('tbody')[0];
				var e = body.getElementsByTagName('tr');
				for(var i=0;i<e.length;i++){
					listcontent = listcontent +"-"+ window.Pin_Array[parseInt(e[i].id)-1].Message_details.userID;  //remember bigger :((
				}
				return listcontent;			
			}

			function comeBackMap(){
				window.map.setZoom(16);
			}
						
