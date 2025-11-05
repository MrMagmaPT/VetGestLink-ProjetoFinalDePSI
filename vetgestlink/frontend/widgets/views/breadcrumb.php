<?php
use yii\helpers\Html;
use yii\helpers\Url;

// Define default values for variables if not already set
$backgroundImage = $backgroundImage ?? '/static/img/default-background.jpg';
$title = $title ?? 'Default Title';
$items = $items ?? [];
?>

<div class="slider-area2">
    <div class="slider-height2 d-flex align-items-center" style="background-image: url('<?= Yii::getAlias('@web') . $backgroundImage ?>');">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="hero-cap hero-cap2 text-center">
                        <h2><?= Html::encode($title) ?></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><?= Html::a('Home', Url::home()) ?></li>
                                <?php foreach ($items as $item): ?>
                                    <?php if (isset($item['url'])): ?>
                                        <li class="breadcrumb-item"><?= Html::a($item['label'], $item['url']) ?></li>
                                    <?php else: ?>
                                        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($item['label']) ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
