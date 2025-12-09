<?php
namespace common\validators;

use yii\validators\Validator;

class BirthValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $birth = $model->$attribute;
        if (empty($birth)) {
            $this->addError($model, $attribute, 'A data de nascimento é obrigatória.');
            return;
        }
        $birthDate = strtotime($birth);
        $today = strtotime(date('Y-m-d'));
        if ($birthDate > $today) {
            $this->addError($model, $attribute, 'A data de nascimento não pode ser maior que hoje.');
        }
    }
}
