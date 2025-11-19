<?php

namespace frontend\controllers;

use common\models\Animal;
use common\models\Nota;
use Yii;
use yii\data\ActiveDataProvider;
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
        $dataProvider = new ActiveDataProvider([
            'query' => Animal::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
        if (($model = Animal::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreateNota($animal_id)
    {
        $model = new Nota();
        $model->animais_id = $animal_id;
        $model->userprofiles_id = Yii::$app->user->identity->userProfile->id;
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Nota criada com sucesso.');
            return $this->redirect(['animal/index', 'id' => $animal_id]);
        }

        return $this->render('createNota', [
            'model' => $model,
        ]);
    }

    public function actionViewAnimalDetails($id)
    {
        $model = $this->findModel($id);

        // Fetch the latest Nota for this animal
        $latestNota = Nota::find()
            ->where(['animais_id' => $id])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();

        return $this->renderAjax('viewAnimalDetails', [
            'model' => $model,
            'latestNota' => $latestNota,
        ]);
    }

    public function actionViewNotas($animal_id)
    {
        $model = $this->findModel($animal_id);
        $allnotas = Nota::find()
            ->where(['animais_id' => $animal_id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('viewNotas', [
            'model' => $model,
            'allnotas' => $allnotas,
        ]);
    }


}
