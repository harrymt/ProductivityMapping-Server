<?php
/**
 * Created by PhpStorm.
 * User: harrymt
 * Date: 12/03/2016
 * Time: 16:48
 */
    require_once 'RequestUtil.class.php';
?>

<div class="intro">
    <div class="intro-inner">
        <div class="intro-pitch">
            <div class="intro-pitch-inner">
                <h2>Study location mapper</h2>
                <p><?= RequestUtil::get('/status/'); ?> at <?= RequestUtil::get('/status/date') . ' ' . RequestUtil::get('/status/time'); ?></p>
                <i class='js-loading-spinner icon-large fa fa-circle-o-notch fa-spin'></i>
                <p><a target="_blank" href="//www.harrymt.com/">harrymt.com</a></p>
            </div>
        </div>
    </div>
</div> <!-- ./intro -->
