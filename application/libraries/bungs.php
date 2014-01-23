<?php

class Bungs {

protected $bungs_path = "img/bungs";

	public static function 	files($directory)
	{
		//Log::write("info", $directory);
		$glob = glob($directory.'/*');

		if ($glob === false) return array();

		// To get the appropriate files, we'll simply glob the directory and filter
		// out any "files" that are not truly files so we do not end up with any
		// directories in our list, but only true files within the directory.
		return array_filter($glob, function($file) {
			return filetype($file) == 'file';
		});
	}

public $bungs_arr = array();

   function __construct() {
   	$this->bungs_arr = $this->files(path('public').$this->bungs_path);
   }

   public function get_bung_img() {
   	return URL::base()."/".$this->bungs_path."/".basename($this->bungs_arr[array_rand($this->bungs_arr, 1)]);
   }
}
