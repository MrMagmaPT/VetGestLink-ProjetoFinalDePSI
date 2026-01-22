<?php

namespace backend\modules\api\controllers;

use yii\rest\Controller;
use yii\web\BadRequestHttpException;

class MatematicaController extends Controller
{
    public $modelClass = 'common\models\Servico';
    public function actionRaizdois($valor){
        if (!is_numeric($valor)) {
            throw new BadRequestHttpException('Parâmetro inválido. O valor deve ser um número.');
        }
        //Verificar se o numero é negativo
        if ((float)$valor < 0) {
            throw new BadRequestHttpException('Não é possível calcular a raiz quadrada de um número negativo.');
        }

        return [
            'raizdois' => round(sqrt((float)$valor),2)
        ];
    }
}

