<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$verifyLink = Yii::getAlias('@frontendUrl') . '/site/verify-email?token=' . $user->verification_token;
?>
Olá <?= $user->username ?>,

Siga o link abaixo para verificar o seu email:

<?= $verifyLink ?>
