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

    /**
     * Obter nome da categoria (propriedade virtual)
     * @return string|null
     */
    public function getCategoriaNome()
    {
        // Se categorias já foi carregado (eager loading), usa o cache
        if ($this->isRelationPopulated('categorias')) {
            $categoria = $this->categorias;
            return $categoria ? $categoria->nome : null;
        }
        
        // Se não foi carregado, faz query única
        $categoria = $this->getCategorias()->one();
        return $categoria ? $categoria->nome : null;
    }

    /**
     * Obter objeto Categoria (propriedade virtual)
     * @return Categoria|null
     */
    public function getCategoria()
    {
        // Se categorias já foi carregado (eager loading), usa o cache
        if ($this->isRelationPopulated('categorias')) {
            return $this->categorias;
        }
        
        // Se não foi carregado, faz query única
        return $this->getCategorias()->one();
    }

    /**
     * Estatísticas de stock para dashboard
     */
    public static function getStockStats()
    {
        return [
            'total' => self::find()->where(['eliminado' => 0])->count(),
            'emStock' => self::find()->where(['>', 'quantidade', 9])->andWhere(['eliminado' => 0])->count(),
            'baixoStock' => self::find()->where(['between', 'quantidade', 5, 9])->andWhere(['eliminado' => 0])->count(),
            'critico' => self::find()->where(['<', 'quantidade', 5])->andWhere(['eliminado' => 0])->count(),
        ];
    }

    /**
     * Retorna medicamentos com stock crítico
     */
    public static function getMedicamentosCriticos()
    {
        return self::find()
            ->select(['nome', 'quantidade'])
            ->where(['<', 'quantidade', 5])
            ->andWhere(['eliminado' => 0])
            ->orderBy(['quantidade' => SORT_ASC])
            ->asArray()
            ->all();
    }

    /**
     * Verifica se há stock suficiente
     * @param int $quantidade Quantidade necessária
     * @return bool
     */
    public function temStockSuficiente($quantidade)
    {
        return $this->quantidade >= $quantidade;
    }

    /**
     * Decrementa o stock do medicamento
     * @param int $quantidade Quantidade a decrementar
     * @return bool True se bem sucedido, False se stock insuficiente
     */
    public function decrementarStock($quantidade)
    {
        if (!$this->temStockSuficiente($quantidade)) {
            return false;
        }
        
        $this->quantidade -= $quantidade;
        return $this->save(false);
    }

    /**
     * Incrementa o stock do medicamento (devolução)
     * @param int $quantidade Quantidade a incrementar
     * @return bool
     */
    public function incrementarStock($quantidade)
    {
        $this->quantidade += $quantidade;
        return $this->save(false);
    }

}
