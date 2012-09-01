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

	public function prepareArrayForNewslist($list) {
        foreach ($list as $news) {
        	if(strlen($news->text) > 200) {
            	$news->text = substr($news->text,0,200); 
            	$news->textShort = true;
        	}
			$preparedList[] = $news;
        }
    	return $preparedList;
	}

}
 ?>