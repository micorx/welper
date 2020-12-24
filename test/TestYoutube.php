<?php
include_once 'config/config.php';
include_once '../src/Test.php';

use Micorx\Welper\Test;

$link = 'https://www.youtube.com/watch?v=jNQXAC9IVRw';
$tester = new Test();
$video = $tester->test_youtube_video($link);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
</head>

<body>
    TEST YOU TUBE VIDEO
    <?php var_dump($video); ?>
    <?php echo $video; ?>
</body>

</html>