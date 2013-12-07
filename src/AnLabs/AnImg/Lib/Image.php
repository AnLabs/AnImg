<?php
namespace AnLabs\AnImg\Lib;

/**
 * Represents a neutral image object
 *
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package AnImag
 */
abstract class Image
{
    private $image;
    private $fileExtension;
    
    final public function image()
    {
        return $this->image;
    }
    
    final protected function setImage($image)
    {
        $this->image = $image;
        $this->setWidth(imagesx($image));
        $this->setHeight(imagesy($image));
        imagealphablending($image, true);
        return $this;
    }

    final public function fileExtension()
    {
        return $this->fileExtension;
    }

    final public function changeFileExtention($fileExtension)
    {
        $this->fileExtension = $fileExtension;
        return $this;
    }

    private $width;
    private $height;

    final public function width()
    {
        return $this->width;
    }

    final protected function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    final public function height()
    {
        return $this->height;
    }

    final protected function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function loadImageFromFile($path)
    {
        $fileContents = file_get_contents($path);
        $this->initImage($fileContents);
        return $this;
    }

    /**
     * Initializes internal image object used for operations. Usually used for
     * loading an image from file
     *
     * @param bytestream(string) $fileContents The file contents of the image
     *                                         file.
     */
    abstract protected function initImage($fileContents);

    final public function saveImageToFile($path, $newExtension = null)
    {
        $image = $this->renderImage($newExtension ? : $this->fileExtension);
        return file_put_contents($path, $image);
    }

    /**
     * Renders the internal image object into a byte stream, ready for saving
     * to a file
     *
     * @param string $newExtension The file format to be rendered to. It is
     *                             always set, either to a new format or the
     *                             current file format.
     */
    abstract protected function renderImage($newExtension);
    
    public function __construct($el1 = null, $el2 = null)
    {
        if (is_resource($el1)) {
            $this->setImage($el1);
        } else if (is_int($el1) && is_int($el2)) {
            $this->setImage(static::createEmptyWithDimensions($el1, $el2));
        } else {
            $this->loadImageFromFile($el1);
        }
    }

    abstract protected static function createEmptyWithDimensions($width, $height);
}
