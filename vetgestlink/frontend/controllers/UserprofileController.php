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
 * UserprofileController implements the CRUD actions for Userprofile model.
 */
class UserprofileController extends Controller
{
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
     * Displays the user profile view.
     *
     * @param int|null $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id = null)
    {
        $id = $id ?: Yii::$app->user->id;

        // Eager load userprofile e moradas (ajuste aqui se o nome da relação for diferente)
        $user = User::find()->where(['id' => $id])->with(['userprofile.moradas'])->one();

        if (!$user) {
            throw new NotFoundHttpException('Utilizador não encontrado.');
        }

        // garante que temos um Userprofile (pode ser null)
        $profile = $user->userprofile;
        if ($profile === null) {
            $profile = new Userprofile();
            $profile->user_id = $user->id;
            $moradas = [];
        } else {
            // buscar apenas moradas ativas (eliminado = 0)
            $moradas = $profile->getMoradas()->where(['eliminado' => 0])->all();
            // legacy fallbacks (se precisar)
            if (empty($moradas)) {
                if (isset($profile->enderecos) && is_array($profile->enderecos) && count($profile->enderecos)) {
                    foreach ($profile->enderecos as $e) {
                        if (isset($e->eliminado) && $e->eliminado == 0) $moradas[] = $e;
                    }
                } elseif (isset($profile->address) && is_array($profile->address) && count($profile->address)) {
                    foreach ($profile->address as $e) {
                        if (isset($e->eliminado) && $e->eliminado == 0) $moradas[] = $e;
                    }
                }
            }
        }

        return $this->render('view', [
            'user' => $user,
            'model' => $profile,
            'moradas' => $moradas,
        ]);
    }

    /**
     * Updates the user profile.
     *
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $model = $user->userprofile;
        $moradas = $model ? $model->getMoradas()->where(['eliminado' => 0])->all() : [];

        // handle POST
        if ($this->request->isPost) {
            // Support no-JS 'Remover' button: if remover_morada exists, force that Morada[...]..eliminado = 1
            $removeIndex = Yii::$app->request->post('remover_morada', null);
            if ($removeIndex !== null) {
                $post = Yii::$app->request->post();
                if (!isset($post['Morada'])) $post['Morada'] = [];
                $post['Morada'][$removeIndex]['eliminado'] = 1;
                // update body params so later ->post() reads the modified data
                if (method_exists(Yii::$app->request, 'setBodyParams')) {
                    Yii::$app->request->setBodyParams($post);
                } else {
                    $_POST = $post;
                }
            }

            if ($model->load(Yii::$app->request->post())) {
                // carregar ficheiro enviado (se houver)
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

                // validar apenas o ficheiro (se enviado)
                if ($model->imageFile instanceof UploadedFile) {
                    if (!$model->validate(['imageFile'])) {
                        // render com erros do ficheiro
                        return $this->render('update', [
                            'model' => $model,
                            'moradas' => $moradas,
                        ]);
                    }
                    // upload (apaga antigo, guarda novo nome em $model->foto)
                    if (!$model->uploadImage()) {
                        Yii::$app->session->setFlash('error', 'Falha ao gravar a imagem. Tente novamente.');
                        return $this->render('update', [
                            'model' => $model,
                            'moradas' => $moradas,
                        ]);
                    }
                }

                try {
                    if (!$model->save(false)) {
                        throw new \Exception('Falha ao salvar perfil');
                    }

                    // processar moradas postadas (nova função)
                    $this->savePostedMoradas($model);

                    Yii::$app->session->setFlash('success', 'Perfil atualizado com sucesso.');
                    return $this->redirect(['view']);
                } catch (\Throwable $e) {
                    Yii::error('Userprofile update failed: ' . $e->getMessage());
                    Yii::$app->session->setFlash('error', 'Erro ao atualizar perfil.');
                    return $this->render('update', [
                        'model' => $model,
                        'moradas' => $moradas,
                    ]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'moradas' => $moradas,
        ]);
    }

    /**
     * Saves the user profile (POST action).
     *
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSave()
    {
        $user = Yii::$app->user->identity;
        $model = $user->userprofile;
        if ($model === null) {
            throw new NotFoundHttpException('Perfil não encontrado.');
        }
        $moradas = $model->getMoradas()->where(['eliminado' => 0])->all() ?? [];

        if ($this->request->isPost) {
            // same no-JS remover handling as in actionUpdate
            $removeIndex = Yii::$app->request->post('remover_morada', null);
            if ($removeIndex !== null) {
                $post = Yii::$app->request->post();
                if (!isset($post['Morada'])) $post['Morada'] = [];
                $post['Morada'][$removeIndex]['eliminado'] = 1;
                if (method_exists(Yii::$app->request, 'setBodyParams')) {
                    Yii::$app->request->setBodyParams($post);
                } else {
                    $_POST = $post;
                }
            }

            if ($model->load(Yii::$app->request->post())) {
                // carregar ficheiro enviado
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

                if ($model->imageFile instanceof UploadedFile) {
                    if (!$model->validate(['imageFile'])) {
                        // validação falhou: renderizar update com erros
                        return $this->render('update', [
                            'model' => $model,
                            'moradas' => $moradas,
                        ]);
                    }

                    if (!$model->uploadImage()) {
                        Yii::$app->session->setFlash('error', 'Falha ao gravar a imagem. Tente novamente.');
                        return $this->render('update', [
                            'model' => $model,
                            'moradas' => $moradas,
                        ]);
                    }
                }

                try {
                    if (!$model->save(false)) {
                        throw new \Exception('Falha ao salvar perfil');
                    }
                    $this->savePostedMoradas($model);
                    Yii::$app->session->setFlash('success', 'Perfil editado com sucesso.');
                } catch (\Throwable $e) {
                    Yii::error('Userprofile save failed: ' . $e->getMessage());
                    Yii::$app->session->setFlash('error', 'Erro ao editar o perfil.');
                }
            }
        }
        // redirecionar para a view do utilizador atual explicitamente
        return $this->redirect(['view', 'id' => $user->id ?? null]);
    }

    /**
     * Remove user's profile photo.
     * POST only. Deletes the file and clears the DB column.
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

        // tenta apagar o ficheiro
        $deleted = false;
        if (Yii::$app->has('imageUploader')) {
            try {
                $deleted = Yii::$app->imageUploader->delete($model->foto);
            } catch (\Throwable $e) {
                Yii::error('RemovePhoto error: ' . $e->getMessage());
                $deleted = false;
            }
        }

        // limpa a coluna e salva
        $model->foto = null;
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Foto removida com sucesso.');
        } else {
            Yii::$app->session->setFlash('error', 'Erro ao atualizar perfil após remoção da foto.');
        }

        return $this->redirect(['view']);
    }

    /**
     * Finds the Userprofile model based on its primary key value.
     *
     * @param int $id
     * @return Userprofile
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Userprofile::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Process posted Morada data: create, update, mark removed.
     * Expects POST['Morada'] = array of arrays with keys [id, rua, nporta, andar, cdpostal, cxpostal, localidade, eliminado]
     * @param Userprofile $model
     */
    protected function savePostedMoradas(Userprofile $model)
    {
        $posted = Yii::$app->request->post('Morada', []);
        $postedIds = [];

        foreach ($posted as $i => $data) {
            // skip empty blocks when no id
            $hasAny = false;
            foreach (['rua','nporta','cdpostal','cidade','localidade'] as $k) {
                if (!empty($data[$k])) { $hasAny = true; break; }
            }
            if (empty($data['id']) && !$hasAny) continue;

            // existing morada update / mark eliminado
            if (!empty($data['id'])) {
                $m = Morada::findOne($data['id']);
                if ($m && $m->userprofiles_id == $model->id) {
                    // if client requested elimination, mark and continue
                    if (!empty($data['eliminado'])) {
                        $m->eliminado = 1;
                        $m->save(false);
                        continue;
                    }

                    // assign only allowed fields
                    $m->rua = $data['rua'] ?? $m->rua;
                    $m->nporta = $data['nporta'] ?? $m->nporta;
                    $m->andar = $data['andar'] ?? $m->andar;
                    $m->cdpostal = $data['cdpostal'] ?? $m->cdpostal;
                    $m->cidade = $data['cidade'] ?? $m->cidade;
                    $m->cxpostal = $data['cxpostal'] ?? $m->cxpostal;
                    $m->localidade = $data['localidade'] ?? $m->localidade;
                    $m->principal = isset($data['principal']) ? (int)$data['principal'] : ($m->principal ?? 0);
                    $m->eliminado = 0;
                    $m->save(false);
                    $postedIds[] = $m->id;
                }
            } else {
                // new morada: if eliminado flag is set, skip creation (user clicked Remover on empty/new block)
                if (!empty($data['eliminado'])) {
                    continue;
                }

                $m = new Morada();
                $m->rua = $data['rua'] ?? null;
                $m->nporta = $data['nporta'] ?? null;
                $m->andar = $data['andar'] ?? null;
                $m->cdpostal = $data['cdpostal'] ?? null;
                $m->cidade = $data['cidade'] ?? null;
                $m->cxpostal = $data['cxpostal'] ?? null;
                $m->localidade = $data['localidade'] ?? null;
                $m->principal = isset($data['principal']) ? (int)$data['principal'] : 0;
                $m->userprofiles_id = $model->id;
                $m->eliminado = 0;
                $m->save(false);
                $postedIds[] = $m->id;
            }
        }

        // mark as eliminado any existing moradas not present in postedIds
        $existing = Morada::find()->where(['userprofiles_id' => $model->id])->all();
        foreach ($existing as $e) {
            if ($e->id && !in_array($e->id, $postedIds)) {
                $e->eliminado = 1;
                $e->save(false);
            }
        }
    }

    /**
     * Add a new Morada for the logged-in user's profile.
     * GET: show form; POST: validate and save then redirect back to update.
     */
    public function actionAddMorada($id = null)
    {
        $user = Yii::$app->user->identity;
        $profile = $user->userprofile;
        if (!$profile || empty($profile->id)) {
            Yii::$app->session->setFlash('warning', 'Por favor, salve o perfil antes de adicionar/editar moradas.');
            return $this->redirect(['update']);
        }

        if ($id) {
            // editar morada existente
            $model = Morada::findOne($id);
            if (!$model || $model->userprofiles_id != $profile->id) {
                Yii::$app->session->setFlash('error', 'Morada não encontrada ou sem permissão.');
                return $this->redirect(['update']);
            }
        } else {
            $model = new Morada();
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->userprofiles_id = $profile->id;
            $model->eliminado = 0;
            if ($model->isNewRecord && !isset($model->principal)) {
                $model->principal = 0;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', ($id ? 'Morada atualizada' : 'Morada adicionada') . ' com sucesso.');
                return $this->redirect(['update']);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao gravar morada.');
            }
        }

        return $this->render('add-morada', ['model' => $model, 'profileId' => $profile->id]);
    }

    /**
     * Remove a morada (address) by marking it as eliminated.
     * POST only. Expects ID as a parameter.
     */
    public function actionRemoveMorada($id)
    {
        $user = Yii::$app->user->identity;
        $profile = $user->userprofile;
        if (!$profile) {
            if (Yii::$app->request->isPjax || Yii::$app->request->isAjax) {
                return $this->asJson(['error' => 'Perfil não encontrado.']);
            }
            Yii::$app->session->setFlash('error', 'Perfil não encontrado.');
            return $this->redirect(['update']);
        }

        $morada = Morada::findOne($id);
        if (!$morada || $morada->userprofiles_id != $profile->id) {
            if (Yii::$app->request->isPjax || Yii::$app->request->isAjax) {
                return $this->asJson(['error' => 'Morada não encontrada ou sem permissão.']);
            }
            Yii::$app->session->setFlash('error', 'Morada não encontrada ou sem permissão.');
            return $this->redirect(['update']);
        }

        $morada->eliminado = 1;
        $morada->save(false);

        // obter lista atualizada de moradas ativas
        $moradas = $profile->getMoradas()->where(['eliminado' => 0])->all();

        // se for pjax/ajx, retornar apenas o fragmento atualizado
        if (Yii::$app->request->isPjax || Yii::$app->request->isAjax) {
            return $this->renderAjax('_moradas_list', ['moradas' => $moradas]);
        }

        // fallback: redirect para update
        Yii::$app->session->setFlash('success', 'Morada removida.');
        return $this->redirect(['update']);
    }
}
