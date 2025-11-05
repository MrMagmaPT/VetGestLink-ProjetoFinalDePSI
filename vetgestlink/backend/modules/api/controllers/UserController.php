<?php
namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    //USAR O CAMPO AUTH_KEY DA TABELA USER DO YII2 PARA AUTENTICAÇÃO NAS REQUISIÇÕES API

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            //only=> ['index'], //Apenas para o GET
        ];
        return $behaviors;
    }
}