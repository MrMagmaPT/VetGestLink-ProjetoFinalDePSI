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
 * NotaController implements the CRUD actions for Nota model.
 */
class NotaController extends Controller
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
     * Displays a single Nota model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionIndex($animal_id)
    {
        $model = $this->findModel($animal_id);

        $animal = Animal::findOne($animal_id);
        $allnotas = $animal->notas;

        return $this->render('index', [
            'model' => $model,
            'allnotas' => $allnotas,
        ]);
    }

    /**
     * Creates a new Nota model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($animal_id)
    {
        $model = new Nota();
        $model->animais_id = $animal_id;
        $model->userprofiles_id = Yii::$app->user->identity->userprofile->id;
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Nota criada com sucesso.');
            return $this->redirect(['animal/index', 'id' => $animal_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Nota model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Nota atualizada com sucesso.');
                return $this->redirect(['nota/index', 'animal_id' => $model->animais_id]);
            } else {
                Yii::$app->session->setFlash('danger', 'Erro ao atualizar a nota. Verifique os campos.');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing Nota model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->session->setFlash('success', 'Nota eliminada com sucesso.');
        $this->findModel($id)->delete();

        return $this->redirect(['nota/index','animal_id' => $model->animais_id]);
    }

    /**
     * Finds the Nota model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Nota the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Nota::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
