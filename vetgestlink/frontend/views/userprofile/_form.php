<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/** @var \yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var array $moradas */

$user = $user ?? ($model->user ?? null);
$moradas = $moradas ?? ($model->moradas ?? []);

// Iniciar o ActiveForm com enctype para upload de arquivos
$form = ActiveForm::begin([
    'id' => 'userprofile-form',
    'options' => ['enctype' => 'multipart/form-data'],
]);

// avatar: usa o componente imageUploader para construir a URL (o componente já tem defaultImage)
if (Yii::$app->has('imageUploader')) {
    // $model->foto contém apenas o filename ou null
    $avatarUrl = Yii::$app->imageUploader->getUrl($model->foto);
} else {
    // fallback: usar alias @uploadsUrl (deve ser configurado em common/config/main.php)
    $currentPhoto = $model->foto ?: ($user->userprofile->foto ?? null);
    $uploadsUrl = null;
    try {
        $uploadsUrl = Yii::getAlias('@uploadsUrl');
    } catch (\Exception $e) {
        $uploadsUrl = '/backend/web/uploads';
    }
    $avatarUrl = rtrim($uploadsUrl, '/') . '/users/' . ltrim($currentPhoto ?? 'default.jpg', '/\\');
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

    <?php Pjax::begin(['id' => 'pjax-moradas', 'timeout' => 5000]); ?>
        <?= $this->render('_moradas_list', ['moradas' => $moradas]) ?>
    <?php Pjax::end(); ?>

    <div class="mb-3">
        <?= Html::a('+ Adicionar Morada', ['userprofile/adicionar-morada'], ['class' => 'btn btn-sm btn-success']) ?>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <?= Html::a('Cancelar', ['/userprofile/view'], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton('Salvar Alterações', ['class' => 'btn btn-success']) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>
