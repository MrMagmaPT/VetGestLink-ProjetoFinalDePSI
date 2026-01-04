<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "fatura".
 *
 * @property int $id
 * @property float $total
 * @property string $created_at
 * @property int $estado
 * @property int $metodospagamentos_id
 * @property int $userprofiles_id
 * @property int $eliminado
 *
 * @property Linhafatura[] $linhasfaturas
 * @property Metodopagamento $metodospagamentos
 * @property Userprofile $userprofile
 */
class Fatura extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false, // Não tem updated_at
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eliminado'], 'default', 'value' => 0],
            [['total', 'estado', 'userprofiles_id'], 'required'],
            [['created_at'], 'safe'],
            [['total'], 'number'],
            [['estado', 'metodospagamentos_id', 'userprofiles_id', 'eliminado'], 'integer'],
            // exist validator aceita null (não valida quando o valor é null)
            [['metodospagamentos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Metodopagamento::class, 'targetAttribute' => ['metodospagamentos_id' => 'id']],
            [['userprofiles_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userprofile::class, 'targetAttribute' => ['userprofiles_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total' => 'Total',
            'created_at' => 'Data de Criação',
            'estado' => 'Estado',
            'metodospagamentos_id' => 'Método de Pagamento',
            'userprofiles_id' => 'Cliente',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Linhafatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasfaturas()
    {
        return $this->hasMany(Linhafatura::class, ['faturas_id' => 'id'])
            ->where(['eliminado' => 0])
            ->with(['servicos', 'medicamentos', 'marcacoes.servicos', 'marcacoes.animais']);
    }

    /**
     * Gets query for [[Metodopagamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodospagamentos()
    {
        return $this->hasOne(Metodopagamento::class, ['id' => 'metodospagamentos_id']);
    }

    /**
     * Gets query for [[Userprofile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserprofiles()
    {
        return $this->hasOne(Userprofile::class, ['id' => 'userprofiles_id']);
    }

    /**
     * Obter data de criação formatada
     * @param string $format Formato de saída (padrão: 'Y-m-d H:i:s')
     * @return string|null
     */
    public function getCreatedAtFormatted($format = 'Y-m-d H:i:s')
    {
        return $this->created_at ? date($format, $this->created_at) : null;
    }


    /**
     * Obter data da fatura formatada (alias para created_at)
     * @param string $format Formato de saída (padrão: 'd/m/Y')
     * @return string|null
     */
    public function getDataFatura($format = 'd/m/Y')
    {
        return $this->getCreatedAtFormatted($format);
    }

    /**
     * Atualiza o total da fatura somando os totais das linhas associadas.
     */
    public function atualizarTotal()
    {
        $soma = $this->getLinhasFaturas()
            ->where(['eliminado' => 0])
            ->sum('total');
        $this->total = $soma ?: 0;
        $this->save(false, ['total']);
    }


    /**
     * Converte string vazia para null antes da validação.
     * Isto garante que um campo dropdown com prompt ('') será salvo como NULL na BD.
     */
    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        // normalizar empty string para null para o campo de método de pagamento
        if ($this->metodospagamentos_id === '') {
            $this->metodospagamentos_id = null;
        }

        return true;
    }

    /**
     * Obter nome do método de pagamento (propriedade virtual)
     * @return string|null
     */
    public function getMetodoPagamentoNome()
    {
        if ($this->isRelationPopulated('metodospagamentos')) {
            $metodo = $this->metodospagamentos;
            return $metodo ? $metodo->nome : null;
        }
        $metodo = $this->getMetodospagamentos()->one();
        return $metodo ? $metodo->nome : null;
    }

    /**
     * Obter nome completo do cliente (propriedade virtual)
     * @return string|null
     */
    public function getClienteNome()
    {
        if ($this->isRelationPopulated('userprofiles')) {
            $cliente = $this->userprofiles;
            return $cliente ? $cliente->nomecompleto : null;
        }
        $cliente = $this->getUserprofiles()->one();
        return $cliente ? $cliente->nomecompleto : null;
    }

    /**
     * Obter total de linhas da fatura (propriedade virtual)
     * @return int
     */
    public function getTotalLinhas()
    {
        if ($this->isRelationPopulated('linhasfaturas')) {
            return count($this->linhasfaturas);
        }
        return $this->getLinhasfaturas()->count();
    }

    /**
     * Adicionar linha de medicamento à fatura
     * @param int $medicamento_id ID do medicamento
     * @param int $quantidade Quantidade do medicamento
     * @param bool $vendidoEmConsulta Se foi vendido durante consulta
     * @return bool Se a linha foi adicionada com sucesso
     */
    public function adicionarLinhaMedicamento($medicamento_id, $quantidade = 1, $vendidoEmConsulta = false)
    {
        $medicamento = Medicamento::findOne($medicamento_id);
        if (!$medicamento) {
            return false;
        }

        $linha = new Linhafatura();
        $linha->faturas_id = $this->id;
        $linha->medicamentos_id = $medicamento_id;
        $linha->quantidade = $quantidade;
        $linha->total = $medicamento->preco * $quantidade;
        $linha->vendidoemconsulta = $vendidoEmConsulta ? 1 : 0;
        
        if ($linha->save()) {
            // Atualizar total da fatura
            $this->atualizarTotal();
            return true;
        }
        
        return false;
    }

    /**
     * Criar fatura automaticamente a partir de uma marcação
     * @param Marcacao $marcacao A marcação para gerar a fatura
     * @return Fatura|null Retorna a fatura criada ou null se houver erro
     */
    public static function criarDeMarcacao($marcacao)
    {
        if (!$marcacao || !$marcacao->id) {
            Yii::error('Marcação inválida para criar fatura', __METHOD__);
            return null;
        }

        // Verificar se já existe uma fatura para esta marcação
        $faturaExistente = Linhafatura::find()
            ->where(['marcacoes_id' => $marcacao->id])
            ->one();
        
        if ($faturaExistente) {
            Yii::warning("Já existe uma fatura para a marcação ID {$marcacao->id}", __METHOD__);
            return self::findOne($faturaExistente->faturas_id);
        }

        // Obter o ID do cliente (dono do animal)
        $clienteId = null;
        if ($marcacao->animais && $marcacao->animais->userprofiles_id) {
            $clienteId = $marcacao->animais->userprofiles_id;
        }

        if (!$clienteId) {
            Yii::error("Não foi possível obter o cliente da marcação ID {$marcacao->id}", __METHOD__);
            return null;
        }

        // Obter o valor do serviço
        $valorServico = 0;
        if ($marcacao->servicos && $marcacao->servicos->valor) {
            $valorServico = $marcacao->servicos->valor;
        }

        // Criar a fatura
        $fatura = new self();
        $fatura->userprofiles_id = $clienteId;
        $fatura->estado = 0; // Pendente
        $fatura->total = $valorServico;
        $fatura->metodospagamentos_id = null; // Será definido quando a fatura for paga
        
        if (!$fatura->save()) {
            Yii::error("Erro ao criar fatura: " . json_encode($fatura->errors), __METHOD__);
            return null;
        }

        // Criar linha de fatura para o serviço da marcação
        $linha = new Linhafatura();
        $linha->faturas_id = $fatura->id;
        $linha->marcacoes_id = $marcacao->id;
        $linha->quantidade = 1;
        $linha->total = $valorServico;
        $linha->vendidoemconsulta = 0;
        
        if (!$linha->save()) {
            Yii::error("Erro ao criar linha de fatura: " . json_encode($linha->errors), __METHOD__);
            // Deletar a fatura criada se a linha falhar
            $fatura->delete();
            return null;
        }

        // Atualizar total da fatura (garantir consistência)
        $fatura->atualizarTotal();
        
        Yii::info("Fatura ID {$fatura->id} criada com sucesso para marcação ID {$marcacao->id}", __METHOD__);
        return $fatura;
    }
}
