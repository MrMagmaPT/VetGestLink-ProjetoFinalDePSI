<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use frontend\widgets\ImageUploadWidget;


/** @var \yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var array $moradas */

// ID do utilizador
$userId = $model->id;

// URL da imagem (o método getImageUrl do modelo trata do default)
$avatarUrl = $model->getImageUrl();
?>
        <div class="card shadow-sm">
            <?php $form = ActiveForm::begin([
                'id' => 'userprofile-form',
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

            <!-- header -->
            <div class="text-white p-4 d-flex align-items-center" style="background: linear-gradient(90deg, rgb(76, 184, 138) 0%, rgb(148, 226, 182) 100%);">  
            <div class="me-3">
                    <!-- Foto de perfil -->
                    <?= ImageUploadWidget::widget([
                        'model' => $model,
                        'attribute' => 'imageFile',
                        'form' => $form,
                        'previewId' => 'image-preview-signup',
                        'defaultImage' => Yii::getAlias('@uploadsUrl') . '/users/default.jpg',
                        'imageSize' => 80,
                        'borderColor' => '#28a745',
                    ]) ?>
                </div>
                <div class="flex-grow-1">
                    <div class="h5 mb-2 text-white">Editar Perfil</div>
                </div>
            </div>

            <!-- body -->
            <div class="p-4">
                <!-- Informações de Contato -->
                <div class="mb-4">
                    <div class="text-success fw-semibold mb-3">Informações de Contato</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="text-muted me-2 mt-1"><i class="fa-solid fa-user text-success"></i></div>
                                <div class="flex-grow-1">
                                    <?= $form->field($model, 'nomecompleto')->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'Nome Completo'
                                    ])->label('Nome Completo', ['class' => 'small text-muted']) ?>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="text-muted me-2 mt-1"><i class="fa-regular fa-address-card text-success"></i></div>
                                <div class="flex-grow-1">
                                    <?= $form->field($model, 'nif')->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'NIF'
                                    ])->label('NIF', ['class' => 'small text-muted']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="text-muted me-2 mt-1"><i class="fa-solid fa-phone text-success"></i></div>
                                <div class="flex-grow-1">
                                    <?= $form->field($model, 'telemovel')->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'Telemóvel'
                                    ])->label('Telefone', ['class' => 'small text-muted']) ?>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="text-muted me-2 mt-1"><i class="fa-regular fa-calendar text-success"></i></div>
                                <div class="flex-grow-1">
                                    <?= $form->field($model, 'dtanascimento')->input('date', [
                                        'class' => 'form-control'
                                    ])->label('Data de Nascimento', ['class' => 'small text-muted']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Moradas -->
                <div class="mb-4">
                    <div class="text-success fw-semibold mb-3">Moradas</div>
                    
                    <?php Pjax::begin(['id' => 'pjax-moradas', 'timeout' => 5000]); ?>
                        <?= $this->render('moradas_list', ['moradas' => $moradas]) ?>
                    <?php Pjax::end(); ?>

                    <div class="mt-3">
                        <?= Html::a('<i class="fa-solid fa-plus"></i> Adicionar Morada', ['morada/create', 'userId' => $userId], [
                            'class' => 'btn btn-outline-success btn-sm rounded-pill'
                        ]) ?>
                    </div>
                </div>
                <!-- Outros -->
                <div>
                    <div class="text-success fw-semibold mb-2">Outros</div>
                    <div class="small text-muted">Data de criação</div>
                    <div class="fw-medium">
                        <?= Html::encode($model->getCreatedAt('Y-m-d')) ?>
                    </div>
                </div>
            </div>
            <!-- footer -->
            <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2">
                <?= Html::a('Cancelar', ['/userprofile/view'], ['class' => 'btn btn-outline-secondary rounded-pill']) ?>
                <?= Html::submitButton('Salvar Alterações', ['class' => 'btn btn-success rounded-pill']) ?>
                <?php if (!empty($model->foto)): ?>
                    <?= Html::a('Remover Foto', ['userprofile/remove-photo'], [
                        'class' => 'btn btn-outline-danger rounded-pill',
                        'data' => [
                            'method' => 'post',
                            'confirm' => 'Tem a certeza que pretende remover a sua foto de perfil?'
                        ]
                    ]) ?>
                <?php endif; ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


