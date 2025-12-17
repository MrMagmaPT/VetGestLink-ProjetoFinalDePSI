<?php
namespace frontend\controllers;

use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Userprofile;
use common\models\Morada;
use yii\web\UploadedFile;

/**
 * Controller responsável pelo perfil do utilizador.
 *
 */
class UserprofileController extends Controller
{
    /**
     * Comportamentos do controller (acesso e verbos HTTP).
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'remove-photo' => ['POST'],
                        'remove-morada' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Mostra a página de perfil do utilizador.
     * Carrega as moradas e apresenta a view `view.php`.
     */
    public function actionView($id = null)
    {

        // id do utilizador: ou o passado por parâmetro, ou o atual autenticado
        $id = $id ?: Yii::$app->user->id;
        $user = User::find()->where(['id' => $id])->with(['userprofile.moradas'])->one();

        if (!$user) {
            throw new NotFoundHttpException('Utilizador não encontrado.');
        }

        // Obter perfil e moradas
        $profile = $user->userprofile;
        if ($profile === null) {
            $profile = new Userprofile();
            $profile->user_id = $user->id;
            $moradas = [];
        } else {
            $moradas = $profile->getMoradas()->all();
        }

        return $this->render('view', [
            'user' => $user,
            'model' => $profile,
            'moradas' => $moradas,
        ]);
    }

    /**
     * Atualiza o perfil do utilizador.
     * A função principal fica curta; validações e tarefas delegadas para helpers.
     */
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $model = $user->userprofile;
        $moradas = $model ? $model->getMoradas()->all() : [];

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                // Lidar com upload da imagem (se houver)
                if (!$this->handleImageUpload($model)) {
                    // Upload falhou: a função já definiu flash; reexibir form
                    return $this->render('update', ['model' => $model, 'moradas' => $moradas]);
                }

                try {
                    if (!$model->save(false)) {
                        throw new \Exception('Falha ao salvar perfil');
                    }

                    Yii::$app->session->setFlash('success', 'Perfil atualizado com sucesso.');
                    return $this->redirect(['view']);
                } catch (\Throwable $e) {
                    Yii::error('Userprofile update failed: ' . $e->getMessage());
                    Yii::$app->session->setFlash('error', 'Erro ao atualizar perfil.');
                    return $this->render('update', ['model' => $model, 'moradas' => $moradas]);
                }
            }
        }

        return $this->render('update', ['model' => $model, 'moradas' => $moradas]);
    }

    /**
     * Guarda o perfil (POST) — usado por endpoints que submetem o perfil.
     */
    public function actionSave()
    {
        $user = Yii::$app->user->identity;
        $model = $user->userprofile;

        // Verificar se o perfil existe
        if ($model === null) {
            throw new NotFoundHttpException('Perfil não encontrado.');
        }

        $moradas = $model->getMoradas()->where(['eliminado' => 0])->all() ?? [];

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if (!$this->handleImageUpload($model)) {
                return $this->render('update', ['model' => $model, 'moradas' => $moradas]);
            }

            try {
                if (!$model->save(false)) {
                    throw new \Exception('Falha ao salvar perfil');
                }

                Yii::$app->session->setFlash('success', 'Perfil editado com sucesso.');
            } catch (\Throwable $e) {
                Yii::error('Userprofile save failed: ' . $e->getMessage());
                Yii::$app->session->setFlash('error', 'Erro ao editar o perfil.');
            }
        }


        return $this->redirect(['view', 'id' => $user->id ?? null]);
    }

    /**
     * Remove a fotografia do perfil do utilizador (POST).
     * Apaga o ficheiro via imageUploader e limpa o campo na BD.
     */
    public function actionRemovePhoto()
    {
        $user = Yii::$app->user->identity;
        $model = $user->userprofile;

        if (!$model) {
            Yii::$app->session->setFlash('error', 'Perfil não encontrado.');
            return $this->redirect(['view']);
        }

        if (empty($model->foto)) {
            Yii::$app->session->setFlash('warning', 'O utilizador não tem foto.');
            return $this->redirect(['view']);
        }

        if (Yii::$app->has('imageUploader')) {
            try {
                Yii::$app->imageUploader->delete($model->foto);
            } catch (\Throwable $e) {
                Yii::error('RemovePhoto error: ' . $e->getMessage());
            }
        }

        $model->foto = null;
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Foto removida com sucesso.');
        } else {
            Yii::$app->session->setFlash('error', 'Erro ao atualizar perfil após remoção da foto.');
        }

        return $this->redirect(['update']);
    }

    // ----------------------- HELPERS PRIVADOS -----------------------

    /**
     * Trata o upload da imagem do perfil (se existir ficheiro enviado).
     * Retorna true se não houver ficheiro ou se o upload foi bem-sucedido.
     */
    private function handleImageUpload(Userprofile $model): bool
    {
        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
        if (!($model->imageFile instanceof UploadedFile)) {
            return true; // nada a fazer
        }

        // apenas validar o campo imageFile
        if (!$model->validate(['imageFile'])) {
            return false;
        }

        if (!$model->uploadImage()) {
            Yii::$app->session->setFlash('error', 'Falha ao gravar a imagem. Tente novamente.');
            return false;
        }

        return true;
    }
}
