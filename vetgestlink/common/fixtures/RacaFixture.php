<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class RacaFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Raca';
    public $depends = [EspecieFixture::class];
}
