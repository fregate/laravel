<?php

// Route::get('(:bundle)', function()
// {
// 	return View::make('jupload::index');
// });

// Route::get('(:bundle)/test', function()
// {
// 	return View::make('jupload::test');
// });

Route::any('(:bundle)/xxx', function()
{
        $upload_handler = IoC::resolve('UploadHandler');

        switch (Request::method())
        {
                case 'DELETE':
                        //$upload_handler->delete(null);
			Log::write('info', "Method Delete");
                        break;
                case 'OPTIONS':
                        break;
                case 'HEAD':
                case 'GET':
			Log::write('info', "Method GET");
                        $upload_handler->get(null);
                        break;
                case 'POST': {
                        if (Input::get('_method') === 'DELETE')
                        {
                                $upload_handler->delete(null);
                        }
                        else
                        {
                                $upload_handler->post(null);
                        }
                        break;
                    }
                default:
                        header('HTTP/1.1 405 Method Not Allowed');
        }

//        return View::make('jupload::index');
});


// Route::any('(:bundle)/upload/(:any?)', array('as' => 'upload', function($folder = null)
// {
// 	if ($folder !== null)
// 		$folder .= '/';

// 	$upload_handler = IoC::resolve('UploadHandler');

// 	if ( ! Request::ajax())
// 		return;

// 	switch (Request::method())
// 	{
// 		case 'DELETE':
// 			$upload_handler->delete($folder);
// 			break;
// 		case 'OPTIONS':
// 			break;
// 		case 'HEAD':
// 		case 'GET':
// 			$upload_handler->get($folder);
// 			break;
// 		case 'POST':
// 			if (Input::get('_method') === 'DELETE')
// 			{
// 				$upload_handler->delete($folder);
// 			}
// 			else
// 			{
// 				$upload_handler->post($folder);
// 			}
// 			break;
// 		default:
// 			header('HTTP/1.1 405 Method Not Allowed');
// 	}
// }));
