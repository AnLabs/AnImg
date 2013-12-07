<?php
namespace AnLabs\AnImg\Lib;

/**
 * Color enumeration
 *
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package AnImag
 */
final class Color
{
    const GREY = 0;
    const BLUE = 1;
    const TURQUOISE = 2;
    const GREEN = 3;
    const ORANGE = 4;
    const YELLOW = 5;
    const PURPLE = 6;
    const RED = 7;

    const REGULAR = Color::GREY;

    private $color;

    private function __construct($color = Color::REGULAR)
    {
        $this->color = $color;
    }

    public function color()
    {
        return $this->color;
    }

    private static $colorRgbs = [
        self::GREY => [33, 33, 33],
        self::BLUE => [0, 0, 150],
        self::TURQUOISE => [0, 150, 255],
        self::ORANGE => [230, 150, 33],
        self::YELLOW => [255, 200, 33],
        self::PURPLE => [80, 0, 180],
        self::RED => [200, 0, 0],
    ];
    
    public static function getColorRgb($name)
    {
        return self::$colorRgbs[$name] ? : null;
    }

    private static $colors = [];
    
    public static function grey()
    {
        return self::getColorObject(Color::GREY);
    }
    
    public static function red()
    {
        return self::getColorObject(Color::RED);
    }
    
    public static function green()
    {
        return self::getColorObject(Color::GREEN);
    }
    
    public static function blue()
    {
        return self::getColorObject(Color::BLUE);
    }
    
    public static function turquoise()
    {
        return self::getColorObject(Color::TURQUOISE);
    }
    
    public static function yellow()
    {
        return self::getColorObject(Color::YELLOW);
    }
    
    public static function orange()
    {
        return self::getColorObject(Color::ORANGE);
    }
    
    public static function purple()
    {
        return self::getColorObject(Color::PURPLE);
    }

    private static function getColorObject($name)
    {
        if (!isset(self::$colors[$name])) {
            self::$colors[$name] = new Color($name);
        }

        return self::$colors[$name];
    }
}
