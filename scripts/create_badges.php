#!/usr/bin/env php
<?php
use AnLabs\AnImg\Badge;
use AnLabs\AnImg\Lib\Color;

require __DIR__ . '/../vendor/autoload.php';

$badgeCreator = new Badge;

if ($argc < 2) {
    throw new \Exception("You have to provide a YAML file as an argument!");
}
array_shift($argv);

function getColor($string)
{
    switch ($string) {
        case 'grey':
            $color = Color::grey();
            break;
        case 'blue':
            $color = Color::blue();
            break;
        case 'turquoise':
            $color = Color::turquoise();
            break;
        case 'green':
            $color = Color::green();
            break;
        case 'orange':
            $color = Color::orange();
            break;
        case 'yellow':
            $color = Color::yellow();
            break;
        case 'purple':
            $color = Color::purple();
            break;
        case 'red':
            $color = Color::red();
            break;
        default:
            throw new \Exception("Invalid color: $string");
    }

    return $color;
}

foreach ($argv as $path) {
    $yml = \Symfony\Components\Yaml::parse($path);
    $badges = $yml["badges"];

    foreach ($badges as $badge) {
        $image = $badgeCreator->createGitHubBadge($badge["left"], $badge["right"], Color::orange());
        $image->saveImageToFile($badge["output"], 'png');
        echo "Created '{$badge['left']}' - '{$badge['right']}' ('{$badge['output']}')" . PHP_EOL;
    }
}

echo PHP_EOL . "Done." . PHP_EOL;

