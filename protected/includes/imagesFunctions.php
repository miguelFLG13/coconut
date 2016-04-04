<?php
/**
 * Coconut Functions for image changes
 * @date 2015-06-26
 * @author Miguel Jimenez Garcia
 *
 */

function create_thumbnail($image_id){
	
	$thumb = new thumbnail("global/img/gallery/".$image_id);
	$thumb->size_height(350);
	$thumb->jpeg_quality(80);
	$thumb->save("global/img/thumbnails/".$image_id);
	
	/*list($old_width, $old_height) = getimagesize("global/img/gallery/".$image_id);
	
	$type = exif_imagetype("global/img/gallery/".$image_id);
	
	if($old_width > $old_height){
		$width = 250;
		$height = round(($width * $old_height / $old_width), 1, PHP_ROUND_HALF_UP);
	}else{
		$height = 250;
		$width = round(($old_width * $height / $old_height), 1, PHP_ROUND_HALF_UP);
	}
	
	$height = $old_height * 0.5;
	$width = $old_width * 0.5;
	
	if($type == 2)
		$image = imagecreatefromjpeg("global/img/gallery/".$image_id);
	else if($type == 3)
		$image = imagecreatefrompng("global/img/gallery/".$image_id);
	else if($type == 1)
		$image = imagecreatefromgif("global/img/gallery/".$image_id);
	else
		return;
	
	$tmp_image = imagecreatetruecolor($width, $height);

	imagecopyresampled($tmp_image, $image, 0, 0, 0, 0, $width, $height, $old_height, $old_width);
 
	imagedestroy($image);

	imagejpeg($tmp_image, "global/img/thumbnails/".$image_id, 80);*/
}

function save_slider_images($images){
	Doo::db()->query("UPDATE images SET slider = 0;");
	Doo::loadModel('Image');
	
	$image = New Image;
	$i = 0;
	
	foreach($images as $one_image){
			
		if($i > 4)
			break;
		
		$image->id = $one_image;
		$image->slider = 1;
		$image->update();
		
		$i++;
	}
}

class thumbnail
{
	var $img;

	function thumbnail($imgfile)
	{
		$type = exif_imagetype($imgfile);
		if($type == 2){
			//JPEG
			$this->img["format"]="JPEG";
			$this->img["src"] = ImageCreateFromJPEG ($imgfile);
		} elseif ($type == 3) {
			//PNG
			$this->img["format"]="PNG";
			$this->img["src"] = ImageCreateFromPNG ($imgfile);
		} elseif ($type == 1) {
			//GIF
			$this->img["format"]="GIF";
			$this->img["src"] = ImageCreateFromGIF ($imgfile);
		} else {
			//DEFAULT
			echo "Not Supported File";
			exit();
		}
		@$this->img["lebar"] = imagesx($this->img["src"]);
		@$this->img["tinggi"] = imagesy($this->img["src"]);
		//default quality jpeg
		$this->img["quality"]=75;
	}

	function size_height($size=100)
	{
		//height
    	$this->img["tinggi_thumb"]=$size;
    	@$this->img["lebar_thumb"] = ($this->img["tinggi_thumb"]/$this->img["tinggi"])*$this->img["lebar"];
	}

	function size_width($size=100)
	{
		//width
		$this->img["lebar_thumb"]=$size;
    	@$this->img["tinggi_thumb"] = ($this->img["lebar_thumb"]/$this->img["lebar"])*$this->img["tinggi"];
	}

	function size_auto($size=100)
	{
		//size
		if ($this->img["lebar"]>=$this->img["tinggi"]) {
    		$this->img["lebar_thumb"]=$size;
    		@$this->img["tinggi_thumb"] = ($this->img["lebar_thumb"]/$this->img["lebar"])*$this->img["tinggi"];
		} else {
	    	$this->img["tinggi_thumb"]=$size;
    		@$this->img["lebar_thumb"] = ($this->img["tinggi_thumb"]/$this->img["tinggi"])*$this->img["lebar"];
 		}
	}

	function jpeg_quality($quality=75)
	{
		//jpeg quality
		$this->img["quality"]=$quality;
	}

	function show()
	{
		//show thumb
		@Header("Content-Type: image/".$this->img["format"]);

		/* change ImageCreateTrueColor to ImageCreate if your GD not supported ImageCreateTrueColor function*/
		$this->img["des"] = ImageCreateTrueColor($this->img["lebar_thumb"],$this->img["tinggi_thumb"]);
    		@imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["lebar_thumb"], $this->img["tinggi_thumb"], $this->img["lebar"], $this->img["tinggi"]);

		if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
			//JPEG
			imageJPEG($this->img["des"],"",$this->img["quality"]);
		} elseif ($this->img["format"]=="PNG") {
			//PNG
			imagePNG($this->img["des"]);
		} elseif ($this->img["format"]=="GIF") {
			//GIF
			imageGIF($this->img["des"]);
		} elseif ($this->img["format"]=="WBMP") {
			//WBMP
			imageWBMP($this->img["des"]);
		}
	}

	function save($save="")
	{
		//save thumb
		if (empty($save)) $save=strtolower("./thumb.".$this->img["format"]);
		/* change ImageCreateTrueColor to ImageCreate if your GD not supported ImageCreateTrueColor function*/
		$this->img["des"] = ImageCreateTrueColor($this->img["lebar_thumb"],$this->img["tinggi_thumb"]);
    		@imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["lebar_thumb"], $this->img["tinggi_thumb"], $this->img["lebar"], $this->img["tinggi"]);

		if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
			//JPEG
			imageJPEG($this->img["des"],"$save",$this->img["quality"]);
		} elseif ($this->img["format"]=="PNG") {
			//PNG
			imagePNG($this->img["des"],"$save");
		} elseif ($this->img["format"]=="GIF") {
			//GIF
			imageGIF($this->img["des"],"$save");
		} elseif ($this->img["format"]=="WBMP") {
			//WBMP
			imageWBMP($this->img["des"],"$save");
		}
	}
}
?>
