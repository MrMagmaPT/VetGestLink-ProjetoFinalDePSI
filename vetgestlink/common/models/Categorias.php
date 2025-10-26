<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categorias".
 *
 * @property int $id
 * @property string $nome
 * @property int $eliminado
 *
 * @property Medicamentos[] $medicamentos
 */
class Categorias extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorias';
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
            [['nome'], 'string', 'max' => 100],
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
     * Gets query for [[Medicamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedicamentos()
    {
        return $this->hasMany(Medicamentos::class, ['categorias_id' => 'id']);
    }

}
