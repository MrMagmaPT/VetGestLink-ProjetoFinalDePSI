<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\filters\auth\QueryParamAuth;
use common\models\Animal;
use common\models\Nota;

/**
 * Controller de Animais
 * Endpoints para gerenciar animais do cliente autenticado
 */
class AnimalController extends ActiveController
{
    public $modelClass = 'common\models\Animal';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];
        
        // Autenticação customizada
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
        ];

        // JSON response
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    //Tira as ações padrões do ActiveController (index, view, create, update, delete)
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }

    protected function getUserProfileId()
    {
        $user = Yii::$app->user->identity;
        if (!$user || !$user->userprofile) {
            throw new UnauthorizedHttpException('Usuário sem perfil associado');
        }
        return $user->userprofile->id;
    }

    /**
     * GET /animal/all
     * Lista todos os animais do cliente autenticado
     */
    public function actionAll()
    {
        $permission = Yii::$app->user->can('viewAnimals');

        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para visualizar animais.');
        }

        $userProfileId = $this->getUserProfileId();

        $animais = Animal::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->all();

        $result = [];
        foreach ($animais as $animal) {
            $idade = null;
            if ($animal->dtanascimento) {
                $nascimento = new \DateTime($animal->dtanascimento);
                $hoje = new \DateTime();
                $intervalo = $hoje->diff($nascimento);

                if ($intervalo->y >= 1) {
                    $idade = $intervalo->y . ($intervalo->y > 1 ? ' anos' : ' ano');
                } elseif ($intervalo->m >= 1) {
                    $idade = $intervalo->m . ($intervalo->m > 1 ? ' meses' : ' mês');
                } else {
                    $idade = $intervalo->d . ($intervalo->d > 1 ? ' dias' : ' dia');
                }
            }
            // Obter URL da foto com tratamento de erro
            $fotoUrl = null;
            try {
                $fotoUrl = $animal->getImageUrl();
            } catch (\Exception $e) {
                $fotoUrl = null;
            }

            $result[] = [
                'id' => $animal->id,
                'nome' => $animal->nome,
                'especie' => $animal->especies ? $animal->especies->nome : null,
                'especie_id' => $animal->especies_id,
                'raca' => $animal->racas ? $animal->racas->nome : null,
                'raca_id' => $animal->racas_id,
                'idade' => $idade,
                'peso' => (float)$animal->peso,
                'sexo' => $animal->sexo,
                'datanascimento' => $animal->dtanascimento,
                'microchip' => $animal->microship,
                'foto_url' => $fotoUrl,
                'userprofiles_id' => $animal->userprofiles_id,
                'created_at' => $animal->created_at,
                'updated_at' => $animal->updated_at,
                'ativo' => $animal->eliminado == 0,
            ];
        }

        return $result;
    }

    /**
     * GET /animal/view/{id}
     * Detalhes de um animal específico
     */
    public function actionView($id)
    {
        $permission = Yii::$app->user->can('viewAnimals');

        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para visualizar animais.');
        }
        
        $userProfileId = $this->getUserProfileId();

        $animal = Animal::find()
            ->where(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->one();

        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

       $idade = null;
        if ($animal->dtanascimento) {
            $nascimento = new \DateTime($animal->dtanascimento);
            $hoje = new \DateTime();
            $intervalo = $hoje->diff($nascimento);

            if ($intervalo->y >= 1) {
                $idade = $intervalo->y . ($intervalo->y > 1 ? ' anos' : ' ano');
            } elseif ($intervalo->m >= 1) {
                $idade = $intervalo->m . ($intervalo->m > 1 ? ' meses' : ' mês');
            } else {
                $idade = $intervalo->d . ($intervalo->d > 1 ? ' dias' : ' dia');
            }
        }

        // Buscar notas do animal
        $notas = [];
        foreach ($animal->notas as $nota) {
            $notas[] = [
                'id' => $nota->id,
                'texto' => $nota->nota,
                'created_at' => $nota->created_at,
                'autor' => $nota->userprofiles ? $nota->userprofiles->nomecompleto : 'N/A',
            ];
        }

        // Obter URL da foto com tratamento de erro
        $fotoUrl = null;
        try {
            $fotoUrl = $animal->getImageUrl();
        } catch (\Exception $e) {
            $fotoUrl = null;
        }

        return [
            'id' => $animal->id,
            'nome' => $animal->nome,
            'especie' => $animal->especies ? $animal->especies->nome : null,
            'especie_id' => $animal->especies_id,
            'raca' => $animal->racas ? $animal->racas->nome : null,
            'raca_id' => $animal->racas_id,
            'idade' => $idade,
            'peso' => (float)$animal->peso,
            'sexo' => $animal->sexo,
            'datanascimento' => $animal->dtanascimento,
            'microchip' => $animal->microship,
            'foto_url' => $fotoUrl,
            'notas' => $notas,
            'created_at' => $animal->created_at,
            'updated_at' => $animal->updated_at,
            'ativo' => $animal->eliminado == 0,
            'dono' => [
                'id' => $animal->userprofiles->id,
                'nomecompleto' => $animal->userprofiles->nomecompleto,
                'telemovel' => $animal->userprofiles->telemovel,
            ],
        ];
    }

    /**
     * GET /animal/notas/{id}
     * Lista notas de um animal específico
     */
    public function actionNotas($id)
    {
        $permission = Yii::$app->user->can('viewAnimals');
        
        // Verifica permissão
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para visualizar animais.');
        }

        $userProfileId = $this->getUserProfileId();

        // Verificar se o animal pertence ao usuário
        $animal = Animal::findOne(['id' => $id, 'userprofiles_id' => $userProfileId, 'eliminado' => 0]);
        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        $notas = Nota::find()
            ->where(['animais_id' => $id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $result = [];
        foreach ($notas as $nota) {
            $result[] = [
                'id' => $nota->id,
                'nota' => $nota->nota,
                'animais_id' => $nota->animais_id,
                'userprofiles_id' => $nota->userprofiles_id,
                'created_at' => $nota->created_at,
                'autor' => $nota->userprofiles ? $nota->userprofiles->nomecompleto : 'N/A',
            ];
        }

        return $result;
    }

    /**
     * GET /animal/count
     * Conta total de animais do cliente
     */
    public function actionCount()
    {
        $permission = Yii::$app->user->can('viewAnimals');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para visualizar animais.');
        }

        $userProfileId = $this->getUserProfileId();
        $count = Animal::find()
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->count();
        
        return ['count' => (int)$count];
    }

    /**
     * GET /animal/nomes
     * Lista apenas ID e nomes dos animais
     */
    public function actionNomes()
    {
        $permission = Yii::$app->user->can('viewAnimals');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para visualizar animais.');
        }

        $userProfileId = $this->getUserProfileId();
        $nomes = Animal::find()
            ->select(['id', 'nome'])
            ->where(['userprofiles_id' => $userProfileId, 'eliminado' => 0])
            ->asArray()
            ->all();
        
        return $nomes;
    }

    /**
     * GET /animal/microchip/{microchip}
     * Buscar animal por número de microchip
     */
    public function actionPormicrochip($microchip)
    {
        $permission = Yii::$app->user->can('viewAnimals');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para visualizar animais.');
        }

        $userProfileId = $this->getUserProfileId();
        $animal = Animal::find()
            ->where([
                'id' => $microchip,
                'userprofiles_id' => $userProfileId,
                'eliminado' => 0
            ])
            ->one();

        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        // microship: 1 se tem, 0 se não tem
        $temMicrochip = (!empty($animal->microship) && $animal->microship > 0) ? 1 : 0;

        return [
            'id' => $animal->id,
            'tem_microchip' => $temMicrochip,
        ];
    }

    /**
     * GET /animal/especie/{especie_id}
     * Lista animais por espécie
     */
    public function actionPorespecie($especie_id)
    {
        $permission = Yii::$app->user->can('viewAnimals');
        if (!$permission) {
            throw new UnauthorizedHttpException('Você não tem permissão para visualizar animais.');
        }

        $userProfileId = $this->getUserProfileId();
        $animais = Animal::find()
            ->where([
                'especies_id' => $especie_id,
                'userprofiles_id' => $userProfileId,
                'eliminado' => 0
            ])
            ->with(['racas'])
            ->all();

        $result = [];
        foreach ($animais as $animal) {
            $result[] = [
                'id' => $animal->id,
                'nome' => $animal->nome,
                'raca' => $animal->racas ? $animal->racas->nome : null,
                'peso' => (float)$animal->peso,
                'sexo' => $animal->sexo,
            ];
        }

        return $result;
    }
}


