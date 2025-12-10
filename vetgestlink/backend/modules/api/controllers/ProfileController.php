<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use common\models\User;
use common\models\Userprofile;
use common\models\Morada;

/**
 * Controller de Perfil do Usuário
 *
 * Endpoints para gerenciar perfil e morada do cliente autenticado.
 */
class ProfileController extends ApiController
{

    /**
     * GET /profile
     * Obter dados completos do perfil
     */
    public function actionIndex()
    {
        $user = $this->getAuthenticatedUser();
        $userprofile = $user->userprofile;

        // Buscar morada principal
        $morada = Morada::findOne(['userprofiles_id' => $userprofile->id, 'eliminado' => 0]);

        return [
            'success' => true,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ],
            'profile' => [
                'id' => $userprofile->id,
                'nomecompleto' => $userprofile->nomecompleto,
                'telemovel' => $userprofile->telemovel,
                'nif' => $userprofile->nif,
            ],
            'morada' => $morada ? [
                'id' => $morada->id,
                'rua' => $morada->rua,
                'nporta' => $morada->nporta,
                'cdpostal' => $morada->cdpostal,
                'localidade' => $morada->localidade,
            ] : null,
        ];
    }

    /**
     * PUT /profile/update
     * Atualizar dados do perfil (nome, email, telefone e morada)
     */
    public function actionUpdate()
    {
        $user = $this->getAuthenticatedUser();
        $userprofile = $user->userprofile;

        $data = Yii::$app->request->post();

        // Atualizar nome completo
        if (isset($data['nomecompleto']) && !empty($data['nomecompleto'])) {
            $userprofile->nomecompleto = $data['nomecompleto'];
        }

        // Atualizar telefone (remover espaços e caracteres especiais, manter apenas dígitos)
        if (isset($data['telemovel']) && !empty($data['telemovel'])) {
            $telemovel = preg_replace('/[^0-9]/', '', $data['telemovel']);
            if (strlen($telemovel) > 9) {
                throw new BadRequestHttpException('O telemóvel deve ter no máximo 9 dígitos');
            }
            $userprofile->telemovel = $telemovel;
        }

        // Atualizar email (no User)
        if (isset($data['email']) && !empty($data['email'])) {
            // Validar formato de email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new BadRequestHttpException('Email inválido');
            }

            // Verificar se email já existe para outro usuário
            $existingUser = User::findOne(['email' => $data['email']]);
            if ($existingUser && $existingUser->id != $user->id) {
                throw new BadRequestHttpException('Este email já está em uso');
            }

            $user->email = $data['email'];
        }

        // Atualizar morada se fornecida
        $morada = null;
        if (isset($data['morada']) && is_array($data['morada'])) {
            $moradaData = $data['morada'];
            
            // Buscar morada existente
            $morada = Morada::findOne(['userprofiles_id' => $userprofile->id, 'eliminado' => 0]);
            
            // Se não existe, criar nova
            if (!$morada) {
                $morada = new Morada();
                $morada->userprofiles_id = $userprofile->id;
            }
            
            // Atualizar campos da morada se fornecidos
            if (isset($moradaData['rua']) && !empty($moradaData['rua'])) {
                $morada->rua = $moradaData['rua'];
            }
            if (isset($moradaData['nporta']) && !empty($moradaData['nporta'])) {
                $morada->nporta = $moradaData['nporta'];
            }
            if (isset($moradaData['cdpostal']) && !empty($moradaData['cdpostal'])) {
                $morada->cdpostal = $moradaData['cdpostal'];
            }
            if (isset($moradaData['localidade']) && !empty($moradaData['localidade'])) {
                $morada->localidade = $moradaData['localidade'];
            }
        }

        // Salvar alterações
        if (!$user->save(false)) {
            throw new BadRequestHttpException('Erro ao atualizar email: ' . json_encode($user->errors));
        }

        if (!$userprofile->save(false)) {
            throw new BadRequestHttpException('Erro ao atualizar perfil: ' . json_encode($userprofile->errors));
        }

        if ($morada && !$morada->save(false)) {
            throw new BadRequestHttpException('Erro ao atualizar morada: ' . json_encode($morada->errors));
        }

        // Buscar morada para resposta (caso não tenha sido atualizada)
        if (!$morada) {
            $morada = Morada::findOne(['userprofiles_id' => $userprofile->id, 'eliminado' => 0]);
        }

        return [
            'success' => true,
            'message' => 'Perfil atualizado com sucesso',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ],
            'profile' => [
                'id' => $userprofile->id,
                'nomecompleto' => $userprofile->nomecompleto,
                'telemovel' => $userprofile->telemovel,
                'nif' => $userprofile->nif,
            ],
            'morada' => $morada ? [
                'id' => $morada->id,
                'rua' => $morada->rua,
                'nporta' => $morada->nporta,
                'cdpostal' => $morada->cdpostal,
                'localidade' => $morada->localidade,
            ] : null,
        ];
    }

    /**
     * PUT /profile/morada
     * Atualizar ou criar morada principal
     */
    public function actionMorada()
    {
        $user = $this->getAuthenticatedUser();
        $userprofile = $user->userprofile;

        $data = Yii::$app->request->post();

        // Validar campos obrigatórios
        if (empty($data['rua']) || empty($data['nporta']) || empty($data['cdpostal']) || empty($data['localidade'])) {
            throw new BadRequestHttpException('Rua, número da porta, código postal e localidade são obrigatórios');
        }

        // Buscar morada existente
        $morada = Morada::findOne(['userprofiles_id' => $userprofile->id, 'eliminado' => 0]);

        // Se não existe, criar nova
        if (!$morada) {
            $morada = new Morada();
            $morada->userprofiles_id = $userprofile->id;
        }

        // Atualizar dados
        $morada->rua = $data['rua'];
        $morada->nporta = $data['nporta'];
        $morada->cdpostal = $data['cdpostal'];
        $morada->localidade = $data['localidade'];

        if (!$morada->save()) {
            throw new BadRequestHttpException('Erro ao salvar morada: ' . json_encode($morada->errors));
        }

        return [
            'success' => true,
            'message' => 'Morada atualizada com sucesso',
            'morada' => [
                'id' => $morada->id,
                'rua' => $morada->rua,
                'nporta' => $morada->nporta,
                'cdpostal' => $morada->cdpostal,
                'localidade' => $morada->localidade,
            ],
        ];
    }

    /**
     * PUT /profile/password
     * Alterar senha
     */
    public function actionPassword()
    {
        $user = $this->getAuthenticatedUser();

        $data = Yii::$app->request->post();

        // Validar campos obrigatórios
        if (empty($data['current_password']) || empty($data['new_password'])) {
            throw new BadRequestHttpException('Senha atual e nova senha são obrigatórias');
        }

        // Verificar senha atual
        if (!$user->validatePassword($data['current_password'])) {
            throw new BadRequestHttpException('Senha atual incorreta');
        }

        // Validar tamanho da nova senha
        if (strlen($data['new_password']) < 6) {
            throw new BadRequestHttpException('A nova senha deve ter no mínimo 6 caracteres');
        }

        // Atualizar senha
        $user->setPassword($data['new_password']);
        $user->generateAuthKey(); // Invalidar sessões antigas

        if (!$user->save()) {
            throw new BadRequestHttpException('Erro ao alterar senha: ' . json_encode($user->errors));
        }

        return [
            'success' => true,
            'message' => 'Senha alterada com sucesso',
            'token' => $user->auth_key, // Novo token
        ];
    }
}
