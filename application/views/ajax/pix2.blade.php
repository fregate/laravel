<!-- <img src="img/DSC06218.jpg" id="target" alt="[Jcrop Example]" /> -->

<?php
$imgs = Auth::user()->images()->get();
if(count($imgs) == 0) {
	echo "No uploaded images";
} else {
	foreach ($imgs as $img) {
		$a = AuxImage::get_thumb_attrs($img->id, 0, $rowh);
		echo "<span class='imgspan'><img src='". AuxImage::get_uri($img->id, $a) ."'></img></span>";
	}

//echo '<script type="text/javascript" src="js/jquery.montage.min.js"></script>';

}
?>

