<?php
namespace AnLabs\AnImg;

/**
 * Creates badges, yay!
 *
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package AnImag
 */
final class Badge
{
    const FONT_PATH_WINDOWS = "C:\\Windows\\Fonts\\";

    public function createGitHubBadge($leftText, $rightText, Lib\Color $color = null, $fontSize = 11)
    {
        if (!$color) {
            $color = Lib\Color::turquoise();
        }

        $marginFactor = $fontSize / 10;
        $roundRadius = 2 * $marginFactor;
        $margin = [
            "top"    => 4 * $marginFactor,
            "bottom" => 4 * $marginFactor * 1.5,
            "left"   => 4 * $marginFactor,
            "right"  => 4 * $marginFactor,
        ];
        $text = $leftText . $rightText;

        // Currently Windows only :(
        $font = $this->bruteFontSize(self::FONT_PATH_WINDOWS . "DejaVuSansCondensed.ttf", $text, $fontSize);
        $width = [
            "left"  => $font->width($leftText),
            "right" => $font->width($rightText),
            "total" => $font->width($text),
        ];
        $badgeWidth = (int)(4 * $margin["left"] + 4 * $margin["right"] + $width["total"]);
        $badgeHeight = (int)($margin["top"] + $margin["bottom"] + $font->height($text));

        $badgeImage = new Lib\TCGDImage($badgeWidth, $badgeHeight);
        $badgeImage->fillColor(255, 255, 255, 0);

        $leftSideWidth = $margin["left"] * 4 + $width["left"];
        $badgeImage->roundedRectangle(0, 0, $leftSideWidth + 5, $badgeHeight - 1, Lib\Color::grey(), $roundRadius);
        $badgeImage->roundedRectangle($leftSideWidth, 0, $badgeWidth - 1, $badgeHeight - 1, $color, $roundRadius);

        $text_color = $badgeImage->allocateColor(255, 255, 255);
        
        $textY = $margin["top"] + $font->height($text) - 2;
        $this->createShadowText($badgeImage, $leftText, 2 * $margin["left"], $textY, $text_color, $font, $marginFactor / 2);

        $leftDistance = $margin["left"] * 3 + $margin["right"] * 3 + $width["left"];
        $this->createShadowText($badgeImage, $rightText, $leftDistance, $textY, $text_color, $font, $marginFactor / 2);

        return $badgeImage;
    }

    private function createShadowText(Lib\TCGDImage $image, $text, $x, $y, Lib\ImageColor $color, Lib\ImageFont $font, $offset = 1)
    {
        $oldRgb = $color->rgbColor();
        $shadeColor = $image->allocateColor($oldRgb[0] * 0.3, $oldRgb[1] * 0.3, $oldRgb[2] * 0.3);
        $textTransformer = new TextTransformer;
        $offset = max([1, $offset]);

        $textTransformer->applyText($image, $text, $x + $offset, $y + $offset, $shadeColor, $font);
        $textTransformer->applyText($image, $text, $x, $y, $color, $font);
    }

    private function bruteFontSize($font, $text, $wanted)
    {
        $start = 1;
        $newFont = new Lib\ImageFont($font, $start);
        $height = (int)$newFont->height($text);
        $oldHeight = $height;

        while (!($oldHeight < $wanted && $wanted < $height || $wanted == $height)) {
            $start += 1;
            $newFont = new Lib\ImageFont($font, $start);
            $oldHeight = $height;
            $height = (int)$newFont->height($text);
        }

        return $newFont;
    }
}
