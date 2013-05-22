
<?php

class AuxImage
{
    protected static function path() { return 'public/uploads'; }

    public static function get_field()
    {
        return 'uimage';
    }

    public static function make($image, $attr = "")
    {
        $filename = $image['name'];
        if($filename == '')
            return 0;

        Input::upload(AuxImage::get_field(), AuxImage::path(), $filename);

        $layer = PHPImageWorkshop\ImageWorkshop::initFromPath(AuxImage::path() . "/" . $filename);

        $new_image = array(
            'mime' => $image['type'],
            'size' => $image['size'],
            'path' => AuxImage::path(),
            'name' => $filename,
            'sx' => $layer->getWidth(),
            'sy' => $layer->getHeight(),
            'shorturl' => uniqid(),
        );

        $img = new Image($new_image);
        $img->save();

        echo $img->id;

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
