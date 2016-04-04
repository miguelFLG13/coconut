/**
 * Function to change the layer for idiom in the admin
 */

function changeInputs(layer, idioms){
	for(i = 1; i <= idioms; i++)
		document.getElementById('textContent'+i).style.display = 'none';
	document.getElementById('textContent'+layer).style.display = 'inline';
	
}
