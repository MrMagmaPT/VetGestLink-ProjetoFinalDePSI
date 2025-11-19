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
        \Yii::$app->user->loginUrl = null; // Desabilitar redirect para login
        \Yii::$app->user->enableAutoLogin = false;

        // Configurar componentes do módulo
        $this->setComponents([
            'auth' => [
                'class' => 'backend\modules\api\components\AuthComponent',
                'loginSuccessMessage' => 'Login bem-sucedido',
                'logoutSuccessMessage' => 'Logout realizado com sucesso',
                'requiredRole' => 'cliente',
                'invalidateTokenOnLogout' => true,
            ],
        ]);
    }
}
