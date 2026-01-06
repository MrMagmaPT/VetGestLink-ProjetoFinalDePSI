<?php

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var common\models\Morada[] $moradas */
/** @var yii\widgets\ActiveForm $form */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="userprofile-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'needs-validation'
            
        ],
        'fieldConfig' => [
                    'errorOptions' => ['class' => 'text-danger'],
                ],
    ]); ?>

    <div class="row">
        <!-- Coluna Esquerda -->
        <div class="col-md-8">
            <!-- Card Informações Pessoais -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user text-primary"></i>
                        Informações Pessoais
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                    $roles = Yii::$app->authManager->getRolesByUser($model->user_id);
                    $roleName = $roles ? array_keys($roles)[0] : 'cliente';
                    $allRoles = Yii::$app->authManager->getRoles();
                    $roleItems = [];
                    foreach ($allRoles as $key => $role) {
                        $roleItems[$key] = ucfirst($role->name);
                    }
                    ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label"><i class="fas fa-user-shield"></i> Perfil de Acesso</label>
                            <?php if (Yii::$app->user->can('admin')): ?>
                                <?= Html::dropDownList(
                                    'role',
                                    Yii::$app->request->post('role', $roleName),
                                    $roleItems,
                                    [
                                        'class' => 'form-control',
                                        'prompt' => 'Selecione a Role',
                                        'name' => 'role',
                                    ]
                                ) ?>
                            <?php else: ?>
                                <input type="text" class="form-control" value="<?= ucfirst($roleName) ?>" readonly />
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'nomecompleto')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'Digite o nome completo',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-user-circle"></i> Nome Completo') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'nif')->textInput([
                                'maxlength' => 9,
                                'placeholder' => '123456789',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-id-card"></i> NIF') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'telemovel')->textInput([
                                'maxlength' => 9,
                                'placeholder' => '910000000',
                                'class' => 'form-control'
                            ])->label('<i class="fas fa-phone"></i> Telemóvel') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Morada -->
            <?php if (!empty($moradas)): ?>
                <?php foreach ($moradas as $i => $morada): ?>
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                Morada <?= $i + 1 ?>
                            </h5>
                            <div>
                                <?= Html::radio('morada_principal', isset($morada->principal) ? $morada->principal : ($i === 0), [
                                    'value' => $i,
                                    'label' => 'Principal',
                                    'labelOptions' => ['class' => 'ms-2 mb-0'],
                                    'id' => 'morada-principal-' . $i,
                                    'class' => 'form-check-input',
                                ]) ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <?= $form->field($morada, "[$i]rua")->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'Ex: Rua das Flores',
                                        'class' => 'form-control'
                                    ])->label('<i class="fas fa-road"></i> Rua') ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($morada, "[$i]nporta")->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'Nº',
                                        'class' => 'form-control'
                                    ])->label('<i class="fas fa-door-open"></i> Nº Porta') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <?= $form->field($morada, "[$i]andar")->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'Ex: 2º',
                                        'class' => 'form-control'
                                    ])->label('<i class="fas fa-building"></i> Andar') ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($morada, "[$i]cdpostal")->textInput([
                                        'maxlength' => true,
                                        'placeholder' => '0000-000',
                                        'class' => 'form-control'
                                    ])->label('<i class="fas fa-mail-bulk"></i> Cód. Postal') ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($morada, "[$i]cxpostal")->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'Cx. Postal',
                                        'class' => 'form-control'
                                    ])->label('<i class="fas fa-inbox"></i> Cx. Postal') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($morada, "[$i]cidade")->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'Ex: Lisboa',
                                        'class' => 'form-control'
                                    ])->label('<i class="fas fa-city"></i> Cidade') ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($morada, "[$i]localidade")->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'Ex: Benfica',
                                        'class' => 'form-control'
                                    ])->label('<i class="fas fa-location-dot"></i> Localidade') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Coluna Direita -->
        <div class="col-md-4">
            <!-- Card Foto de Perfil -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-camera text-success"></i>
                        Foto de Perfil
                    </h5>
                </div>
                <div class="card-body text-center">
                    <?php \yii\widgets\Pjax::begin(['id' => 'userprofile-image-pjax', 'enablePushState' => false]); ?>
                        <div class="mb-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px;">
                            <?php if ($model->getImageUrl()): ?>
                                <img id="userprofile-image-preview" src="<?= $model->getImageUrl() ?>" alt="<?= $model->nomecompleto ?>" class="img-thumbnail rounded" style="max-width: 100%; height: auto; max-height: 300px;" />
                            <?php else: ?>
                                <img id="userprofile-image-preview" src="" alt="Preview" class="img-thumbnail rounded" style="max-width: 100%; height: auto; max-height: 300px; display: none;" />
                                <i class="fas fa-user-circle text-muted" style="font-size: 120px;"></i>
                            <?php endif; ?>
                        </div>
                    <p class="text-muted small">
                        <i class="fas fa-info-circle"></i>
                        Carregue uma nova imagem para substituir
                    </p>
                    <?= $form->field($model, 'imageFile')->fileInput([
                        'accept' => 'image/*',
                        'class' => 'form-control',
                        'data-image-preview' => 'userprofile-image-preview',
                    ])->label('Selecionar Imagem') ?>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Formatos: JPG, PNG, GIF (máx. 2MB)
                    </small>
                            <?php if ($model->getImageUrl() && !$model->isDefaultImage()): ?>
                            <?php \yii\widgets\Pjax::begin(['id' => 'remove-image-pjax', 'enablePushState' => false]); ?>
                            <?= Html::a(
                                '<i class="fas fa-trash"></i> Remover Imagem',
                                ['remove-image', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-danger btn-md',
                                    'data-confirm' => 'Tem certeza que deseja remover a imagem de perfil?',
                                    'data-method' => 'post',
                                    'data-pjax' => 1,
                                ]
                            ) ?>
                            <?php \yii\widgets\Pjax::end(); ?>
                        <?php endif; ?>
                    <?php \yii\widgets\Pjax::end(); ?>
                </div>
            </div>
            <!-- Card Ações -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cog"></i>
                        Ações
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= Html::submitButton(
                            '<i class="fas fa-save"></i> Guardar Alterações',
                            ['class' => 'btn btn-primary btn-md me-2']
                        ) ?>
                        <?= Html::a(
                            '<i class="fas fa-times"></i> Cancelar',
                            ['view', 'id' => $model->id],
                            ['class' => 'btn btn-secondary btn-md me-2']
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
