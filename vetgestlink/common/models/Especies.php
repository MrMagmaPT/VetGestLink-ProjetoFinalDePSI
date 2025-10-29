<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "especies".
 *
 * @property int $id
 * @property string $nome
 * @property int $eliminado
 *
 * @property Animais[] $animais
 * @property Racas[] $racas
 */
class Especies extends \yii\db\ActiveRecord
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
     * Gets query for [[Animais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimais()
    {
        return $this->hasMany(Animais::class, ['especies_id' => 'id']);
    }

    /**
     * Gets query for [[Racas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRacas()
    {
        return $this->hasMany(Racas::class, ['especies_id' => 'id']);
    }

}
