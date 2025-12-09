<?php
namespace common\validators;

use yii\validators\Validator;

class NifValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $nif = $model->$attribute;

        if (!$this->isValidNif($nif)) {
            $this->addError($model, $attribute, 'O NIF introduzido não é válido.');
        }
    }

    private function isValidNif($nif)
    {
        // Usa uma expressão regular para garantir que o NIF tem exatamente 9 dígitos
        // e que o primeiro dígito é 1, 2, 3, 5, 6, 8 ou 9. Se não passar, retorna falso.
            if (!preg_match('/^[1235689]\d{8}$/', $nif)){
            return false;
        } 

        // Cálculo do dígito de controlo
        $sum = 0;
        //Para cada um dos 8 primeiros dígitos do NIF, multiplica o dígito pelo peso (de 9 a 2) e soma ao total.
        for ($i = 0; $i < 8; $i++){
            $sum += $nif[$i] * (9 - $i);
        } 

        $check = $sum % 11;
        // Se o resto for menor que 2, o dígito de controlo é 0. 
        // Caso contrário, é 11 menos o resto.
        if ($check < 2) {
            $check = 0;
        } else {
            $check = 11 - $check;
        }

        // Compara o dígito de controlo calculado com o último dígito do NIF
        return $nif[8] == $check;
    }
}
