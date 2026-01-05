<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class MedicamentoFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Medicamento';
    public $depends = [CategoriaFixture::class];
}
