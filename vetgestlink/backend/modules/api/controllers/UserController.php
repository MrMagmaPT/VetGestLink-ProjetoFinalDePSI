<?php
namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\web\Response;
use common\models\User;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Configuração CORS para Android
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'tokenParam' => 'auth_key',
            'except' => ['login', 'options'],
        ];

        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        return $actions;
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $username = $request->get('username');
        $password = $request->get('password');

        if (empty($username) || empty($password)) {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Username e password são obrigatórios'
            ];
        }

        $user = User::findByUsername($username);

        if ($user && $user->validatePassword($password)) {
            if (empty($user->auth_key)) {
                $user->generateAuthKey();
                $user->save(false);
            }

            Yii::$app->response->statusCode = 200;
            return [
                'success' => true,
                'auth_key' => $user->auth_key,
                'user_id' => $user->id,
                'username' => $user->username
            ];
        }

        Yii::$app->response->statusCode = 401;
        return [
            'success' => false,
            'message' => 'Credenciais inválidas'
        ];
    }

}
