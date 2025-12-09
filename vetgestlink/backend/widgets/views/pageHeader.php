<?php
/**
 * @var string $title
 * @var string $icon
 * @var array $breadcrumbs
 */
use yii\helpers\Html;

?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas <?= Html::encode($icon) ?>"></i>
                    <?= Html::encode($title) ?>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <?php foreach ($breadcrumbs as $bc): ?>
                        <?php if (isset($bc['url'])): ?>
                            <li class="breadcrumb-item">
                                <?= Html::a($bc['label'], $bc['url']) ?>
                            </li>
                        <?php else: ?>
                            <li class="breadcrumb-item active">
                                <?= $bc['label'] ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</div>
