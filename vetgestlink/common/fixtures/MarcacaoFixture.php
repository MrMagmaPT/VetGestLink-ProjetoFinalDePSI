<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class MarcacaoFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Marcacao';
    public $depends = [
        ServicoFixture::class,
        AnimalFixture::class,
        UserprofileFixture::class,
    ];
}
