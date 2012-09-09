<?php 
/**
 * @package     
 * @author      
 * @contributor
 * @copyright   2010 rue des ecoles
 * @licence
 */

class tools {

	// Quality is a number between 0 (best compression) and 100 (best quality)
	public function png2jpg($originalFile, $outputFile, $quality) {
	    $image = imagecreatefrompng($originalFile);
	    imagejpeg($image, $outputFile, $quality);
	    imagedestroy($image);
	}

	// Return the name of an image without extension
	public function ImageNameWithoutExt($image) {
		$image = explode('.',$image);
		array_pop($image);
		$image = implode('.',$image);
		return $image;
	}

	// Prepare the data to send to the news tpl
	public function prepareArrayForNewslist($list) {
        foreach ($list as $news) {
        	if(strlen($news->text) > 200) {
            	$news->text = substr($news->text,0,200); 
            	$news->textShort = true;            	
        	}
        	$news->imageName = $this->ImageNameWithoutExt($news->image);
			$preparedList[] = $news;
        }
    	return $preparedList;
	}

}
 ?>