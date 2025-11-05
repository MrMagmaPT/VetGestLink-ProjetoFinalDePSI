<?php
use yii\helpers\Html;

// Define default values for $type and $message if not already set
$type = $type ?? 'info';
$message = $message ?? 'No message provided.';

$alertTypes = [
    'error' => 'danger',
    'danger' => 'danger',
    'success' => 'success',
    'info' => 'info',
    'warning' => 'warning'
];

$alertClass = $alertTypes[$type] ?? 'info';
?>

<div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
    <?= Html::encode($message) ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
