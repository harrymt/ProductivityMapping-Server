<?php
/**
 *
 */
    require_once '../Environment_variable.class.php';

?>

<!DOCTYPE html>
<html>
<head>
    <?php include('templates/head.php'); ?>
</head>
<body>
    <?php include('templates/cover.php'); ?>

    <?php include('templates/maps.php'); ?>

    <?php include('templates/keywords.php'); ?>

    <?php include('templates/apps.php'); ?>

    <?php include('templates/footer.php'); ?>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= Environment_variable::$API_GOOGLE_KEY ?>&callback=initMap"></script>

    <script src="js/built/main.min.js"></script>
</body>
</html>