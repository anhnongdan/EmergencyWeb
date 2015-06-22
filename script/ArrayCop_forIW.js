function copyParentPin_Array(){
	for(var i=0;i<opener.window.Pin_Array.length;i++){
		if(window.Pin_Array[i].Message_details==null){
			continue;
		}
		if(window.Pin_Array[i].Message_details.userID!=opener.window.Pin_Array[i].Message_details.userID){
			alert("Error on copying Array");
		}else{
			window.Pin_Array[i].wait = opener.window.Pin_Array[i].wait;
			window.Pin_Array[i].recommend = opener.window.Pin_Array[i].recommend
		}
	}
}
