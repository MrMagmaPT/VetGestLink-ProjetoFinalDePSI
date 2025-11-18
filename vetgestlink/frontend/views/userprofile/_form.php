<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$user = $user ?? ($model->user ?? null);
$moradas = $moradas ?? ($model->moradas ?? [ArrayHelper::toArray($model)]);
?>

<?php $form = ActiveForm::begin(['id' => 'userprofile-form']); ?>

    <!-- Informações de Contato -->
    <div class="mb-4">
        <div class="fw-semibold text-success mb-2">Informações de Contato</div>
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-2">
                <span class="me-2">
                    <!-- SVG Email -->
                    <div class="text-muted me-2 small align-self-start "><i class="fa-regular fa-user text-success"></i></div>
                </span>
                    <div>
                        <div class="small text-muted">Nome Completo</div>
                        <div class="fw-medium"><?= Html::encode($model->nomecompleto ?? '-') ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-2">
                <span class="me-2">
                    <!-- SVG Phone -->
                    <div class="text-muted me-2 small align-self-start "><i class="fa-solid fa-phone text-success"></i></div>
                </span>
                    <div>
                        <div class="small text-muted">Telefone</div>
                        <?= $form->field($model, 'telemovel', [
                                'template' => '{input}{error}',
                                'options' => ['class' => 'mb-0'],
                        ])->textInput(['class' => 'form-control border-0 border-bottom rounded-0', 'style' => 'background:transparent;'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-2">
                <span class="me-2">
                    <!-- SVG User -->
                    <div class="text-muted me-2 small align-self-start "><i class="fa-regular fa-address-card text-success"></i></div>
                </span>
                    <div>
                        <div class="small text-muted">NIF</div>
                        <?= $form->field($model, 'nif', [
                                'template' => '{input}{error}',
                                'options' => ['class' => 'mb-0'],
                        ])->textInput(['class' => 'form-control border-0 border-bottom rounded-0', 'style' => 'background:transparent;'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-2">
                <span class="me-2">
                    <!-- SVG Calendar -->
                    <div class="text-muted me-2 small align-self-start "><i class="fa-regular fa-calendar text-success"></i></div>
                </span>
                    <div>
                        <div class="small text-muted">Data de Nascimento</div>
                        <?= $form->field($model, 'dtanascimento', [
                                'template' => '{input}{error}',
                                'options' => ['class' => 'mb-0'],
                        ])->input('date', [
                                'class' => 'form-control border-0 border-bottom rounded-0 bg-white',
                                'readonly' => true,
                                'tabindex' => -1,
                                'style' => 'background:transparent;'
                        ])->label(false) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-3">

    <!-- Moradas -->
    <div class="mb-4">
        <div class="fw-semibold text-success mb-2">Moradas</div>
        <div id="moradas-list">
            <?php foreach ($moradas as $i => $morada): ?>
                <div class="morada-item mb-3 border-bottom pb-3 position-relative">
                    <div class="fw-semibold mb-2">Morada <?= $i == 0 ? 'Principal' : ($i+1) ?></div>
                    <?php if ($i > 0): ?>
                        <button type="button" class="btn-close position-absolute top-0 end-0 remove-morada" aria-label="Remover"></button>
                    <?php endif; ?>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between py-2 border-top">
                                <div class="text-muted small">Rua</div>
                                <?= Html::textInput("Morada[$i][rua]", ArrayHelper::getValue($morada, 'rua', ''), [
                                        'class' => 'form-control border-0 border-bottom rounded-0 text-end', 'style' => 'background:transparent;'
                                ]) ?>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-top">
                                <div class="text-muted small">Andar</div>
                                <?= Html::textInput("Morada[$i][andar]", ArrayHelper::getValue($morada, 'andar', ''), [
                                        'class' => 'form-control border-0 border-bottom rounded-0 text-end', 'style' => 'background:transparent;'
                                ]) ?>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-top">
                                <div class="text-muted small">Cidade</div>
                                <?= Html::textInput("Morada[$i][cidade]", ArrayHelper::getValue($morada, 'cidade', ''), [
                                        'class' => 'form-control border-0 border-bottom rounded-0 text-end', 'style' => 'background:transparent;'
                                ]) ?>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-top">
                                <div class="text-muted small">Localidade</div>
                                <?= Html::textInput("Morada[$i][localidade]", ArrayHelper::getValue($morada, 'localidade', ''), [
                                        'class' => 'form-control border-0 border-bottom rounded-0 text-end', 'style' => 'background:transparent;'
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between py-2 border-top">
                                <div class="text-muted small">Nº Porta</div>
                                <?= Html::textInput("Morada[$i][nporta]", ArrayHelper::getValue($morada, 'nporta', ''), [
                                        'class' => 'form-control border-0 border-bottom rounded-0 text-end', 'style' => 'background:transparent;'
                                ]) ?>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-top">
                                <div class="text-muted small">Código Postal</div>
                                <?= Html::textInput("Morada[$i][cdpostal]", ArrayHelper::getValue($morada, 'cdpostal', ArrayHelper::getValue($morada, 'codpostal', '')), [
                                        'class' => 'form-control border-0 border-bottom rounded-0 text-end', 'style' => 'background:transparent;'
                                ]) ?>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-top">
                                <div class="text-muted small">Cx Postal</div>
                                <?= Html::textInput("Morada[$i][cxpostal]", ArrayHelper::getValue($morada, 'cxpostal', ArrayHelper::getValue($morada, 'cx', '')), [
                                        'class' => 'form-control border-0 border-bottom rounded-0 text-end', 'style' => 'background:transparent;'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-success btn-sm rounded-pill px-3" id="add-morada">+ Adicionar Morada</button>
    </div>

    <hr class="my-3">

    <!-- Outros -->
    <div class="mb-4">
        <div class="fw-semibold text-success mb-2">Outros</div>
        <div class="small text-muted">Data de criação</div>
        <div class="fw-medium"><?= Html::encode(Yii::$app->formatter->asDate($model->created_at ?? $user->created_at ?? null, 'long')) ?></div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <?= Html::a('Cancelar', ['/userprofile/view'], ['class' => 'btn btn-outline-secondary rounded-pill px-4']) ?>

        <?= Html::submitButton('Salvar Alterações', ['class' => 'btn btn-success rounded-pill px-4']) ?>
    </div>

<?php ActiveForm::end(); ?>

