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
            ['username', 'required', 'message' => 'O campo Nome de Utilizador é obrigatório.'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este nome de utilizador já existe.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required', 'message' => 'O campo Email é obrigatório.'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Este email já está registado.'],

            ['password', 'required', 'message' => 'O campo Palavra-passe é obrigatório.'],
            ['password', 'string', 'min' => 6],

            ['nomecompleto', 'required', 'message' => 'O campo Nome Completo é obrigatório.'],
            ['dtanascimento', 'required', 'message' => 'O campo Data de Nascimento é obrigatório.'],
            ['nif', 'required', 'message' => 'O campo NIF é obrigatório.'],
            ['nif', 'string', 'length' => 9],
            ['telemovel', 'required', 'message' => 'O campo Telemóvel é obrigatório.'],
            ['telemovel', 'string', 'length' => 9],

            ['rua', 'required', 'message' => 'O campo Rua é obrigatório.'],
            ['nporta', 'required', 'message' => 'O campo Número da Porta é obrigatório.'],
            ['cdpostal', 'required', 'message' => 'O campo Código Postal é obrigatório.'],
            ['localidade', 'required', 'message' => 'O campo Localidade é obrigatório.'],
            ['cidade', 'required', 'message' => 'O campo Cidade é obrigatório.'],
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
            // 1. Criar User primeiro
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            //Usuarios Criados no frontend ficam Inativos até verificação por email
            $user->status = User::STATUS_INACTIVE;
            $user->created_at = time();
            $user->updated_at = time();

            if (!$user->save()) {
                Yii::error("Erro User: " . json_encode($user->errors));
                $this->addErrors($user->getErrors());
                return null;
            }

            // 2. Criar e salvar Userprofile (agora com user_id válido)
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

            // 3. Atribuir role
            $auth = Yii::$app->authManager;
            $clienteRole = $auth->getRole('cliente');

            if ($clienteRole) {
                $auth->assign($clienteRole, $user->id);
            } else {
                Yii::$app->session->setFlash('warning', 'A role "cliente" não existe no RBAC.');
            }

            // 4. Salvar Userprofile já validado
            $userprofile->user_id = $user->id;
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

            // 5. Upload de imagem de perfil (se fornecida)
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

            // 6. Criar Morada
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

            // 7. Enviar email de verificação
            $emailEnviado = false;

            // Enviar email de verificação
            if ($user) {
                $emailEnviado = $this->sendEmail($user);
                if (!$emailEnviado) {
                    Yii::error('Falha ao enviar e-mail de verificação para o utilizador: ' . $user->email);
                } else {
                    Yii::info('E-mail de verificação enviado para: ' . $user->email);
                }
            }
            return $user;

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
