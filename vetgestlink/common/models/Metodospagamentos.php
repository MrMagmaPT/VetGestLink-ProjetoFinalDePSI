<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "metodospagamentos".
 *
 * @property int $id
 * @property string $nome
 * @property int $vigor
 * @property int $eliminado
 *
 * @property Faturas[] $faturas
 */
class Metodospagamentos extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metodospagamentos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eliminado'], 'default', 'value' => 0],
            [['nome', 'vigor'], 'required'],
            [['vigor', 'eliminado'], 'integer'],
            [['nome'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'vigor' => 'Vigor',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Faturas::class, ['metodospagamentos_id' => 'id']);
    }

}
