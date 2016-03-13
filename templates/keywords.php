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
    $keywords_str = ""; foreach($keywords as $key => $value) { $keywords_str .= "'$key' used by $value users<br>"; }

    $word_pairs = array();
    // Print out the words as a json object { text: "", size: "" }
    foreach($keywords as $key => $value) {
        array_push($word_pairs, array(
            "text" => $key,
            "size" => $value
        ));
    }


?>

<script>var phpKeywords = <?= json_encode($word_pairs); ?>;</script>

<div class="section" id="keywords">
    <h3>Keywords</h3>
    <p class="description">Top 3 keywords used.</p>
    <code><?= $keywords_str ?></code>
    <div class="js-keyword-cloud"></div>
</div><!-- /.section -->