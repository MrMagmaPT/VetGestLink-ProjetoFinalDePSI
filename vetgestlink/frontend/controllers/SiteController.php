<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        //não autenticados podem registar-se
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        //autenticados podem fazer logout
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        //obter todos os serviços ordenados por nome
        $servicos = \common\models\Servico::find()
            ->orderBy(['nome' => SORT_ASC])
            ->all();

        return $this->render('index', [
            'servicos' => $servicos,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                if ($model->sendEmail(Yii::$app->params['vetGestLinkEmail'])) {
                    Yii::$app->session->setFlash('success', '<i class="fas fa-check-circle me-2"></i><strong>Mensagem enviada com sucesso!</strong> Obrigado por entrar em contato. Responderemos em breve.');
                } else {
                    Yii::$app->session->setFlash('danger', '<i class="fas fa-exclamation-circle me-2"></i>Ocorreu um erro ao enviar sua mensagem. Por favor, tente novamente.');
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('danger', '<i class="fas fa-exclamation-triangle me-2"></i>Erro ao enviar email: ' . $e->getMessage());
                Yii::error('Erro no envio de email de contato: ' . $e->getMessage(), 'contact');
            }
            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $signupResult = $model->signup();
            if ($signupResult) {
                Yii::$app->session->setFlash('success', 'Obrigado por se registar. Por favor verifique o seu email.');
                return $this->goHome();
            } else {
                $errorSummary = implode('<br>', array_map(function($errors) {
                    return implode('<br>', $errors);
                }, $model->getErrors()));
                Yii::$app->session->setFlash('danger', 'Ocorreu um erro ao criar a sua conta.<br>' . $errorSummary);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Verifique o seu email.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('danger', 'Desculpe, não conseguimos dar reset á password deste email.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Nova password salva com sucesso.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->verifyEmail()) {
            Yii::$app->session->setFlash('success', 'O seu email foi confirmado com sucesso!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Desculpe, não foi possível verificar a sua conta com o token fornecido.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Verifique o seu email para mais instruções.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Desculpe, não foi possível reenviar o email de verificação para o endereço fornecido.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function actionInformation(){
        return $this->render('information');
    }
}
