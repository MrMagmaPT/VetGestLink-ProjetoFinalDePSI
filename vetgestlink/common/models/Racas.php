<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "racas".
 *
 * @property int $id
 * @property string $nome
 * @property int $especies_id
 * @property int $eliminado
 *
 * @property Animais[] $animais
 * @property Especies $especies
 */
class Racas extends \yii\db\ActiveRecord
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
            [['especies_id'], 'exist', 'skipOnError' => true, 'targetClass' => Especies::class, 'targetAttribute' => ['especies_id' => 'id']],
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
            'especies_id' => 'Especies ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Animais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimais()
    {
        return $this->hasMany(Animais::class, ['racas_id' => 'id']);
    }

    /**
     * Gets query for [[Especies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEspecies()
    {
        return $this->hasOne(Especies::class, ['id' => 'especies_id']);
    }

}
