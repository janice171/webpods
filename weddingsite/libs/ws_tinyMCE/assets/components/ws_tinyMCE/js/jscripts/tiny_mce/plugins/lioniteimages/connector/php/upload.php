<?php
require_once(dirname(__FILE__) . '/config.php');
if(isAuth()) {
		
	$filename = !empty($_FILES) && isset($_FILES['qqfile']) ? $_FILES['qqfile']['name'] : $_GET['qqfile'];
	$filename = cleanupFilename($filename);
	
	include ('qqUploader.php');
	
	$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
	$uploader ->setFilename($filename);
	$result = $uploader->handleUpload($uploadPath . '/');
	if(isset($result['success']) && $result['success'] == true) {
		createthumb($uploadPath . '/' . $filename,$uploadPath . '/.thumbs/' . $filename, 100, 100);
		$result['image'] = $imageURL . '/' . urlencode($filename);
		$result['thumb'] = $imageURL . '/.thumbs/' . urlencode($filename);
	}
	echo htmlspecialchars(json_encode($result),ENT_NOQUOTES);
}

function cleanupFilename($filename) {
	//Clean up filename
	$filename = str_replace(array('_' ,'!','@','$','%','^','&','*','(',')',"'",'`',';','<','>','"',",",'/'),'',$filename);
	$filename = strtolower(str_replace(' ','-',$filename));
	$filename = str_replace(array('--','---','----'),'-',$filename);
	$filename = urlencode($filename);
	//Add a small random suffix to prevent overwrite
	$suffix = substr(md5(mt_rand(0, 100000000)),0,8);
	$ext = substr($filename,strrpos($filename,'.') + 1);
	$filename = str_replace('.' . $ext,'-' . $suffix . '.' . $ext,$filename);
	return $filename;
}
function createthumb($file,$target,$x,$y) {
	$image = getimagesize($file);
	if(!$image) {
		return false;
	}
	switch ($image['mime'])
	{
		case 'image/gif':
			$createFunction	= 'imagecreatefromgif';
			$outputFunction		= 'imagegif';
		break;
		
		case 'image/x-png':
		case 'image/png':
			$createFunction	= 'imagecreatefrompng';
			$outputFunction		= 'imagepng';
		break;
		
		default:
			$createFunction	= 'imagecreatefromjpeg';
			$outputFunction	 	= 'imagejpeg';
		break;
	}

	$imageRsc =  $createFunction($file);
	$prevX = imageSX($imageRsc);
	$prevY = imageSY($imageRsc);
	if ($prevX > $prevY) {
		$width = $x;
		$height = $prevY * ($y/$prevX);
	} else if ($prevX < $prevY)	{
		$width=$prevX*($x/$prevY);
		$height=$y;
	}else if ($prevX == $prevY)	{
		$width=$x;
		$height=$y;
	}
	$imgRsc=imagecreatetruecolor($width,$height);
	imagecopyresampled($imgRsc,$imageRsc,0,0,0,0,$width,$height,$prevX,$prevY);
	 
	$outputFunction($imgRsc,$target);
	imagedestroy($imgRsc); 
	imagedestroy($imageRsc); 
}