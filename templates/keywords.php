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
    $keywords = RequestUtil::get('/keywords/3');
    $keywords_str = ""; foreach($keywords as $key => $value) { $keywords_str .= "$key : $value<br>"; }
?>

<div class="section" id="keywords">
    <h3>Keywords</h3>
    <p class="description">Top 3 keywords used.</p>
    <code><?= $keywords_str ?></code>
</div><!-- /.section -->