<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "medicamento".
 *
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property float $preco
 * @property int $quantidade
 * @property int $categorias_id
 * @property int $eliminado
 *
 * @property Categoria $categoria
 * @property Linhafatura[] $linhasfaturas
 */
class Medicamento extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medicamentos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eliminado'], 'default', 'value' => 0],
            [['nome', 'descricao', 'preco', 'quantidade', 'categorias_id'], 'required'],
            [['preco'], 'number'],
            [['quantidade', 'categorias_id', 'eliminado'], 'integer'],
            [['nome'], 'string', 'max' => 45],
            [['descricao'], 'string', 'max' => 250],
            [['categorias_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['categorias_id' => 'id']],
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
            'descricao' => 'Descricao',
            'preco' => 'Preco',
            'quantidade' => 'Quantidade',
            'categorias_id' => 'Categoria ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategorias()
    {
        return $this->hasOne(Categoria::class, ['id' => 'categorias_id']);
    }

    /**
     * Gets query for [[Linhafatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasfaturas()
    {
        return $this->hasMany(Linhafatura::class, ['medicamentos_id' => 'id']);
    }

}
