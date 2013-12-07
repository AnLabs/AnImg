<?php
namespace AnLabs\AnImg;

/**
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package AnImag
 */
final class TextTransformer
{
    public function applyText(Lib\Image $image, $text, $start_x, $start_y, Lib\ImageColor $color, Lib\ImageFont $font, $angle = 0)
    {
        return imagettftext($image->image(), $font->size(), $angle,
                $start_x , $start_y, $color->color(), $font->file(), $text);
    }
}
