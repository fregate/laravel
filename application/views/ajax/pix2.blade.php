<!-- <img src="img/DSC06218.jpg" id="target" alt="[Jcrop Example]" /> -->

<?php
$imgs = Auth::user()->images()->get();
if(count($imgs) == 0) {
	echo "No uploaded images";
} else {
	foreach ($imgs as $img) {
		echo "<p>".$img->name."</p>";
	}
}
?>

<script type="text/javascript">
jQuery(function($){

})
</script>
