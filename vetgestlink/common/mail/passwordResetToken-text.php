<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

//Mudei isso para sempre ser redirecionado para o frontend para reset de password
//TODO mudar em produção localhost para o domínio real
$resetLink = 'http://localhost/vetgestlink/frontend/web/site/reset-password?token=' . $user->password_reset_token;
?>
Olá <?= $user->username ?>,

Siga o link abaixo para redefinir sua senha:

<?= $resetLink ?>
