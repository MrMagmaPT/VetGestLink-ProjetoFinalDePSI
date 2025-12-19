<?php
namespace frontend\controllers;

use common\models\Animal;
use common\models\Nota;
use Yii;
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
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Nota models for a specific Animal.
     * @param int $animal_id Animal ID
     * @return string
     */
    public function actionIndex()
    {
        // Pegar o ID do animal a partir dos parâmetros da requisição
        $animalId = Yii::$app->request->get('animalId');

        //Encontrar o animal
        $animal = Animal::findOne($animalId);
        
        // Verificar se o animal existe
        if (!$animal) {
            Yii::$app->session->setFlash('danger', 'Animal não encontrado.');
            return $this->render('/animal/index', [
                'error' => 'Animal não encontrado.'
            ]);
        }

        // Pegar todas as notas associadas ao animal
        $allnotas = $animal->notas;

        return $this->render('index', [
            'animal' => $animal,
            'allnotas' => $allnotas,
        ]);
    }

    /**
     * Creates a new Nota model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($animalId)
    {
        $model = new Nota();
        $model->animais_id = $animalId;
        $model->userprofiles_id = Yii::$app->user->identity->userprofile->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Nota criada com sucesso.');
            return $this->redirect(['nota/view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Nota model.
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
