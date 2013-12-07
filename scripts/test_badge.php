<?php
use AnLabs\AnImg\Badge;
use AnLabs\AnImg\Lib\Color;

require __DIR__ . '/../vendor/autoload.php';

$badgeCreator = new Badge;
$image = $badgeCreator->createGitHubBadge("feature", "incomplete", Color::orange());
$image->saveImageToFile('img.png', 'png');
