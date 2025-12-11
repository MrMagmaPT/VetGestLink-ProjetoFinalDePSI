<?php

namespace backend\modules\api;

/**
 * api module definition class
 */
class ModuleAPI extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Deixar stateless (sem sessão)
        \Yii::$app->user->enableSession = false;

        // Configurar errorHandler para retornar JSON
        \Yii::$app->errorHandler->errorAction = null;
        
        // Forçar response format JSON para erros
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }
}
