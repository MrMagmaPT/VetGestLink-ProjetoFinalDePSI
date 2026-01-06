<?php

use yii\helpers\Html;

/** @var common\models\Fatura $model */
/** @var common\models\Linhafatura[] $linhas */
/** @var string|null $nome_emissor */
/** @var string|null $nome_recep */

$nome_emissor = isset($nome_emissor) && $nome_emissor !== '' ? $nome_emissor : (Yii::$app->user->identity->username ?? 'Emissor');
$nome_recep = isset($nome_recep) && $nome_recep !== '' ? $nome_recep : ($model->userprofiles->nomecompleto ?? 'Cliente');
$created = $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at, 'php:d/m/Y H:i') : date('d/m/Y H:i');
$email_emissor = Yii::$app->user->identity->email ?? '';
?>

<div class="header">
    <div class="company">
        <?php if (!empty($logoDataUri)): ?>
            <div class="logo">
                <img src="<?= Html::encode($logoDataUri) ?>" alt="Logo">
            </div>
        <?php endif; ?>
        <h2>Fatura</h2>
        <div>Emissor: <?= Html::encode($nome_emissor) ?></div>
        <div>Email de Contacto: <?= Html::encode($email_emissor) ?></div>
    </div>
    <div class="invoice-meta">
        <h3>Fatura</h3>
        <div><strong>ID:</strong> #<?= Html::encode($model->id) ?></div>
        <div><strong>Data:</strong> <?= Html::encode($created) ?></div>
        <div><strong>Cliente:</strong> <?= Html::encode($nome_recep) ?></div>
    </div>
</div>

<table>
    <thead>
    <tr>
        <th style="width:5%;">#</th>
        <th>Descrição</th>
        <th style="width:12%;" class="text-right">Quantidade</th>
        <th style="width:18%;" class="text-right">Preço Unit.</th>
        <th style="width:18%;" class="text-right">Total</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($linhas)): ?>
        <tr>
            <td colspan="5" class="text-center">Sem itens</td>
        </tr>
    <?php else: ?>
        <?php $i = 1; foreach ($linhas as $linha): ?>
            <tr>
                <td class="text-center"><?= $i++ ?></td>
                <td>
                    <?php
                    if ($linha->marcacoes && $linha->marcacoes->servicos) {
                        echo Html::encode($linha->marcacoes->servicos->nome
                            . ($linha->marcacoes->animais ? ' - ' . $linha->marcacoes->animais->nome : ''));
                    } elseif ($linha->servicos) {
                        echo Html::encode($linha->servicos->nome);
                    } elseif ($linha->medicamentos) {
                        echo Html::encode($linha->medicamentos->nome);
                    } else {
                        echo Html::encode($linha->descricao ?? 'Item');
                    }
                    ?>
                </td>
                <td class="text-right"><?= Html::encode($linha->quantidade) ?></td>
                <td class="text-right"><?= Html::encode(number_format(($linha->quantidade ? $linha->total / $linha->quantidade : 0), 2, ',', '.')) ?> €</td>
                <td class="text-right"><?= Html::encode(number_format($linha->total, 2, ',', '.')) ?> €</td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<table style="margin-top:10px;">
    <tr class="total-row">
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td class="text-right" style="border:none;"><strong>TOTAL:</strong></td>
        <td class="text-right total-amount"><?= Html::encode(number_format($model->total, 2, ',', '.')) ?> €</td>
    </tr>
</table>

<div class="observacoes">
    <div><strong>Observações:</strong></div>
    <div><?= Html::encode($model->observacoes ?? '') ?></div>
</div>
