<?php
use yii\helpers\Html;

/** @var $model */
/** @var $attribute */
/** @var $previewId */
/** @var $defaultImage */
/** @var $imageSize */
/** @var $borderColor */
/** @var $helpText */
/** @var $hint */
/** @var $label */
/** @var $accept */
/** @var $inputId */
/** @var $form */
?>

<div class="text-center">
    <div style="margin-bottom: 15px; cursor: pointer; position: relative; display: inline-block;" onclick="document.getElementById('<?= $inputId ?>').click();">
        <img id="<?= $previewId ?>" 
             src="<?= $defaultImage ?>" 
             alt="Foto de Perfil" 
             style="width: <?= $imageSize ?>px; height: <?= $imageSize ?>px; border: 3px solid <?= $borderColor ?>; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;">
        <div class="image-upload-overlay" style="position: absolute; top: 0; left: 0; width: <?= $imageSize ?>px; height: <?= $imageSize ?>px; border-radius: 50%; display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; background: rgba(0,0,0,0.5);">
            <i class="fa fa-camera" style="font-size: 40px; color: white;"></i>
        </div>
    </div>
    <p class="text-muted" style="font-size: 12px; margin-bottom: 5px;">
        <?php if ($helpText): ?>
            <i class="fa fa-info-circle"></i> <?= $helpText ?>
        <?php endif; ?>
    </p>
    <p class="text-muted" style="font-size: 11px;">
        <?= $hint ?>
    </p>
    
    <?= $form->field($model, $attribute)->fileInput([
        'class' => 'form-control',
        'accept' => $accept,
        'data-image-preview' => $previewId,
        'style' => 'display: none;'
    ])->label(false)->hint(false) ?>
</div>

<style>
    .image-upload-overlay:hover {
        opacity: 1 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var container = document.querySelector('[onclick*="<?= $inputId ?>"]');
        if (container) {
            var overlay = container.querySelector('.image-upload-overlay');
            container.addEventListener('mouseenter', function() {
                if (overlay) overlay.style.opacity = '1';
            });
            container.addEventListener('mouseleave', function() {
                if (overlay) overlay.style.opacity = '0';
            });
        }
    });
</script>
