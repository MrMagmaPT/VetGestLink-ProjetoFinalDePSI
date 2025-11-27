<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var \yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var array $moradas */

$user = $user ?? ($model->user ?? null);
$moradas = $moradas ?? ($model->moradas ?? []);

$form = ActiveForm::begin([
    'id' => 'userprofile-form',
    'options' => ['enctype' => 'multipart/form-data'],
]);

// avatar: foto do model > foto do user > default
$currentPhoto = $model->foto ?: ($user->userprofile->foto ?? null);
if ($currentPhoto) {
    $storedPath = (strpos($currentPhoto, '/') !== false) ? $currentPhoto : (Yii::$app->has('imageUploader') ? trim(Yii::$app->imageUploader->subdir, '/\\') . '/' . $currentPhoto : $currentPhoto);
}
if (Yii::$app->has('imageUploader')) {
    $avatarUrl = Yii::$app->imageUploader->getUrl($storedPath ?? (trim(Yii::$app->imageUploader->subdir, '/\\') . '/default.jpg'));
} else {
    $avatarUrl = '/2_ano_1_semestre/Projeto/vetgestlink/backend/web/uploads/users/' . ltrim($currentPhoto ?? 'default.jpg', '/\\');
}
?>

<div class="userprofile-form">
    <div class="row mb-3">
        <div class="col-auto">
            <?= Html::img($avatarUrl, ['id' => 'avatar-preview', 'style' => 'width:80px;height:80px;object-fit:cover;border-radius:50%;border:1px solid #ddd;']) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'imageFile')->fileInput(['accept' => 'image/png, image/jpeg'])->label('Fotografia de Perfil (opcional)') ?>
            <div id="imageFile-client-error" class="text-danger small" style="display:none;margin-top:.25rem;"></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6"><?= $form->field($model, 'nomecompleto')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'telemovel')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'nif')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'dtanascimento')->input('date') ?></div>
    </div>
    <hr class="my-4 ">
    <div id="moradas-list">
        <?php foreach ($moradas as $i => $morada): ?>
            <div class="morada-item mb-3 border-bottom pb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Morada <?= $i === 0 ? 'Principal' : ($i + 1) ?></strong>
                    <?= Html::hiddenInput("Morada[$i][id]", ArrayHelper::getValue($morada, 'id', '')) ?>
                    <div>
                        <?php $mid = ArrayHelper::getValue($morada, 'id', null); ?>
                        <?php if ($mid): ?>
                            <?= Html::a('Editar', ['userprofile/add-morada', 'id' => $mid], ['class' => 'btn btn-sm btn-outline-primary me-2']) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-6"><?= Html::textInput("Morada[$i][rua]", ArrayHelper::getValue($morada, 'rua', ''), ['class' => 'form-control', 'placeholder' => 'Rua', 'readonly' => true]) ?></div>
                    <div class="col-md-3"><?= Html::textInput("Morada[$i][nporta]", ArrayHelper::getValue($morada, 'nporta', ''), ['class' => 'form-control', 'placeholder' => 'Nº Porta', 'readonly' => true]) ?></div>
                    <div class="col-md-3"><?= Html::textInput("Morada[$i][andar]", ArrayHelper::getValue($morada, 'andar', ''), ['class' => 'form-control', 'placeholder' => 'Andar', 'readonly' => true]) ?></div>
                    <div class="col-md-4 mt-2"><?= Html::textInput("Morada[$i][cdpostal]", ArrayHelper::getValue($morada, 'cdpostal', ''), ['class' => 'form-control', 'placeholder' => 'Código Postal', 'readonly' => true]) ?></div>
                    <div class="col-md-4 mt-2"><?= Html::textInput("Morada[$i][cidade]", ArrayHelper::getValue($morada, 'cidade', ''), ['class' => 'form-control', 'placeholder' => 'Cidade', 'readonly' => true]) ?></div>
                    <div class="col-md-4 mt-2"><?= Html::textInput("Morada[$i][cxpostal]", ArrayHelper::getValue($morada, 'cxpostal', ''), ['class' => 'form-control', 'placeholder' => 'Cx Postal', 'readonly' => true]) ?></div>
                    <div class="col-md-4 mt-2"><?= Html::textInput("Morada[$i][localidade]", ArrayHelper::getValue($morada, 'localidade', ''), ['class' => 'form-control', 'placeholder' => 'Localidade', 'readonly' => true]) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mb-3">
        <?= Html::a('+ Adicionar Morada', ['userprofile/add-morada'], ['class' => 'btn btn-sm btn-success']) ?>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <?= Html::a('Cancelar', ['/userprofile/view'], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton('Salvar Alterações', ['class' => 'btn btn-success']) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>
