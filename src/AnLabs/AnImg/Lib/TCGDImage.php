<?php
namespace AnLabs\AnImg\Lib;

/**
 * Represents a GD image object
 *
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package AnImag
 */
final class TCGDImage extends Image
{
    public function allocateColor($red, $green, $blue)
    {
        return new ImageColor($this, $red, $green, $blue);
    }

    public function allocateColorForObject(Color $color)
    {
        $rgb = Color::getColorRgb($color->color());
        return $this->allocateColor($rgb[0], $rgb[1], $rgb[2]);
    }

    public function fillColor($red, $green, $blue, $alpha = 255)
    {
        $color = imagecolorallocatealpha($this->image(), $red, $green, $blue, $alpha);
        imagefilledrectangle($this->image(), 0, 0, $this->width(), $this->height(), $color);
    }

    public function destroy()
    {
        imagedestroy($this->image());
        unset($this);
    }

    protected function initImage($fileContents)
    {
        $image = imagecreatefromstring($fileContents);
        
        if ($image === false) {
            throw new \Exception("Unsupported or corrupt image data!");
        }
        imagealphablending($image, true);
        $this->setWidth(imagesx($image));
        $this->setHeight(imagesy($image));
        
        return $image;
    }

    protected function renderImage($newExtension)
    {
        ob_start();
        $image = $this->image();

        switch ($newExtension) {
            case 'png':
                imagepng($image, null, 0);
                break;
            case 'jpg':
            case 'jpeg':
                imagejpeg($image, null, 100);
                break;
            case 'gif':
                imagegif($image, null);
                break;

            default:
                throw new \Exception("Unsupported file format '$newExtension'");
                break;
        }

        return ob_get_clean();
    }

    protected static function createEmptyWithDimensions($width, $height)
    {
        return imagecreatetruecolor($width, $height);
    }
}
