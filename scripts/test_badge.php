<?php
use AnLabs\AnImg\Badge;
use AnLabs\AnImg\Lib\Color;

require __DIR__ . '/../vendor/autoload.php';

$badgeCreator = new Badge;
$image = $badgeCreator->createGitHubBadge("feature", "incomplete", Color::orange(), 200);
$image->saveImageToFile('img.png', 'png');

$imageTransform = new \AnLabs\AnImg\ImageTransformer;
$downsampledImage = $imageTransform->resizeTransformAR($image, 22, false);
$downsampledImage->saveImageToFile('img2.png', 'png');
