<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "raca".
 *
 * @property int $id
 * @property string $nome
 * @property int $especies_id
 * @property int $eliminado
 *
 * @property Animal[] $animal
 * @property Especie $especie
 */
class Raca extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'racas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eliminado'], 'default', 'value' => 0],
            [['nome', 'especies_id'], 'required'],
            [['especies_id', 'eliminado'], 'integer'],
            [['nome'], 'string', 'max' => 45],
            [['especies_id'], 'exist', 'skipOnError' => true, 'targetClass' => Especie::class, 'targetAttribute' => ['especies_id' => 'id']],
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
            'especies_id' => 'Especie ID',
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
        return $this->hasMany(Animal::class, ['racas_id' => 'id']);
    }

    /**
     * Gets query for [[Especie]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEspecies()
    {
        return $this->hasOne(Especie::class, ['id' => 'especies_id']);
    }

}
