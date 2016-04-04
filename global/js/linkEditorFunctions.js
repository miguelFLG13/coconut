/**
 * Function to change the layer for type of link in the admin
 */

function changeSourceLink(type){
	if(type){
		document.getElementById("externalSource").style.display = 'inline';
		document.getElementById("internalSource").style.display = 'none';
	}else{
		document.getElementById("externalSource").style.display = 'none';
		document.getElementById("internalSource").style.display = 'inline';
	}
}
