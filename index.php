<?php
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

    <table class="perferences">
      <tr>
        <td>
          <?php include('templates/keywords.php'); ?>
        </td>
        <td>
          <?php include('templates/apps.php'); ?>
        </td>
      </tr>
    </table>

    <?php include('templates/footer.php'); ?>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= Environment_variable::$API_GOOGLE_KEY ?>&amp;callback=initMap"></script>

    <script src="js/built/main.min.js"></script>
</body>
</html>