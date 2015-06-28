var DataArray = [];
var plot2;


function  firstGraphCreate(five, ten, fifteen, twenty, twentyfive, thirty, thirtyfive, forty){
	
	window.DataArray.push([5,parseInt(five)]);
	window.DataArray.push([10,parseInt(ten)]);
	window.DataArray.push([15,parseInt(fifteen)]);
	window.DataArray.push([20,parseInt(twenty)]);
	window.DataArray.push([25,parseInt(twentyfive)]);
	window.DataArray.push([30, parseInt(thirty)]);
	window.DataArray.push([35, thirtyfive]);
	window.DataArray.push([40, forty]);
	
	//alert("May be it went wrong");
	
	window.plot2 = $.jqplot ('graph', [window.DataArray], {
		title: 'Arrived Messages within each 5 minutes',
		axes: {
         xaxis: {
          label: 'Time (minutes)',       
        },
        yaxis: {
          label: 'Message',
          min: 0,
          pad: 2,
          tickInterval: 1,
          labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
        }
      }
	  });
}

function continuousData(five, ten, fifteen, twenty, twentyfive, thirty, thirtyfive, forty){
	if(window.DataArray.length==0){
		alert("This function should not be used now!");
	}else{
		window.DataArray = [];	
		window.DataArray.push([5,parseInt(five)]);
		window.DataArray.push([10,parseInt(ten)]);
		window.DataArray.push([15,parseInt(fifteen)]);
		window.DataArray.push([20,parseInt(twenty)]);
		window.DataArray.push([25,parseInt(twentyfive)]);
		window.DataArray.push([30, parseInt(thirty)]);
		window.DataArray.push([35, thirtyfive]);
		window.DataArray.push([40, forty]);
		
		window.plot2.series[0].data = window.DataArray;
		window.plot2.replot();
		
		/*
		var plot2 = $.jqplot ('graph', [window.DataArray], {
		title: 'Plot With Options',
		axes: {
         xaxis: {
          label: 'Time (minutes)',       
        },
        yaxis: {
          label: 'Message',
          min: 0,
          tickInterval: 1,
          labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
        }
      }
	  }); */		
	}
}
