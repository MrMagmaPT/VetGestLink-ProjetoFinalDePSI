<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "servicos".
 *
 * @property int $id
 * @property string $nome
 * @property float $valor
 * @property int $eliminado
 *
 * @property Marcacao[] $marcacoes
 */
class Servico extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servicos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eliminado'], 'default', 'value' => 0],
            [['nome', 'valor'], 'required'],
            [['valor'], 'number'],
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
            'valor' => 'Valor',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Marcacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarcacoes()
    {
        return $this->hasMany(Marcacao::class, ['servicos_id' => 'id']);
    }

}
