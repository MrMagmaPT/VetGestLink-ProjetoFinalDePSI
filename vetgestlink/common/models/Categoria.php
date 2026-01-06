<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categoria".
 *
 * @property int $id
 * @property string $nome
 * @property int $eliminado
 *
 * @property Medicamento[] $medicamento
 */
class Categoria extends \yii\db\ActiveRecord
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
     * Gets query for [[Medicamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedicamentos()
    {
        return $this->hasMany(Medicamento::class, ['categorias_id' => 'id']);
    }

    /**
     * Retorna a contagem de medicamentos ativos da categoria
     * @return int
     */
    public function getMedicamentosAtivosCount()
    {
        return $this->getMedicamentos()->where(['eliminado' => 0])->count();
    }

    /**
     * Retorna medicamentos ativos desta categoria (limitado)
     * @param int $limit Limite de registos
     * @return Medicamento[]
     */
    public function getMedicamentosAtivos($limit = 10)
    {
        return $this->getMedicamentos()
            ->where(['eliminado' => 0])
            ->limit($limit)
            ->all();
    }

    /**
     * Retorna contagem total de categorias ativas
     * @return int
     */
    public static function getTotalCount()
    {
        return self::find()->where(['eliminado' => 0])->count();
    }

}
