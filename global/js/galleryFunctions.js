/**
 * Function to change the layer for upload images in the admin
 */

function changeUpload(layer){
	
	if(layer){
		document.getElementById('gallery').style.display = 'none';
		document.getElementById('uploadImage').style.display = 'inline';
	}else{
		location.reload()
	}
	
}

function addGalleryImage(type, id, url){
	
	if(type == '1'){
		if($('#image'+id).is(':checked'))
			window.opener.document.getElementById("galleryImages").innerHTML += "<div id='gallery" + id + "'><img src='" + url + "global/img/gallery/" + id + "' style='max-height: 100px; max-width: 100px'/><input type='hidden' name='gallery[]' value='" + id + "' /><a href='javascript:document.getElementById(\"gallery" + id + "\").remove();'><img id='delete' src='" + url + "global/img/delete.png'/></a></div>";
		else
			window.opener.document.getElementById("gallery" + id).remove();
	}else{
		window.opener.document.getElementById("featuredImage").innerHTML = "<div id='featuredImageSelected'><img src='" + url + "global/img/gallery/" + id + "' style='max-height: 100px; max-width: 100px'/><input type='hidden' name='featuredImageSelected' value='" + id + "'/><a href='javascript:document.getElementById(\"featuredImageSelected\").remove();'><img id='delete' src='" + url + "global/img/delete.png'/></a></div>";
	}
    
}
