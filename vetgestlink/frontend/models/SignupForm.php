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

            ['nomecompleto', 'required'],
            ['dtanascimento', 'required'],
            ['nif', 'required'],
            ['nif', 'string', 'length' => 9],
            ['telemovel', 'required'],
            ['telemovel', 'string', 'length' => 9],

            ['rua', 'required'],
            ['nporta', 'required'],
            ['cdpostal', 'required'],
            ['localidade', 'required'],
            ['cidade', 'required'],
            [['andar', 'cxpostal'], 'safe'],
            ['principal', 'boolean'],
            ['imageFile', 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2], // 2MB
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
        // Carrega o ficheiro antes da validação para que a regra 'file' funcione
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

        if (!$this->validate()) {
            Yii::$app->session->setFlash('danger', 'Existem erros no formulário. Verifique os campos novamente.');
            Yii::error("Validação falhou: " . json_encode($this->errors));
            return null;
        }

        $uploadedFileName = null;

        try {
            // 1. Criar User
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            //$user->status = User::STATUS_INACTIVE;
            $user->created_at = time();
            $user->updated_at = time();

            if (!$user->save()) {
                Yii::$app->session->setFlash('danger', 'Erro ao criar utilizador.');
                Yii::error("Erro User: " . json_encode($user->errors));
                return null;
            }

            // 2. Atribuir role
            $auth = Yii::$app->authManager;
            $clienteRole = $auth->getRole('cliente');

            if ($clienteRole) {
                $auth->assign($clienteRole, $user->id);
            } else {
                Yii::$app->session->setFlash('warning', 'A role "cliente" não existe no RBAC.');
            }

            // 3. Criar Userprofile
            $userprofile = new Userprofile();
            $userprofile->user_id = $user->id;
            $userprofile->nomecompleto = $this->nomecompleto;
            $userprofile->dtanascimento = $this->dtanascimento;
            $userprofile->nif = $this->nif;
            $userprofile->telemovel = $this->telemovel;
            $userprofile->eliminado = 0;

            if (!$userprofile->save()) {
                Yii::$app->session->setFlash('danger', 'Erro ao criar o perfil do utilizador.');
                Yii::error("Erro Userprofile: " . json_encode($userprofile->errors));
                // apagar user criado para evitar orfãos
                try {
                    $user->delete();
                } catch (\Throwable $t) {
                    Yii::error('Falha ao apagar user após erro no perfil: ' . $t->getMessage());
                }
                return null;
            }

            // 4. Upload de imagem de perfil (se fornecida)
            if ($this->imageFile) {
                $userprofile->imageFile = $this->imageFile;
                $uploaded = $userprofile->uploadImage();
                if ($uploaded) {
                    $uploadedFileName = $userprofile->foto ?? null; // já é apenas basename
                    if (!$userprofile->save(false)) {
                        Yii::error("Não foi possível guardar o path da imagem no Userprofile: " . json_encode($userprofile->errors));
                        throw new \Exception('Erro ao guardar caminho da imagem no perfil');
                    }
                    Yii::info("Imagem de perfil carregada e caminho guardado para Userprofile ID {$userprofile->id}");
                } else {
                    Yii::warning("Upload de imagem falhou para Userprofile ID {$userprofile->id}");
                    // continuar sem imagem
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
            $morada->eliminado = 0;

            if (!$morada->save()) {
                Yii::$app->session->setFlash('danger', 'Erro ao guardar a morada.');
                Yii::error("Erro Morada: " . json_encode($morada->errors));
                // apagar dados criados (userprofile e user)
                try {
                    $userprofile->delete();
                    $user->delete();
                } catch (\Throwable $t) {
                    Yii::error('Falha ao apagar registos após erro na morada: ' . $t->getMessage());
                }
                return null;
            }

            // 6. Enviar email de verificação
            return $user && $this->sendEmail($user) ? $user : $user;

        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            // Apagar ficheiro enviado caso já exista
            if (!empty($uploadedFileName) && Yii::$app->has('imageUploader')) {
                try {
                    Yii::$app->imageUploader->delete($uploadedFileName);
                } catch (\Throwable $t) {
                    Yii::error("Falha ao apagar ficheiro após rollback: " . $t->getMessage());
                }
            }
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
                [
                    'user' => $user,
                ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
