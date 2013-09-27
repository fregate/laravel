
<?php

class AuxImage
{
    public static function path() { return path('storage').'uploads/'; }

    public static function remove($imageid)
    {
      $img = Image::find($imageid);
      if($img == null)
        return false;

      $ipath = $img->path . $img->name;
      if(!is_readable($ipath)) {
        $img->delete();
Log::write('info', 'remove image: file '.$ipath.' non exist or smth else. remove only db record');
        return true;
      }

      unlink($ipath);
        $img->delete();
Log::write('info', 'remove image: file '.$ipath.' successfull');
        return true;
    }

    public static function make($uploaded_data, $image_size, $image_type, $image_name, $attr = "")
    {
        if($image_name == '')
            return 0;

Log::write('info', 'non empty, moving to '.AuxImage::path().$image_name);
	$mres = move_uploaded_file($uploaded_data, AuxImage::path().$image_name);

Log::write('info', 'move_file returns '.$mres);

        return AuxImage::img_store($image_name, $image_type, $image_size);
    }

    public static function img_store($image_name, $image_type, $image_size)
    {
        list($img_width, $img_height) = @getimagesize(AuxImage::path() . $image_name);

        $new_image = array(
            'mime' => $image_type,
            'size' => $image_size,
            'path' => AuxImage::path(),
            'name' => $image_name,
            'sx' => $img_width,
            'sy' => $img_height,
            'user_id' => Auth::user()->id,
            'shorturl' => uniqid(),
        );

        $img = new Image($new_image);
        $img->save();

        return $img->id;
    }

    public static function get_uri($id, $attrs = "")
    {
        return URL::base() . "/image/" . $id . "/" . $attrs;
    }

    public static function transform($layer, $attrs, $id)
    {
        $a = json_decode(base64_decode($attrs), true);
        if(!isset($a['framex']) || !isset($a['framey']))
            return $layer;

        if($layer->getimageformat() == 'GIF')
        {
//        Log::write('info', 'number of images '.$xlayer->getNumberImages());
    	    for($f = $layer->getNumberImages(); $f > 1; $f--)
    	    {
    	        $layer->removeimage();
    	    }
	// Log::write('info', 'new number of images '.$layer->getNumberImages());
        }

// check for percents
        if(isset($a['w']) && is_string($a['w']))
	{
	  $img = Image::find($id);
	  if($img == null)
            return $layer;
          $a['w'] = $img->sx * str_replace('%', '', $a['w']) / 100;
	}

        if(isset($a['h']) && is_string($a['h']))
        {
          $img = Image::find($id);
          if($img == null)
            return $layer;
          $a['w'] = $img->sy * str_replace('%', '', $a['h']) / 100;
        }


        $layer->cropimage(
            isset($a['w']) ? $a['w'] : $a['framex'],
            isset($a['h']) ? $a['h'] : $a['framey'],
            isset($a['x']) ? $a['x'] : 0,
            isset($a['y']) ? $a['y'] : 0
        );
//        Log::write('info', json_encode($a));
//        $layer->resampleimage($a['framex'], $a['framey'], 0, 1);
        $layer->resizeimage($a['framex'], $a['framey'], 0, 1);

        return $layer;
    }

    public static function get_thumb_attrs($id, $frame_width, $frame_height)
    {
        $img = Image::find($id);
        if($img == null || ($frame_width == 0 && $frame_height == 0))
            return "";

        if($frame_width == 0)
        {
            $frame_width = $frame_height * $img->sx / $img->sy;
        }

        if($frame_height == 0)
        {
            $frame_height = $frame_width * $img->sy / $img->sx;
        }

        $frame_aspect = $frame_width / $frame_height;

        $attrs = new stdClass();
        $attrs->x = 0;
        $attrs->y = 0;

        if($img->sy > $img->sx)
        {
            $attrs->w = round(max($frame_width, $img->sx), 0, PHP_ROUND_HALF_UP);
            $attrs->h = round($attrs->w / $frame_aspect, 0, PHP_ROUND_HALF_UP);
        }
        else
        {
            $attrs->h = round(max($frame_height, $img->sy), 0, PHP_ROUND_HALF_UP);
            $attrs->w = round($attrs->h * $frame_aspect, 0, PHP_ROUND_HALF_UP);
        }

        $attrs->framex = round($frame_width, 0, PHP_ROUND_HALF_UP);
        $attrs->framey = round($frame_height, 0, PHP_ROUND_HALF_UP);

        return base64_encode(json_encode($attrs));
    }
};
