<?php
    require_once 'RequestUtil.class.php';
?>

<div class="intro">
    <div class="intro-inner">
        <div class="intro-gradient"></div>

        <div class="intro-pitch">
            <div class="intro-pitch-inner">
                <p class="name">Harry Mumford<span>&#45;</span>Turner</p>
                <h2><a href="http://www.nottingham.ac.uk/" target="_blank">UoN</a> Study Mapper</span></h2>
                <a class="subtext"><?= RequestUtil::get('/status/'); ?> at <?= RequestUtil::get('/status/time'); ?></a>
            </div>
        </div>
    </div>
</div> <!-- ./intro -->