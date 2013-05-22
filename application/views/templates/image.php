<?php
$im = imagecreatetruecolor(120, 20);
$text_color = imagecolorallocate($im, 233, 14, 91);
imagestring($im, 1, 5, 5,  'A Simple Text String', $text_color);

// Устанавливаем тип содержимого в заголовок, в данном случае image/jpeg
//header('Content-Disposition: Attachment;filename=' . uniqid() . '.png'); 
header('Content-Type: image/png');

// Пропускаем параметр filename, используя NULL, а затем устанавливаем качество в 75%
imagepng($im);

// Освобождаем память
imagedestroy($im);

//$layer = PHPImageWorkshop\ImageWorkshop::initFromPath($path);

//$image = $layer->getResult("ffFFff");

//header('Content-type: image/jpeg);
//imagejpeg($image);

/*if (imagetypes() & IMG_PNG) {
    echo "Поддержка PNG включена";
}

if (imagetypes() & IMG_JPG) {
    echo "Поддержка JPG включена";
}
if (imagetypes() & IMG_GIF) {
    echo "Поддержка GIF включена";
}*/

?>
