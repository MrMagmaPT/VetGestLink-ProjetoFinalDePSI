<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception*/

use yii\helpers\Html;

$this->title = $name;
?>

<?php if ($exception->statusCode === 403): ?>
    <div class="site-index">
        <div class="jumbotron text-center bg-transparent mt-5 mb-5">
            <h1 class="display-1"><i class="fa fa-lock"></i></h1>
            <h2 class="display-4">Acesso Negado</h2>
            <p class="lead">Não tem permissão para aceder a esta página.</p>

            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div class="small-box bg-yellow">
                        <div class="p-4">
                            <i class="fas fa-exclamation-triangle"></i><br>
                            <strong>Permissões Insuficientes:</strong> <br>A ação que tentou realizar requer permissões adicionais que não possui atualmente.
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-4">
                Se acredita que isto é um erro, por favor contacte o administrador do sistema.
            </p>
            <p class="mt-4">
                <?= Html::a('Voltar ao Painel Principal', ['/site/index'], ['class' => 'btn btn-lg btn-primary']) ?>
            </p>
        </div>
    </div>
<?php else: ?>
    <div class="site-error">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>

        <p>
            The above error occurred while the Web server was processing your request.
        </p>
        <p>
            Please contact us if you think this is a server error. Thank you.
        </p>
    </div>
<?php endif; ?>
