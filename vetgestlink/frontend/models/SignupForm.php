<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Userprofile;
use common\models\Morada;
use yii\web\UploadedFile;

class SignupForm extends Model
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

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este nome de utilizador já existe.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este email já está registado.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['nomecompleto', 'dtanascimento', 'nif', 'telemovel'], 'required'],
            ['nomecompleto', 'string', 'max' => 45],
            ['dtanascimento', 'date', 'format' => 'php:Y-m-d'],
            ['nif', 'string', 'length' => 9],
            ['telemovel', 'string', 'length' => 9],

            [['rua', 'nporta', 'cdpostal', 'localidade', 'cidade'], 'required'],

            // MUDANÇA: Ajustar max para 45 caracteres (conforme tabela)
            [['rua', 'localidade', 'cidade'], 'string', 'max' => 45],
            [['nporta', 'andar', 'cxpostal', 'cdpostal'], 'string', 'max' => 45],

            ['principal', 'boolean'],
            ['principal', 'default', 'value' => 1],

            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
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

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // 1. Criar User
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            //$user->status = User::STATUS_INACTIVE;
            $user->created_at = time();
            $user->updated_at = time();

            if (!$user->save()) {
                Yii::error("Erro User: " . json_encode($user->errors));
                throw new \Exception('Erro ao criar User: ' . json_encode($user->errors));
            }

            Yii::info("User ID {$user->id} criado");

            // 2. Atribuir role "cliente"
            $auth = Yii::$app->authManager;
            $clienteRole = $auth->getRole('cliente');

            if ($clienteRole) {
                $auth->assign($clienteRole, $user->id);
                Yii::info("Role 'cliente' atribuída ao User ID {$user->id}");
            } else {
                Yii::warning("Role 'cliente' não encontrada no sistema RBAC");
            }
            // 3. Criar Userprofile
            $userprofile = new Userprofile();
            $userprofile->user_id = $user->id;
            $userprofile->nomecompleto = $this->nomecompleto;
            $userprofile->dtanascimento = $this->dtanascimento;
            $userprofile->dtaregisto = date('Y-m-d H:i:s');
            $userprofile->nif = $this->nif;
            $userprofile->telemovel = $this->telemovel;
            $userprofile->eliminado = 0;

            if (!$userprofile->save()) {
                Yii::error("Erro Userprofile: " . json_encode($userprofile->errors));
                throw new \Exception('Erro ao criar Userprofile: ' . json_encode($userprofile->errors));
            }

            // 3.1 Upload de imagem de perfil (se fornecida)
            if ($this->imageFile) {
                $userprofile->imageFile = $this->imageFile;
                $userprofile->uploadImage();
                Yii::info("Imagem de perfil carregada para Userprofile ID {$userprofile->id}");
            }

            // 4. Criar Morada
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
            $morada->eliminado = 0;

            if (!$morada->save()) {
                Yii::error("Erro Morada: " . json_encode($morada->errors));
                throw new \Exception('Erro ao criar Morada: ' . json_encode($morada->errors));
            }

            $transaction->commit();

            return $user && $this->sendEmail($user) ? $user : null;

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Rollback: " . $e->getMessage());
            Yii::$app->session->setFlash('error', $e->getMessage());
            return null;
        }
    }
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
