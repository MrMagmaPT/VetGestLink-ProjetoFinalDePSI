<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Userprofile;
use common\models\Morada;
use yii\web\UploadedFile;

class SignupFormBackend extends Model
{
    public $username;
    public $email;
    public $password;
    public $nomecompleto;
    public $dtanascimento;
    public $nif;
    public $telemovel;
    public $rua;
    public $nporta;
    public $andar;
    public $cdpostal;
    public $cxpostal;
    public $localidade;
    public $cidade;
    public $principal;
    public $imageFile;
    public $role;

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => 'O nome de utilizador é obrigatório.'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este nome de utilizador já existe.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required', 'message' => 'O email é obrigatório.'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este email já está registado.'],

            ['password', 'required', 'message' => 'A palavra-passe é obrigatória.'],
            ['password', 'string', 'min' => 6],

            ['nomecompleto', 'required', 'message' => 'O nome completo é obrigatório.'],
            ['dtanascimento', 'required', 'message' => 'A data de nascimento é obrigatória.'],
            ['nif', 'required', 'message' => 'O NIF é obrigatório.'],
            ['telemovel', 'required', 'message' => 'O telemóvel é obrigatório.'],
            ['nomecompleto', 'string', 'max' => 45],
            ['dtanascimento', 'date', 'format' => 'php:Y-m-d'],
            ['nif', 'string', 'length' => 9],
            ['telemovel', 'string', 'length' => 9],

            ['rua', 'required', 'message' => 'A rua é obrigatória.'],
            ['nporta', 'required', 'message' => 'O número da porta é obrigatório.'],
            ['cdpostal', 'required', 'message' => 'O código postal é obrigatório.'],
            ['localidade', 'required', 'message' => 'A localidade é obrigatória.'],
            ['cidade', 'required', 'message' => 'A cidade é obrigatória.'],

            // MUDANÇA: Ajustar max para 45 caracteres (conforme tabela)
            [['rua', 'localidade', 'cidade'], 'string', 'max' => 45],
            [['nporta', 'andar', 'cxpostal', 'cdpostal'], 'string', 'max' => 45],

            ['principal', 'boolean'],
            ['principal', 'default', 'value' => 1],

            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'wrongExtension' => 'Apenas ficheiros PNG, JPG ou JPEG são permitidos.'],
            ['role', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Nome de Utilizador',
            'email' => 'Email',
            'password' => 'Palavra-passe',
            'nomecompleto' => 'Nome Completo',
            'dtanascimento' => 'Data de Nascimento',
            'nif' => 'NIF',
            'telemovel' => 'Telemóvel',
            'rua' => 'Rua',
            'nporta' => 'Número da Porta',
            'andar' => 'Andar',
            'cdpostal' => 'Código Postal',
            'cxpostal' => 'Caixa Postal',
            'localidade' => 'Localidade',
            'cidade' => 'Cidade',
            'principal' => 'Morada Principal',
            'imageFile' => 'Fotografia de Perfil',
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            Yii::error("Validação falhou: " . json_encode($this->errors));
            return null;
        }

        try {
            // 1. Criar User primeiro
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            //Usuarios Criados pelo backend ficam ativos por defeito
            $user->status = User::STATUS_ACTIVE;
            $user->created_at = time();
            $user->updated_at = time();

            if (!$user->save()) {
                Yii::error("Erro User: " . json_encode($user->errors));
                $this->addErrors($user->getErrors());
                return null;
            }

            Yii::info("User ID {$user->id} criado");

            // 2. Atribuir role selecionada ou cliente por defeito
            $auth = Yii::$app->authManager;
            $roleName = $this->role ?: 'cliente'; // Se não foi definida, usa 'cliente'
            $roleObj = $auth->getRole($roleName);
            if ($roleObj) {
                $auth->assign($roleObj, $user->id);
                Yii::info("Role '{$roleName}' atribuída ao User ID {$user->id}");
            } else {
                Yii::warning("Role '{$roleName}' não encontrada no sistema RBAC");
            }

            // 3. Criar e salvar Userprofile (agora com user_id válido)
            $userprofile = new Userprofile();
            $userprofile->user_id = $user->id;
            $userprofile->nomecompleto = $this->nomecompleto;
            $userprofile->dtanascimento = $this->dtanascimento;
            $userprofile->nif = $this->nif;
            $userprofile->telemovel = $this->telemovel;

            if (!$userprofile->save()) {
                Yii::error("Erro Userprofile: " . json_encode($userprofile->errors));
                // Se falhar, apagar o User criado
                $user->delete();
                $this->addErrors($userprofile->getErrors());
                return null;
            }

            Yii::info("Userprofile ID {$userprofile->id} criado");

            // 4. Upload de imagem de perfil (se fornecida)
            if ($this->imageFile instanceof UploadedFile) {
                Yii::info("SignupFormBackend: Attempting to upload image for user {$user->id}", __METHOD__);
                $userprofile->imageFile = $this->imageFile;
                
                if ($userprofile->uploadImage()) {
                    // Salvar o caminho da imagem na BD
                    if ($userprofile->save(false)) {
                        Yii::info("SignupFormBackend: Image uploaded and saved: {$userprofile->foto}", __METHOD__);
                    } else {
                        Yii::error("SignupFormBackend: Failed to save image path: " . json_encode($userprofile->errors), __METHOD__);
                    }
                } else {
                    Yii::error("SignupFormBackend: Image upload failed for user {$user->id}", __METHOD__);
                }
            }

            // 5. Criar Morada
            $morada = new Morada();
            $morada->userprofiles_id = $userprofile->id;
            $morada->rua = $this->rua;
            $morada->nporta = $this->nporta;
            $morada->andar = $this->andar;
            $morada->cdpostal = $this->cdpostal;
            $morada->cxpostal = $this->cxpostal;
            $morada->localidade = $this->localidade;
            $morada->cidade = $this->cidade;
            $morada->principal = $this->principal ? 1 : 0;

            // Salvar a morada
            if (!$morada->save()) {
                Yii::error("Erro Morada: " . json_encode($morada->errors));
                // Se falhar, apagar Userprofile e User criados
                $userprofile->delete();
                $user->delete();
                $this->addErrors($morada->getErrors());
                return null;
            }

            //Debugar
            Yii::info("Morada ID {$morada->id} criada para Userprofile ID {$userprofile->id}");

            return $user;

        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            Yii::$app->session->setFlash('error', $e->getMessage());
            return null;
        }
    }

}
