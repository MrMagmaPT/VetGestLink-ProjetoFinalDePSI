<?php
// Define the path to the logo
$logoPath = '/static/img/logo/logo.png';
?>

<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <img src="<?= Yii::getAlias('@web') . $logoPath ?>" alt="Loading...">
            </div>
        </div>
    </div>
</div>
