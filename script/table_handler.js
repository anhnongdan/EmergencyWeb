var selectedRowRec = [];
var selectedRowPro = [];
	

	
	
	function SelectRec(Rtable){
		//var Rtable = document.getElementsByTagName('table')[1];
		var selectedRow = Rtable.target.parentNode;
		//alert("row id: "+selectedRow.id+"color: "+window.getComputedStyle(selectedRow,null).backgroundColor);
		if(window.getComputedStyle(selectedRow,null).backgroundColor=="rgb(232, 237, 255)"){  //selectedRow.backgroundColor cannot use here
			if(Rtable.ctrlKey){
				dropAll(window.selectedRowPro);
				selectedRow.style.backgroundColor="rgb(208, 218, 253)";
				window.selectedRowRec.push(selectedRow);
			}else{
				dropAll(window.selectedRowRec);
				dropAll(window.selectedRowPro)
				selectedRow.style.backgroundColor="rgb(208, 218, 253)";
				window.selectedRowRec.push(selectedRow);
			}
		}else{
			selectedRow.style.backgroundColor="rgb(232, 237, 255)";
			deleteFromArray(window.selectedRowRec,selectedRow);
		} 
	}
	function SelectPro(Ptable){
		var selectedRow = Ptable.target.parentNode;
		//alert("selected: "+selectedRow.id+" color: "+window.getComputedStyle(selectedRow,null).backgroundColor);
		if(window.getComputedStyle(selectedRow,null).backgroundColor=="rgb(232, 237, 255)"){  //selectedRow.backgroundColor cannot use here
			if(Ptable.ctrlKey){
				dropAll(window.selectedRowRec);
				selectedRow.style.backgroundColor="rgb(208, 218, 253)"; //dark
				window.selectedRowPro.push(selectedRow);
			}else{
				dropAll(window.selectedRowPro);
				dropAll(window.selectedRowRec);
				selectedRow.style.backgroundColor="rgb(208, 218, 253)";
				window.selectedRowPro.push(selectedRow);
			}
		}else{
			selectedRow.style.backgroundColor="rgb(232, 237, 255)";  //light
			deleteFromArray(window.selectedRowRec,selectedRow);
		} 
	}
	
	function RecToPro(){
		for(var i=0;i<window.selectedRowRec.length;i++){
			deleteRowFromTable(0,window.selectedRowRec[i]);
			changeToProcess(window.selectedRowRec[i].id-1); //remember to -1
		}
		appendRowsToTable(1, window.selectedRowRec);
		var details = createListStatistic(1);
		var sta = "Total: "+(details.Im+details.Ex+details.Fu+details.Pa+details.Un)+"<img id=\"SI\" src=\"images/pin/redP.png\" alt=\"Im\">"+details.Im+"</img><img id=\"SI\" src=\"images/pin/yellowP.png\" alt=\"Ex\">"+details.Ex+"</img><img id=\"SI\" src=\"images/pin/blueP.png\" alt=\"Fu\">"+details.Fu+"</img><img id=\"SI\" src=\"images/pin/greenP.png\" alt=\"Ex\">"+details.Pa+"</img><img id=\"SI\" src=\"images/pin/grayP.png\" alt=\"Ex\">"+details.Un +"</img>";		
		document.getElementsByTagName('table')[1].getElementsByTagName('tfoot')[0].getElementsByTagName('tr')[0].getElementsByTagName('td')[0].innerHTML=sta;
		details = createListStatistic(0);
		sta = "Total: "+(details.Im+details.Ex+details.Fu+details.Pa+details.Un)+"<img id=\"SI\" src=\"images/pin/redR.png\" alt=\"Im\">"+details.Im+"</img><img id=\"SI\" src=\"images/pin/yellowR.png\" alt=\"Ex\">"+details.Ex+"</img><img id=\"SI\" src=\"images/pin/blueR.png\" alt=\"Fu\">"+details.Fu+"</img><img id=\"SI\" src=\"images/pin/greenR.png\" alt=\"Ex\">"+details.Pa+"</img><img id=\"SI\" src=\"images/pin/grayR.png\" alt=\"Ex\">"+details.Un +"</img>";		
		document.getElementsByTagName('table')[0].getElementsByTagName('tfoot')[0].getElementsByTagName('tr')[0].getElementsByTagName('td')[0].innerHTML=sta;
		
		var listcontent = getPList();
		$.get('writePList.php', {'listcontent': listcontent} ,
			function(){});
	}
	
	function ProToRec(){
		for(var i=0;i<window.selectedRowPro.length;i++){
			deleteRowFromTable(1,window.selectedRowPro[i]);
			changeToRecommend(window.selectedRowPro[i].id-1);
		}
		appendRowsToTable(0, window.selectedRowPro);
		var details = createListStatistic(0);
		var sta = "Total: "+(details.Im+details.Ex+details.Fu+details.Pa+details.Un)+"<img id=\"SI\" src=\"images/pin/redP.png\" alt=\"Im\">"+details.Im+"</img><img id=\"SI\" src=\"images/pin/yellowP.png\" alt=\"Ex\">"+details.Ex+"</img><img id=\"SI\" src=\"images/pin/blueP.png\" alt=\"Fu\">"+details.Fu+"</img><img id=\"SI\" src=\"images/pin/greenP.png\" alt=\"Ex\">"+details.Pa+"</img><img id=\"SI\" src=\"images/pin/grayP.png\" alt=\"Ex\">"+details.Un +"</img>";		
		document.getElementsByTagName('table')[0].getElementsByTagName('tfoot')[0].getElementsByTagName('tr')[0].getElementsByTagName('td')[0].innerHTML=sta;
		details = createListStatistic(1);
		sta = "Total: "+(details.Im+details.Ex+details.Fu+details.Pa+details.Un)+"<img id=\"SI\" src=\"images/pin/redR.png\" alt=\"Im\">"+details.Im+"</img><img id=\"SI\" src=\"images/pin/yellowR.png\" alt=\"Ex\">"+details.Ex+"</img><img id=\"SI\" src=\"images/pin/blueR.png\" alt=\"Fu\">"+details.Fu+"</img><img id=\"SI\" src=\"images/pin/greenR.png\" alt=\"Ex\">"+details.Pa+"</img><img id=\"SI\" src=\"images/pin/grayR.png\" alt=\"Ex\">"+details.Un +"</img>";		
		document.getElementsByTagName('table')[1].getElementsByTagName('tfoot')[0].getElementsByTagName('tr')[0].getElementsByTagName('td')[0].innerHTML=sta;
		
		var listcontent = getPList();
		$.get('writePList.php', {'listcontent': listcontent} ,
			function(){});
	}
	
	function deleteFromArray(Array,row){
		for(var i=0;i<Array.length;i++){
			if(Array[i].id == row.id){
				Array.splice(i,1);
			}
		}	
	}
	function dropAll(Array){
		for(var i=0;i<Array.length;i++){
			Array[i].style.backgroundColor="rgb(232, 237, 255)";
		}
		Array = [];
	}	
	
	function deleteRowFromTable(tablenumber,row){
		var table = document.getElementsByTagName('table')[tablenumber];
		var tbody = table.getElementsByTagName('tbody')[0];
		var rows = tbody.getElementsByTagName('tr');
		for(var i=0;i<rows.length;i++){
			if(rows[i].id==row.id){
				tbody.removeChild(rows[i]);
			}
		}
	}
	function appendRowsToTable(tablenumber, Rows){
		var table = document.getElementsByTagName('table')[tablenumber];
		var tbody = table.getElementsByTagName('tbody')[0];
		for(var i=0;i<Rows.length;i++){
			Rows[i].getElementsByTagName('td')[0].innerHTML = '<img src=\"' +window.Pin_Array[Rows[i].id-1].marker.getIcon()+ '\" alt=\"Error\"></img>';
			
			tbody.appendChild(Rows[i]);
		}
		Rows = [];
	}
	
	function updateDarkRows(){
		//alert("selected on Rec: "+window.selectedRowRec.length);
		var table = document.getElementsByTagName('table')[0]; //rec table
		var tbody = table.getElementsByTagName('tbody')[0];
		var rows = tbody.getElementsByTagName('tr');
		for(var i=0;i<window.selectedRowRec.length;i++){
			for(var j=0;j<rows.length;j++){
				if(window.selectedRowRec[i].id==rows[j].id){
					rows[j].style.backgroundColor="rgb(208, 218, 253)";
				}
			}
		}
	}
	
	
	function createListStatistic(tableNum){
		var Im=0;
		var Ex=0;
		var Fu=0;
		var Pa=0;
		var Un=0;
		
			var table = document.getElementsByTagName('table')[tableNum]; //rec table
			var tbody = table.getElementsByTagName('tbody')[0];
			var rows = tbody.getElementsByTagName('tr');
			for(var i=0;i<rows.length;i++){
				var ur = window.Pin_Array[rows[i].id-1].Message_details.urgency;
				switch(ur){
					case "Immediate":
						Im++;;
						break;
					case "Expected":
						Ex++;
						break;
					case "Future":
						Fu++;
						break;
					case "Past":
						Pa++;
						break;
					default:
						Un++;
				}
			}
		return{Im: Im,Ex: Ex,Fu: Fu,Pa: Pa,Un: Un};			
	}
	
	
	
	
	/*
    function SelectRow(tblTable)
    {
        var SelectedRow = tblTable.srcElement.parentNode;
        
        //this condition checks for the header row
        if((SelectedRow.id!="tblLeftTr1"))
        {
            if(SelectedRow.style.backgroundColor == "#ffeeff")  //is selected ?
            {
                SelectedRow.style.backgroundColor = "white";    //deselect
            }
            else
            {
                SelectedRow.style.backgroundColor = "#ffeeff";  //select
            }   
        }
    }

    //function to select rows from the Right Table (only one row can be selected here)
    function SelectRowRight(tblTable)
    {
        var SelectedRow = tblTable.srcElement.parentNode;
        
        //this condition checks for the header row
        if((SelectedRow.id!="tblRightTr1") )
        {
            if(SelectedRow.style.backgroundColor == "#ffeeff")  //is selected ?
            {
                SelectedRow.style.backgroundColor = "white";    //deselect
            }
            else
            {
                SelectedRow.style.backgroundColor = "#ffeeff";  //select
            }   
        }
        
        var i=0;
        var RightTable = document.getElementById("tblRight");
        
        //this loop prevents from selecting multiple rows
        for(i=1; i<RightTable.rows.length; i++)
        {
            if(RightTable.rows[i].id == SelectedRow.id)
            {
            }
            else
            {
                RightTable.rows[i].style.backgroundColor = "white";
            }
        }
    }
   
   
    //function to add selected rows from Left Table to Right Table
    function AddRows()
    {
        var LeftTable = document.getElementById("tblLeft");    
        var RightTable = document.getElementById("tblRight");    
        var i=0;
        var j=0;
        var RowPresent = 0; //this variable checks if a row is already added to the Right Table
        
        for(i=0; i<LeftTable.rows.length; i++)
        {
            if(LeftTable.rows[i].style.backgroundColor == "#ffeeff")
            {
                for(j=0; j<RightTable.rows.length; j++)
                {
                    if(RightTable.rows[j].id == ("RightRow"+i))
                    {
                        RowPresent=1;
                    }
                }
                
                //this code adds the selected rows to Right Table if not already added
                if(RowPresent==0)   
                {
                    RightRow=RightTable.insertRow();
                    RightRow.id = "RightRow"+i;
                    RightCell1=RightRow.insertCell();
                    RightCell1.align = "left";
                    RightCell2 = RightRow.insertCell();
                    RightCell2.align = "left";
                    
                    RightCell1.innerHTML = LeftTable.rows[i].childNodes[0].innerHTML;
                    RightCell2.innerHTML = LeftTable.rows[i].childNodes[1].innerHTML;
                }
            }
        }
    }

    //function to remove selected rows from the Right Table
    function RemoveRows()
    {
        var RightTable = document.getElementById("tblRight");    
        var i=0;
    
        for(i=0; i<RightTable.rows.length; i++)
        {
            if(RightTable.rows[i].style.backgroundColor == "#ffeeff")   //is selected ?
            {
                DeleteRow(RightTable.rows[i]);
            }
        }
    }
    
    //function to delete a row from Right Table
    function DeleteRow(RowToDelete)
    {
        RowToDelete.parentNode.removeChild(RowToDelete);
        RemoveRows();
    }

    //function to select a row from Right Table to move up
    function MoveUp()
    {
        var RightTable = document.getElementById("tblRight");
        var i=0;
    
        for(i=2; i<RightTable.rows.length; i++)
        {
            if(RightTable.rows[i].style.backgroundColor == "#ffeeff")   //is selected ?
            {
                SendUp(RightTable.rows[i],RightTable.rows[i-1]);
            }
        }
    }
    
    //function to move up the selected row
    function SendUp(CurrentRow,PreviousRow)
    {
        PreviousRow.parentNode.insertBefore(CurrentRow,PreviousRow);
    }

    //function to select a row from Right Table to move down
    function MoveDown()
    {
        var RightTable = document.getElementById("tblRight");
        var i=0;
        var RowToMove=0;
        var PreviousRow;
        var CurrentRow;
        
        for(i=1; i<RightTable.rows.length-1; i++)
        {
            if(RightTable.rows[i].style.backgroundColor == "#ffeeff")
            {
                RightTable.rows[i];
                
                RowToMove = i;
                
                //appends the selected row to the end of the Right Table
                RightTable.rows[i].parentNode.appendChild(RightTable.rows[i]);
                
                //this code moves the appended row up till it reaches 
                //to one position less than its original position
                for(i=RightTable.rows.length-1; i>RowToMove+1; i--)
                {
                    CurrentRow = RightTable.rows[i];
                    PreviousRow = RightTable.rows[i-1];
                    
                    SendUp(CurrentRow, PreviousRow);
                }
            }
        }        
    }    
	*/
