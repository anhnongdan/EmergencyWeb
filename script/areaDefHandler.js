function AreaDefinition(name, center_lat, center_long, lat_min, lat_max, long_min, long_max){
	this.name = name;
	this.center_lat = center_lat;
	this.center_long = center_long;
	this.lat_min = lat_min;
	this.lat_max = lat_max;
	this.long_min = long_min;
	this.long_max = long_max;
}

			//return a String
			function whereVictim(latitude,longitude){
				//alert("lat: "+latitude+"  long: "+longitude);
				for(var i=0;i<window.AreasDef.length;i++){
					var a = window.AreasDef[i];
					//alert("lat min: "+a.lat_min+"lat_max: "+a.lat_max);
					if(latitude>=parseFloat(a.lat_min) && latitude<=parseFloat(a.lat_max) && longitude>=parseFloat(a.long_min) && longitude<=parseFloat(a.long_max))
						return a.name;
				}
				if(i==window.AreasDef.length)
					return 'Unknown';
			}
			function createAreaDropList(){
				var e = document.getElementById('popup_button');
				var dlist = e.getElementsByTagName('select')[0];
				for(var i=0;i<window.AreasDef.length;i++){
					var op = document.createElement('option');
					op.setAttribute('value',i);
					op.innerHTML = window.AreasDef[i].name;
					dlist.appendChild(op);
				}
			}

