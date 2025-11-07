<?php

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var common\models\User $user */
/** @var common\models\Morada[] $moradas */

use yii\helpers\Html;

$this->title = 'Perfil';
$this->params['breadcrumbs'][] = $this->title;

// Register CSS
$this->registerCssFile('@web/static/css/custom-variables.css', ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerCssFile('@web/static/css/userprofile.css', ['depends' => [\yii\web\YiiAsset::class]]);
?>

<div class="userprofile-view container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <!-- MAIN CARD -->
            <div class="card shadow border-0 rounded-4">

                <!-- HEADER -->
                <div class="card-header card-header-accent text-white text-center rounded-top-4 pb-5 position-relative">
                    <h3 class="mt-3 mb-0"><?= Html::encode($user->username) ?></h3>
                    <h3 class="mt-3 mb-0"><?= Html::encode($user->email) ?></h3>
                </div>

                <!-- AVATAR -->
                <div class="text-center">
                    <img src="/img/avatar.png" class="profile-avatar" alt="Avatar">
                </div>

                <!-- BODY -->
                <div class="card-body p-4">

                    <!-- INFO GROUP -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 accent-border">
                            <div class="profile-label">Nome Completo</div>
                            <div class="profile-value"><?= Html::encode($model->nomecompleto) ?></div>
                        </div>

                        <div class="col-md-6 mb-3 accent-border">
                            <div class="profile-label">NIF</div>
                            <div class="profile-value"><?= Html::encode($model->nif) ?></div>
                        </div>
                        <div class="col-md-6 mb-3 accent-border">
                            <div class="profile-label">Telemóvel</div>
                            <div class="profile-value"><?= Html::encode($model->telemovel) ?></div>
                        </div>
                    </div>

                    <!-- MORADA -->
                    <h5 class="fw-bold mb-3 accent-title">
                        <i class="bi bi-geo-alt-fill me-1"></i>Morada
                    </h5>

                    <?php if (!empty($moradas)): ?>
                        <?php foreach ($moradas as $morada): ?>
                            <div class="border rounded p-3 mb-4 bg-light">

                                <div class="row mb-2">
                                    <div class="col-6 profile-label">Rua</div>
                                    <div class="col-6 profile-value"><?= Html::encode($morada->rua) ?></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-6 profile-label">Nº Porta</div>
                                    <div class="col-6 profile-value"><?= Html::encode($morada->nporta) ?></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-6 profile-label">Andar</div>
                                    <div class="col-6 profile-value"><?= Html::encode($morada->andar ?: "-") ?></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-6 profile-label">Código Postal</div>
                                    <div class="col-6 profile-value"><?= Html::encode($morada->cdpostal) ?></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-6 profile-label">Cidade</div>
                                    <div class="col-6 profile-value"><?= Html::encode($morada->cidade) ?></div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-6 profile-label">Cx Postal</div>
                                    <div class="col-6 profile-value"><?= Html::encode($morada->cxpostal ?: "-") ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-6 profile-label">Localidade</div>
                                    <div class="col-6 profile-value"><?= Html::encode($morada->localidade) ?></div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted fst-italic">Sem moradas registadas</p>
                    <?php endif; ?>


                    <!-- OTHER INFO -->
                    <h5 class="fw-bold mb-3 accent-title">
                        <i class="bi bi-info-circle me-1"></i>Outros
                    </h5>
                    <div class="row accent-border">
                        <div class="col-md-12">
                            <div class="profile-label">Data de criação</div>
                            <div class="profile-value">
                                <?= Html::encode(Yii::$app->formatter->asDate($user->created_at, 'long')) ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="card-footer text-end bg-light rounded-bottom-4">
                    <?= Html::a('Editar Perfil', ['userprofile/update'], ['class' => 'btn edit-button']) ?>
                </div>

            </div>
        </div>
    </div>
</div>

