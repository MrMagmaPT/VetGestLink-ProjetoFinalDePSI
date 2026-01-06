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
            ['username', 'required', 'message' => 'O campo Nome de Utilizador é obrigatório.'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este nome de utilizador já está em uso.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required', 'message' => 'O campo Email é obrigatório.'],
            ['email', 'email', 'message' => 'Por favor, insira um endereço de email válido.'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este endereço de email já está em uso.'],

            ['password', 'required', 'message' => 'O campo Password é obrigatório.'],
            ['password', 'string', 'min' => 6, 'tooShort' => 'A password deve ter no mínimo 6 caracteres.'],

            ['nomecompleto', 'required', 'message' => 'O campo Nome Completo é obrigatório.'],
            ['nomecompleto', 'string', 'max' => 100],

            ['dtanascimento', 'required', 'message' => 'O campo Data de Nascimento é obrigatório.'],
            ['dtanascimento', 'date', 'format' => 'php:Y-m-d'],

            ['nif', 'required', 'message' => 'O campo NIF é obrigatório.'],
            ['nif', 'match', 'pattern' => '/^\d{9}$/', 'message' => 'O NIF deve conter exatamente 9 dígitos.'],

            ['telemovel', 'required', 'message' => 'O campo Telemóvel é obrigatório.'],
            ['telemovel', 'match', 'pattern' => '/^9\d{8}$/', 'message' => 'O Telemóvel deve começar com 9 e ter 9 dígitos.'],

            ['rua', 'required', 'message' => 'O campo Rua é obrigatório.'],
            ['nporta', 'required', 'message' => 'O campo Número da Porta é obrigatório.'],
            ['nporta', 'integer', 'message' => 'O Número da Porta deve ser um número válido.'],
            ['andar', 'integer', 'message' => 'O Andar deve ser um número válido.'],
            ['cdpostal', 'required', 'message' => 'O campo Código Postal é obrigatório.'],
            ['cdpostal', 'match', 'pattern' => '/^\d{4}-\d{3}$/', 'message' => 'O Código Postal deve ter o formato 0000-000.'],
            ['cidade', 'required', 'message' => 'O campo Cidade é obrigatório.'],
            ['localidade', 'required', 'message' => 'O campo Localidade é obrigatório.'],

            [['rua', 'nporta', 'andar', 'cdpostal', 'cidade', 'cxpostal', 'localidade'], 'string', 'max' => 45],
            [['andar', 'cxpostal'], 'default', 'value' => null],
            ['principal', 'boolean'],
            ['principal', 'default', 'value' => 1],

            ['imageFile', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2],

            ['role', 'string'],
            ['role', 'default', 'value' => 'cliente'],
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
