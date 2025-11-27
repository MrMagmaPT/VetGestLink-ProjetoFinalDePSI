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
     * Carrega as moradas ativas (eliminado = 0) e apresenta a view `view.php`.
     */
    public function actionView($id = null)
    {
        // id do utilizador: ou o passado por parâmetro, ou o atual autenticado
        $id = $id ?: Yii::$app->user->id;
        $user = User::find()->where(['id' => $id])->with(['userprofile.moradas'])->one();

        if (!$user) {
            throw new NotFoundHttpException('Utilizador não encontrado.');
        }

        // Obter perfil e moradas (somente as não eliminadas)
        $profile = $user->userprofile;
        if ($profile === null) {
            $profile = new Userprofile();
            $profile->user_id = $user->id;
            $moradas = [];
        } else {
            $moradas = $profile->getMoradas()->where(['eliminado' => 0])->all();
            // Fallback legacy caso não existam moradas (mantido por compatibilidade)
            if (empty($moradas)) {
                if (isset($profile->enderecos) && is_array($profile->enderecos) && count($profile->enderecos)) {
                    foreach ($profile->enderecos as $e) {
                        if (isset($e->eliminado) && $e->eliminado == 0) {
                            $moradas[] = $e;
                        }
                    }
                } elseif (isset($profile->address) && is_array($profile->address) && count($profile->address)) {
                    foreach ($profile->address as $e) {
                        if (isset($e->eliminado) && $e->eliminado == 0) {
                            $moradas[] = $e;
                        }
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
     * Atualiza o perfil do utilizador.
     * A função principal fica curta; validações e tarefas delegadas para helpers.
     */
    public function actionUpdate()
    {
        $user = Yii::$app->user->identity;
        $model = $user->userprofile;
        $moradas = $model ? $model->getMoradas()->where(['eliminado' => 0])->all() : [];

        // Normalizar pedido POST para o caso do botão "Remover" sem JavaScript
        $this->normalizeNoJsRemove();

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

                    // Guardar moradas submetidas
                    $this->savePostedMoradas($model);

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

        $this->normalizeNoJsRemove();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if (!$this->handleImageUpload($model)) {
                return $this->render('update', ['model' => $model, 'moradas' => $moradas]);
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

        return $this->redirect(['view']);
    }

    /**
     * Adicionar ou editar uma morada do perfil.
     * Delegado para métodos auxiliares e renderiza `_add_morada` estritamente.
     */
    public function actionAddMorada($id = null)
    {
        $user = Yii::$app->user->identity;
        $profile = $user->userprofile;

        if (!$profile || empty($profile->id)) {
            Yii::$app->session->setFlash('warning', 'Por favor, salve o perfil antes de adicionar/editar moradas.');
            return $this->redirect(['update']);
        }

        $model = $this->findMoradaForProfile($id, $profile) ?? new Morada();

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

        return $this->renderAddMoradaView($model, $profile->id);
    }

    /**
     * Marcar uma morada como eliminada (eliminado = 0 -> 1).
     * Suporta requisições PJAX/AJAX (retorna partial) e fallback redirect.
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

        // Lista atualizada de moradas ativas
        $moradas = $profile->getMoradas()->where(['eliminado' => 0])->all();
        if (Yii::$app->request->isPjax || Yii::$app->request->isAjax) {
            return $this->renderAjax('_moradas_list', ['moradas' => $moradas]);
        }

        Yii::$app->session->setFlash('success', 'Morada removida.');
        return $this->redirect(['update']);
    }

    /**
     * Rota amigável para adicionar/editar morada.
     */
    public function actionAdicionarMorada($id = null)
    {
        return $this->actionAddMorada($id);
    }

    /**
     * Rota amigável para editar morada.
     */
    public function actionEditarMorada($id)
    {
        return $this->actionAddMorada($id);
    }

    // ----------------------- HELPERS PRIVADOS -----------------------

    /**
     * Normaliza o POST para suportar o botão "Remover" sem JS.
     * Transforma o índice recebido em Morada[$index][eliminado] = 1.
     * Para
     */
    private function normalizeNoJsRemove(): void
    {
        $removeIndex = Yii::$app->request->post('remover_morada', null);
        if ($removeIndex !== null) {
            $post = Yii::$app->request->post();
            if (!isset($post['Morada'])) {
                $post['Morada'] = [];
            }
            $post['Morada'][$removeIndex]['eliminado'] = 1;
            if (method_exists(Yii::$app->request, 'setBodyParams')) {
                Yii::$app->request->setBodyParams($post);
            } else {
                $_POST = $post;
            }
        }
    }
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

    /**
     * Renderiza estritamente a partial `_add_morada.php`.
     * Se não existir, lança NotFoundHttpException para facilitar debugging.
     */
    private function renderAddMoradaView(Morada $model, $profileId)
    {
        $expected = $this->getViewPath() . DIRECTORY_SEPARATOR . '_add_morada.php';
        if (file_exists($expected)) {
            return $this->render('_add_morada', ['model' => $model, 'profileId' => $profileId]);
        }

        throw new NotFoundHttpException("View for add/edit morada not found. Expected file: $expected");
    }

    /**
     * Procura uma Morada por id e valida que pertence ao profile.
     * Retorna a Morada ou null (e define flash em caso de erro).
     */
    private function findMoradaForProfile($id, Userprofile $profile): ?Morada
    {
        if (empty($id)) {
            return null;
        }

        $m = Morada::findOne($id);
        if (!$m || $m->userprofiles_id != $profile->id) {
            Yii::$app->session->setFlash('error', 'Morada não encontrada ou sem permissão.');
            return null;
        }

        return $m;
    }

    /**
     * Processa os dados de Morada submetidos: cria, atualiza, marca eliminado.
     * Mantive a implementação original, apenas adicionei chaves e comentários.
     */
    protected function savePostedMoradas(Userprofile $model)
    {
        $posted = Yii::$app->request->post('Morada', []);
        $postedIds = [];

        foreach ($posted as $i => $data) {
            // Ignorar blocos vazios sem id
            $hasAny = false;
            foreach (['rua','nporta','cdpostal','cidade','localidade'] as $k) {
                if (!empty($data[$k])) {
                    $hasAny = true;
                    break;
                }
            }
            if (empty($data['id']) && !$hasAny) {
                continue;
            }

            // Atualizar morada existente
            if (!empty($data['id'])) {
                $m = Morada::findOne($data['id']);
                if ($m && $m->userprofiles_id == $model->id) {
                    if (!empty($data['eliminado'])) {
                        $m->eliminado = 1;
                        $m->save(false);
                        continue;
                    }

                    // Atribuir apenas campos permitidos
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
                // Nova morada
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

        // Marcar como eliminado qualquer morada existente que não foi enviada no form
        $existing = Morada::find()->where(['userprofiles_id' => $model->id])->all();
        foreach ($existing as $e) {
            if ($e->id && !in_array($e->id, $postedIds)) {
                $e->eliminado = 1;
                $e->save(false);
            }
        }
    }

    /**
     * Encontra um Userprofile por id.
     */
    protected function findModel($id)
    {
        if (($model = Userprofile::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
