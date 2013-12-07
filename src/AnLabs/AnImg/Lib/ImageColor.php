<?php
namespace AnLabs\AnImg\Lib;

/**
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package AnImag
 */
final class ImageColor
{
    private $color;
    private $rgbColor;

    public function __construct(Image $image, $red, $green, $blue)
    {
        $this->color = imagecolorallocate($image->image(), $red, $green, $blue);
        $this->rgbColor = [$red, $green, $blue];
    }

    public function color()
    {
        return $this->color;
    }

    public function rgbColor()
    {
        return $this->rgbColor;
    }
}
