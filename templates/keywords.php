<?php
    require_once 'RequestUtil.class.php';
?>

<?php
    $keywords = RequestUtil::get('/keywords/3');
    $keywords_str = ""; foreach($keywords as $key => $value) { $keywords_str .= "'$key' is used in $value zones<br>"; }

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
    <h3>Most Popular Keywords</h3>
    <p class="description">The top 3 keywords used in the app.</p>
    <code><?= $keywords_str ?></code>
</div><!-- /.section -->