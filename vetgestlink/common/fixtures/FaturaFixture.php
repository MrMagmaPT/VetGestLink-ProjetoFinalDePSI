<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class FaturaFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Fatura';
    public $depends = [
        MetodopagamentoFixture::class,
        UserprofileFixture::class,
    ];
}
