
<?php

class AuxImage
{
    public static function path() { return path('storage').'uploads/'; }

    public static function get_field()
    {
        return 'uimage';
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
//        $layer = PHPImageWorkshop\ImageWorkshop::initFromPath(AuxImage::path() . $image_name);
        list($img_width, $img_height) = @getimagesize(AuxImage::path() . $image_name);
//        Log::write('info', 'img width '.$img_width.' height '.$img_height);

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

        // Log::write('info', $img->id);

        return $img->id;
    }

    public static function preform($i, $attr = "")
    {
//        return $i;
       $layer = ImageWorkshop::initFromPath($imagepath);
//        if($attr == '')
            return $layer->getResult();
    }

    public static function get_uri($id)
    {
        return URL::base() . "/image/" . $id;
    }
};
