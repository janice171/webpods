<?php
require_once(dirname(__FILE__) . '/config.php');
if(!isAuth()) {
	die('Access denied');
}

$files = glob($uploadPath . '/.thumbs/*.{jpg,gif,jpeg,png}',GLOB_BRACE);
if(!empty($files)) {
	foreach($files as $image) : $base = urlencode(basename($image)); ?>
		<li><a class="insert" href="<?php echo $imageURL . '/' . $base; ?>">&nbsp;</a>
			<a class="delete" href="<?php echo $imageURL . '/' . $base; ?>">&nbsp;</a>
			<img src="<?php echo $imageURL . '/.thumbs/' . $base; ?>" alt="" /></a>
	<?php endforeach;
}