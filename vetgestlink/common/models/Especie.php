<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "especie".
 *
 * @property int $id
 * @property string $nome
 * @property int $eliminado
 *
 * @property Animal[] $animal
 * @property Raca[] $raca
 */
class Especie extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'especies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eliminado'], 'default', 'value' => 0],
            [['nome'], 'required'],
            [['eliminado'], 'integer'],
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
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Animal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimais()
    {
        return $this->hasMany(Animal::class, ['especies_id' => 'id']);
    }

    /**
     * Gets query for [[Raca]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRacas()
    {
        return $this->hasMany(Raca::class, ['especies_id' => 'id']);
    }

}
