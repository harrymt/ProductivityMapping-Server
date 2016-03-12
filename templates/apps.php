<?php
/**
 * Created by PhpStorm.
 * User: harrymt
 * Date: 12/03/2016
 * Time: 16:55
 */
    require_once 'RequestUtil.class.php';
?>

<?php
    $apps = RequestUtil::get('/apps/3');
    $apps_str = ""; foreach($apps as $key => $value) { $apps_str .= "$key : $value<br>"; }
?>

<h1>Apps</h1>
<p>Top 3 most popular blocked Apps</p>
<code><?= $apps_str ?></code>