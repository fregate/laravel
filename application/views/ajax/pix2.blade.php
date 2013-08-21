<!-- <img src="img/DSC06218.jpg" id="target" alt="[Jcrop Example]" /> -->

<?php
$imgs = Auth::user()->images()->get();
if(count($imgs) == 0) {
	echo "No uploaded images";
} else {
	foreach ($imgs as $img) {
		echo "<span class='imgspan curhand' data-idi=".$img->id."><img src='". AuxImage::get_uri($img->id, AuxImage::get_thumb_attrs($img->id, 0, $rowh)) ."' title='[".$img->sx."x".$img->sy."]'></img></span>";
	}

//echo '<script type="text/javascript" src="js/jquery.montage.min.js"></script>';

}
?>

