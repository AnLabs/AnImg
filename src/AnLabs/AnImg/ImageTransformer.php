<?php
namespace AnLabs\AnImg;

/**
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package AnImag
 */
final class ImageTransformer
{
    /**
     * Retains aspect ratio
     * 
     * @param Lib\TCGDImage $img
     * @param int $newDimension
     * @param bool $isWidth
     *
     * @return Lib\TCGDImage A new resized image
     */
    public function resizeTransformAR(Lib\TCGDImage $img, $newDimension, $isWidth = true)
    {
        $old_x = $img->width();
        $old_y = $img->height();

        if ($isWidth) {
            $factor = $newDimension / $old_x;
            $aspect_ratio = $old_x /  $old_y;
        } else {
            $factor = $newDimension / $old_y;
            $aspect_ratio = $old_x /  $old_y;
        }

        // Calculating the new width and height
        $new_x = (int)($old_x * $factor);
        $new_y = (int)($old_y * $factor);

        // Creating the new image
        $dst_img = new Lib\TCGDImage($new_x, $new_y);
        $dst_img->fillColor(255, 255, 255, 0);
        imagecopyresampled($dst_img->image(),$img->image(),0,0,0,0,$new_x,$new_y,$old_x,$old_y);

        return $dst_img;
    }

    public function UnsharpMask(Lib\TCGDImage $img, $amount = 50, $radius = 0.4, $threshold = 3)
    {
        ////////////////////////////////////////////////////////////////////////
        ////
        ////                  Unsharp Mask for PHP - version 2.1.1
        ////
        ////    Unsharp mask algorithm by Torstein Hønsi 2003-07.
        ////             thoensi_at_netcom_dot_no.
        ////               Please leave this notice.
        ////
        ////////////////////////////////////////////////////////////////////////

        // Adapted to framework by Anh Nhan Nguyen



        // $img is an image that is already created within php using
        // imgcreatetruecolor. No url! $img must be a truecolor image.

        // Attempt to calibrate the parameters to Photoshop:
        if ($amount > 500) {
            $amount = 500;
        }
        $amount = $amount * 0.016;
        if ($radius > 50) {
            $radius = 50;
        }
        $radius = $radius * 2;
        if ($threshold > 255) {
            $threshold = 255;
        }

        $radius = abs(round($radius));     // Only integers make sense.
        if ($radius == 0) {
            return $img;
            imagedestroy($img);
            break;
        }

        $width = $img->width();
        $height = $img->height();
        $imgCanvas = imagecreatetruecolor($width, $height);
        $imgBlur = imagecreatetruecolor($width, $height);


        // Gaussian blur matrix:
        //
        //    1    2    1
        //    2    4    2
        //    1    2    1
        //
        //////////////////////////////////////////////////


        if (function_exists('imageconvolution')) { // PHP >= 5.1
            $matrix = array(
                array( 1, 2, 1 ),
                array( 2, 4, 2 ),
                array( 1, 2, 1 )
            );
            imagecopy ($imgBlur, $img->image(), 0, 0, 0, 0, $width, $height);
            imageconvolution($imgBlur, $matrix, 16, 0);
        }

        if ($threshold>0) {
            // Calculate the difference between the blurred pixels and the original
            // and set the pixels
            for ($x = 0; $x < $width-1; $x++) { // each row
                for ($y = 0; $y < $height; $y++) { // each pixel

                    $rgbOrig = $img->colorAt($x, $y);
                    $rOrig = (($rgbOrig >> 16) & 0xFF);
                    $gOrig = (($rgbOrig >> 8) & 0xFF);
                    $bOrig = ($rgbOrig & 0xFF);

                    $rgbBlur = ImageColorAt($imgBlur, $x, $y);

                    $rBlur = (($rgbBlur >> 16) & 0xFF);
                    $gBlur = (($rgbBlur >> 8) & 0xFF);
                    $bBlur = ($rgbBlur & 0xFF);

                    // When the masked pixels differ less from the original
                    // than the threshold specifies, they are set to their original value.
                    $rNew = (abs($rOrig - $rBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))
                    : $rOrig;
                    $gNew = (abs($gOrig - $gBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))
                    : $gOrig;
                    $bNew = (abs($bOrig - $bBlur) >= $threshold)
                    ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))
                    : $bOrig;



                    if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {
                        $pixCol = $img->allocateColor($rNew, $gNew, $bNew);
                        $img->setPixel($x, $y, $pixCol);
                    }
                }
            }
        } else {
            for ($x = 0; $x < $width; $x++) { // each row
                for ($y = 0; $y < $height; $y++) { // each pixel
                    $rgbOrig = $img->colorAt($x, $y);
                    $rOrig = (($rgbOrig >> 16) & 0xFF);
                    $gOrig = (($rgbOrig >> 8) & 0xFF);
                    $bOrig = ($rgbOrig & 0xFF);

                    $rgbBlur = ImageColorAt($imgBlur, $x, $y);

                    $rBlur = (($rgbBlur >> 16) & 0xFF);
                    $gBlur = (($rgbBlur >> 8) & 0xFF);
                    $bBlur = ($rgbBlur & 0xFF);

                    $rNew = ($amount * ($rOrig - $rBlur)) + $rOrig;
                    if ($rNew>255) {
                        $rNew=255;
                    } elseif ($rNew<0) {
                        $rNew=0;
                    }

                    $gNew = ($amount * ($gOrig - $gBlur)) + $gOrig;
                    if ($gNew>255) {
                        $gNew=255;
                    } elseif($gNew<0) {
                        $gNew=0;
                    }

                    $bNew = ($amount * ($bOrig - $bBlur)) + $bOrig;
                    if ($bNew>255){
                        $bNew=255;
                    } elseif ($bNew<0) {
                        $bNew=0;
                    }

                    $rgbNew = ($rNew << 16) + ($gNew <<8) + $bNew;
                    ImageSetPixel($img->image(), $x, $y, $rgbNew);
                }
            }
        }
        imagedestroy($imgCanvas);
        imagedestroy($imgBlur);

        return $img;
    }
}
