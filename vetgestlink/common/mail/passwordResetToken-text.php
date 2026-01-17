<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$resetLink = Yii::getAlias('@frontendUrl') . '/site/reset-password?token=' . $user->password_reset_token;
?>
Olá <?= $user->username ?>,

Siga o link abaixo para redefinir sua senha:

<?= $resetLink ?>
