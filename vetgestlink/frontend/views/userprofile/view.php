<?php
// php
// File: frontend/views/userprofile/view.php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Userprofile $model */
/** @var common\models\User|null $user */
/** @var common\models\Morada[]|array|null $moradas */

$this->title = 'Perfil';
$this->params['breadcrumbs'][] = $this->title;

// fallbacks
$moradas = $moradas ?? ($model->moradas ?? []);
$user = $user ?? ($model->user ?? null);
$addr = !empty($moradas) ? ArrayHelper::getValue($moradas, 0, $model) : $model;
$editId = $model->id ?? $user->id ?? null;

// avatar: inicial caso não exista imagem
$initials = '';
if (!empty($model->nomecompleto)) {
    $parts = preg_split('/\s+/', trim($model->nomecompleto));
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
} else {
    $initials = strtoupper(substr($user->username ?? 'U', 0, 1));
}
?>
<div class="container py-5">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm">
            <!-- header -->
            <div class="text-white p-4 d-flex align-items-center" style="background: linear-gradient(90deg, rgb(76, 184, 138) 0%, rgb(148, 226, 182) 100%);">
                <div class="me-3">
                    <div class="bg-white text-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width:72px;height:72px;font-weight:700;font-size:20px;">
                        <?= Html::encode($initials) ?>
                    </div>
                </div>
                <div>
                    <div class="h5 mb-0"><?= Html::encode($model->nomecompleto ?? $user->username ?? 'Sem nome') ?></div>
                    <div class="small opacity-90"><?= Html::encode($user->email ?? '-') ?></div>
                </div>
            </div>

                <!-- body -->
                <div class="p-4">
                    <!-- Informações de Contato -->
                    <div class="mb-4">
                        <div class="text-success fw-semibold mb-3">Informações de Contato</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="text-muted me-2 small align-self-start "><i class="fa-regular fa-envelope text-success"></i></div>
                                    <div>
                                        <div class="small text-muted">Email</div>
                                        <div class="fw-medium"><?= Html::encode($user->email ?? '-') ?></div>
                                    </div>
                                </div>

                                <!-- NIF -->
                                <div class="d-flex mb-3">
                                    <div class="text-muted me-2 small align-self-start"><i class="fa-regular fa-address-card text-success"></i></div>
                                    <div>
                                        <div class="small text-muted">NIF</div>
                                        <div class="fw-medium"><?= Html::encode($model->nif ?? '-') ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- TELEMOVEL -->
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="text-muted me-2 small align-self-start"><i class="fa-solid fa-phone text-success"></i></div>
                                    <div>
                                        <div class="small text-muted">Telefone</div>
                                        <div class="fw-medium"><?= Html::encode($model->telemovel ?? '-') ?></div>
                                    </div>
                                </div>
                                <!-- DTANASCIMENTO -->
                                <div class="d-flex mb-3">
                                    <div class="text-muted me-2 small align-self-start"><i class="fa-regular fa-calendar text-success"></i></div>
                                    <div>
                                        <div class="small text-muted">Data de Nascimento</div>
                                        <div class="fw-medium"><?= Html::encode($model->dtanascimento ? Yii::$app->formatter->asDate($model->dtanascimento, 'dd/MM/yyyy') : '-') ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Moradas -->
                    <div class="mb-4">
                        <div class="text-success fw-semibold mb-3">Moradas</div>
                        <div class="mb-2 fw-semibold">Morada Principal</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between py-2 border-top">
                                    <div class="text-muted small">Rua</div>
                                    <div><?= Html::encode(ArrayHelper::getValue($addr, 'rua', '-')) ?></div>
                                </div>
                                <div class="d-flex justify-content-between py-2 border-top">
                                    <div class="text-muted small">Andar</div>
                                    <div><?= Html::encode(ArrayHelper::getValue($addr, 'andar', '-')) ?></div>
                                </div>
                                <div class="d-flex justify-content-between py-2 border-top">
                                    <div class="text-muted small">Cidade</div>
                                    <div><?= Html::encode(ArrayHelper::getValue($addr, 'cidade', '-')) ?></div>
                                </div>
                                <div class="d-flex justify-content-between py-2 border-top">
                                    <div class="text-muted small">Localidade</div>
                                    <div><?= Html::encode(ArrayHelper::getValue($addr, 'localidade', '-')) ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex justify-content-between py-2 border-top">
                                    <div class="text-muted small">Nº Porta</div>
                                    <div><?= Html::encode(ArrayHelper::getValue($addr, 'nporta', '-')) ?></div>
                                </div>
                                <div class="d-flex justify-content-between py-2 border-top">
                                    <div class="text-muted small">Código Postal</div>
                                    <div><?= Html::encode(ArrayHelper::getValue($addr, 'cdpostal', ArrayHelper::getValue($addr, 'codpostal', '-'))) ?></div>
                                </div>
                                <div class="d-flex justify-content-between py-2 border-top">
                                    <div class="text-muted small">Cx Postal</div>
                                    <div><?= Html::encode(ArrayHelper::getValue($addr, 'cxpostal', ArrayHelper::getValue($addr, 'cx', '-'))) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Outros -->
                    <div>
                        <div class="text-success fw-semibold mb-2">Outros</div>
                        <div class="small text-muted">Data de criação</div>
                        <div class="fw-medium"><?= Html::encode(Yii::$app->formatter->asDate($model->created_at ?? $user->created_at ?? null, 'long')) ?></div>
                    </div>
                </div>

                <!-- footer -->
                <div class="card-footer bg-white border-0 d-flex justify-content-end">
                    <?= $editId ? Html::a('Editar Perfil', ['userprofile/update', 'id' => $editId], ['class' => 'btn btn-dark rounded-pill']) : '' ?>
                </div>
            </div>
        </div>
    </div>
</div>
