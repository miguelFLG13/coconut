function confirm_action(confirmed, url){
	if(confirm('¿Estas seguro de eliminar ' + confirmed + '?')){ 
		location.href = url; 
	} 
}
function displayAlert(){
	return(window.confirm("¿Está seguro de guardar los cambios?"));
}
