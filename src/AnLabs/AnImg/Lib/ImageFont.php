<?php
namespace AnLabs\AnImg\Lib;

/**
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package AnImag
 */
final class ImageFont
{
    private $font_file;
    private $font_size;

    public function __construct($path, $size)
    {
        $this->font_file = $path;
        $this->font_size = $size;
    }

    public function file()
    {
        return $this->font_file;
    }

    public function size()
    {
        return $this->font_size;
    }

    public function width($text)
    {
        $dim = $this->calculateTextDimension($text);
        return $dim["width"];
    }

    public function height($text)
    {
        $dim = $this->calculateTextDimension($text);
        return $dim["height"];
    }

    private function calculateTextDimension($text, $angle = 0)
    {
        $bounding_box = imagettfbbox($this->size(), $angle, $this->file(), $text);

        $text_height = abs($bounding_box[3] - $bounding_box[5]);
        $text_width = abs($bounding_box[0] - $bounding_box[2]);

        return [
            "width"  => $text_width,
            "height" => $text_height,
        ];
    }
}
