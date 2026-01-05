<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class AnimalFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Animal';
    public $depends = [
        EspecieFixture::class,
        RacaFixture::class,
        UserprofileFixture::class,
    ];
}
