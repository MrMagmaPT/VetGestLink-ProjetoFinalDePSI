<?php
namespace frontend\controllers;

use Yii;
use common\models\Animal;
use backend\models\AnimalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AnimalController implements the CRUD actions for Animal model.
 */
class AnimalController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index','view'],
                            'allow' => true,
                            'roles' => ['viewAnimals'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Animal models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Obtém o ID do usuário logado
        $userId = Yii::$app->user->identity->id ?? null;
        
        // Buscar o Userprofile do usuário logado
        $userprofile = \common\models\Userprofile::findOne(['user_id' => $userId, 'eliminado' => 0]);
        
        if (!$userprofile) {
            Yii::$app->session->setFlash('error', 'Perfil de usuário não encontrado.');
            return $this->render('index', ['animaisUsuario' => []]);
        }

        // Buscar animais do usuário através do AnimalSearch do backend
        $animaisUsuario = AnimalSearch::getByUserId($userprofile->id);

        // Renderizar a view
        return $this->render('index', [
            'animaisUsuario' => $animaisUsuario,
        ]);
    }

    /**
     * Displays a single Animal model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Buscar o modelo do animal
        $animal = $this->findModel($id);

        //notas já vem ordenado pelo mais recente
        //é só buscar o primeiro do array
        $latestNota = !empty($animal->notas) ? $animal->notas[0] : null;

        return $this->render('view', [
            'animal' => $animal,
            'latestNota' => $latestNota,
        ]);
    }

    /**
     * Finds the Animal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Animal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        // Buscar o Userprofile do usuário logado
        $userId = Yii::$app->user->identity->id ?? null;
        $userprofile = \common\models\Userprofile::findOne(['user_id' => $userId, 'eliminado' => 0]);
        
        if (!$userprofile) {
            throw new NotFoundHttpException('Perfil de usuário não encontrado.');
        }
        
        // Buscar o animal apenas se pertencer ao usuário logado
        if (($model = Animal::findOne(['id' => $id, 'userprofiles_id' => $userprofile->id, 'eliminado' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
