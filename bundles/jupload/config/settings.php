<?php

return array(
//	'script_url' => URL::to_route('upload'),
	'upload_dir' => AuxImage::path(),  //path('public').'bundles/jupload/uploads/files/',
//	'upload_url' => URL::base().'/bundles/jupload/uploads/files/',
	'delete_type' => 'POST',
//	'max_file_size' => 8000000,
// 	'image_versions' => array(
// 		'thumbnail' => array(
// 			'upload_dir' => AuxImage::path(), //path('public').'bundles/jupload/uploads/thumbnails/',
// //			'upload_url' => URL::base().'/bundles/jupload/uploads/thumbnails/',
// 		),
// 	),
);
