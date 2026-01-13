<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use Bluerhinos\phpMQTT;
use common\components\MqttHelper;

/**
 * This is the model class for table "marcacao".
 *
 * @property int $id
 * @property string $data
 * @property string $horainicio
 * @property string $horafim
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $diagnostico
 * @property string $estado
 * @property int $servicos_id
 * @property int $animais_id
 * @property int $userprofiles_id
 * @property int $eliminado
 *
 * @property Animal $animais
 * @property Servico $servicos
 * @property Linhafatura[] $linhasfaturas
 * @property Userprofile $userprofiles
 */
class Marcacao extends \yii\db\ActiveRecord
{
    /**
     * ENUM field values
     */
    const ESTADO_PENDENTE = 'pendente';
    const ESTADO_CANCELADA = 'cancelada';
    const ESTADO_REALIZADA = 'realizada';

    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function tableName()
    {
        return 'marcacoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['diagnostico'], 'default', 'value' => null],
            [['eliminado'], 'default', 'value' => 0],
            ['data', 'required', 'message' => 'A data da marcação é obrigatória.'],
            ['horainicio', 'required', 'message' => 'A hora de início é obrigatória.'],
            ['horafim', 'required', 'message' => 'A hora de fim é obrigatória.'],
            ['estado', 'required', 'message' => 'O estado da marcação é obrigatório.'],
            ['servicos_id', 'required', 'message' => 'O serviço é obrigatório.'],
            ['animais_id', 'required', 'message' => 'O animal é obrigatório.'],
            ['userprofiles_id', 'required', 'message' => 'O veterinário é obrigatório.'],
            [['data', 'horainicio', 'horafim'], 'safe'],
            [['estado'], 'string'],
            [['servicos_id', 'animais_id', 'userprofiles_id', 'eliminado'], 'integer'],
            [['diagnostico'], 'string', 'max' => 500],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['servicos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Servico::class, 'targetAttribute' => ['servicos_id' => 'id']],
            [['animais_id'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::class, 'targetAttribute' => ['animais_id' => 'id']],
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
            'data' => 'Data',
            'horainicio' => 'Hora Início',
            'horafim' => 'Hora Fim',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
            'diagnostico' => 'Diagnóstico',
            'estado' => 'Estado',
            'servicos_id' => 'Serviço',
            'animais_id' => 'Animal',
            'userprofiles_id' => 'Cliente',
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
        return $this->hasOne(Animal::class, ['id' => 'animais_id']);
    }

    /**
     * Gets query for [[Linhafatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasfaturas()
    {
        return $this->hasMany(Linhafatura::class, ['marcacoes_id' => 'id']);
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
     * Gets query for [[Servico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServicos()
    {
        return $this->hasOne(Servico::class, ['id' => 'servicos_id']);
    }

        /**
     * Gets query for medications used in this appointment (marcacao).
     * Returns linhasfaturas that have both marcacoes_id and medicamentos_id,
     * with vendidoemconsulta flag set to 1.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedicamentosUtilizados()
    {
        return $this->hasMany(Medicamento::class, ['id' => 'medicamentos_id'])
            ->viaTable('linhasfaturas', ['marcacoes_id' => 'id'], function($query) {
                $query->andWhere(['vendidoemconsulta' => 1])
                      ->andWhere(['eliminado' => 0])
                      ->andWhere(['IS NOT', 'medicamentos_id', null]);
            });
    }

    /**
     * Gets the linhasfaturas records for medications used in this appointment.
     * Useful to get quantity and total price information.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasfaturasMedicamentos()
    {
        return $this->hasMany(Linhafatura::class, ['marcacoes_id' => 'id'])
            ->andWhere(['vendidoemconsulta' => 1])
            ->andWhere(['eliminado' => 0])
            ->andWhere(['IS NOT', 'medicamentos_id', null])
            ->with('medicamentos');
    }


    /**
     * column estado ENUM value labels
     * @return string[]
     */
    public static function optsEstado()
    {
        return [
            self::ESTADO_PENDENTE => 'pendente',
            self::ESTADO_CANCELADA => 'cancelada',
            self::ESTADO_REALIZADA => 'realizada',
        ];
    }


    /**
     * Verifica se a marcação já tem fatura associada
     * @return bool
     */
    public function temFatura()
    {
        return Linhafatura::find()
            ->where(['marcacoes_id' => $this->id])
            ->exists();
    }

    /**
     * @return string
     */
    public function displayEstado()
    {
        return self::optsEstado()[$this->estado];
    }

    /**
     * @return bool
     */
    public function isEstadoPendente()
    {
        return $this->estado === self::ESTADO_PENDENTE;
    }

    public function setEstadoToPendente()
    {
        $this->estado = self::ESTADO_PENDENTE;
    }

    /**
     * @return bool
     */
    public function isEstadoCancelada()
    {
        return $this->estado === self::ESTADO_CANCELADA;
    }

    public function setEstadoToCancelada()
    {
        $this->estado = self::ESTADO_CANCELADA;
    }

    /**
     * @return bool
     */
    public function isEstadoRealizada()
    {
        return $this->estado === self::ESTADO_REALIZADA;
    }

    public function setEstadoToRealizada()
    {
        $this->estado = self::ESTADO_REALIZADA;
    }

    /**
     * Obter nome do animal (propriedade virtual)
     * @return string|null
     */
    public function getAnimalNome()
    {
        if ($this->isRelationPopulated('animais')) {
            $animal = $this->animais;
            return $animal ? $animal->nome : null;
        }
        $animal = $this->getAnimais()->one();
        return $animal ? $animal->nome : null;
    }

    /**
     * Obter nome completo do veterinário (propriedade virtual)
     * @return string|null
     */
    public function getVeterinarioNome()
    {
        if ($this->isRelationPopulated('userprofiles')) {
            $vet = $this->userprofiles;
            return $vet ? $vet->nomecompleto : null;
        }
        $vet = $this->getUserprofiles()->one();
        return $vet ? $vet->nomecompleto : null;
    }

    /**
     * Obter nome do serviço (propriedade virtual)
     * @return string|null
     */
    public function getServicoNome()
    {
        // Verificar se a relação já foi carregada
        if ($this->isRelationPopulated('servicos')) {
            //Buscar via relação
            $servico = $this->servicos;
            
            // Retornar nome ou null
            return $servico ? $servico->nome : null;
        }
        // Buscar via relação
        $servico = $this->getServicos()->one();

        // Retornar nome ou null
        return $servico ? $servico->nome : null;
    }

    /**
     * Processa os medicamentos associados à marcação durante o update
     * @param array $medicamentosData Array com IDs e quantidades dos medicamentos ['medicamento_id' => quantidade]
     * @param Fatura $fatura Objeto da fatura associada
     * @return array ['success' => bool, 'errors' => array]
     */
    public function processarMedicamentos($medicamentosData, $fatura)
    {
        $errors = [];
        
        // Obter medicamentos atuais (já associados à marcação)
        $medicamentosAtuaisMap = [];
        $linhasAtuais = Linhafatura::find()
            ->where([
                'marcacoes_id' => $this->id, 
                'vendidoemconsulta' => 1, 
                'eliminado' => 0
            ])
            ->andWhere(['IS NOT', 'medicamentos_id', null])
            ->all();
        
        foreach ($linhasAtuais as $linha) {
            $medicamentosAtuaisMap[$linha->medicamentos_id] = [
                'linha_id' => $linha->id,
                'quantidade' => $linha->quantidade
            ];
        }
        
        $medicamentosProcessados = [];
        
        // Processar cada medicamento selecionado
        if (!empty($medicamentosData)) {
            foreach ($medicamentosData as $medicamentoId => $quantidade) {
                if ($quantidade <= 0) continue;
                
                $medicamento = Medicamento::findOne($medicamentoId);
                if (!$medicamento) {
                    $errors[] = "Medicamento com ID {$medicamentoId} não encontrado.";
                    continue;
                }
                
                // Se já existe, atualizar quantidade
                if (isset($medicamentosAtuaisMap[$medicamentoId])) {
                    $linhaExistente = Linhafatura::findOne($medicamentosAtuaisMap[$medicamentoId]['linha_id']);
                    if ($linhaExistente) {
                        $quantidadeAnterior = $linhaExistente->quantidade;
                        $diferenca = $quantidade - $quantidadeAnterior;
                        
                        if ($diferenca != 0) {
                            // Verificar stock suficiente para aumento
                            if ($diferenca > 0 && !$medicamento->temStockSuficiente($diferenca)) {
                                $errors[] = "Stock insuficiente para {$medicamento->nome}. Disponível: {$medicamento->quantidade}";
                                continue;
                            }
                            
                            // Atualizar stock
                            if ($diferenca > 0) {
                                $medicamento->decrementarStock($diferenca);
                            } else {
                                $medicamento->incrementarStock(abs($diferenca));
                            }
                            
                            // Atualizar linha
                            $linhaExistente->quantidade = $quantidade;
                            $linhaExistente->total = $linhaExistente->calcularTotal();
                            $linhaExistente->save(false);
                        }
                        
                        $medicamentosProcessados[] = $medicamentoId;
                    }
                } else {
                    // Criar nova linha
                    if (!$medicamento->temStockSuficiente($quantidade)) {
                        $errors[] = "Stock insuficiente para {$medicamento->nome}. Disponível: {$medicamento->quantidade}";
                        continue;
                    }
                    
                    $novaLinha = new Linhafatura();
                    $novaLinha->marcacoes_id = $this->id;
                    $novaLinha->medicamentos_id = $medicamentoId;
                    $novaLinha->quantidade = $quantidade;
                    $novaLinha->vendidoemconsulta = 1;
                    $novaLinha->faturas_id = $fatura->id;
                    $novaLinha->total = $novaLinha->calcularTotal();
                    
                    if ($novaLinha->save(false)) {
                        $medicamento->decrementarStock($quantidade);
                        $medicamentosProcessados[] = $medicamentoId;
                    }
                }
            }
        }
        
        // Remover medicamentos desmarcados
        foreach ($medicamentosAtuaisMap as $medicamentoId => $info) {
            if (!in_array($medicamentoId, $medicamentosProcessados)) {
                // Restaurar stock
                $medicamento = Medicamento::findOne($medicamentoId);
                if ($medicamento) {
                    $medicamento->incrementarStock($info['quantidade']);
                }
                
                // Eliminar linha
                $linha = Linhafatura::findOne($info['linha_id']);
                if ($linha) {
                    $linha->delete();
                }
            }
        }
        
        return [
            'success' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Cria a fatura inicial para a marcação
     * @return Fatura|null
     */
    public function criarFaturaInicial()
    {
        // Obter o dono do animal (userprofile) através da marcação
        $animal = $this->animais;
        $userprofileId = $animal ? $animal->userprofiles_id : null;
        
        if (!$userprofileId) {
            return null; // Não pode criar fatura sem dono
        }
        
        $fatura = new Fatura();
        $fatura->data = date('Y-m-d');
        $fatura->estado = 0; // Não paga
        $fatura->metodopagamentos_id = 1; // Método padrão
        $fatura->total = 0; // Será atualizado depois
        $fatura->userprofiles_id = $userprofileId; // Associar ao dono do animal
        
        if ($fatura->save(false)) {
            // Criar linha com o serviço da marcação
            $linha = new Linhafatura();
            $linha->marcacoes_id = $this->id;
            $linha->faturas_id = $fatura->id;
            $linha->quantidade = 1;
            $linha->vendidoemconsulta = 1;
            $linha->total = $linha->calcularTotal();
            $linha->save(false);
            
            return $fatura;
        }
        
        return null;
    }

     // Publicar alterações no MQTT após salvar ou deletar
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        // Obter dados relevantes da marcação
        $myObj = new \stdClass();
        $myObj->id = $this->id;
        $myObj->data = $this->data;
        $myObj->horainicio = $this->horainicio;
        $myObj->horafim = $this->horafim;
        $myObj->estado = $this->estado;
        $myObj->servicos_id = $this->servicos_id;
        $myObj->animais_id = $this->animais_id;
        $myObj->userprofiles_id = $this->userprofiles_id; // ID do Veterinário
        $myObj->diagnostico = $this->diagnostico;
        $myObj->created_at = $this->created_at;
        $myObj->updated_at = $this->updated_at;

        // CORREÇÃO: Obter o ID do Cliente (Dono do Animal)
        $clienteId = null;
        if ($this->animais) {
            $clienteId = $this->animais->userprofiles_id;
        } else {
            // Tenta buscar via relação se não estiver carregada
            $animal = $this->getAnimais()->one();
            if ($animal) {
                $clienteId = $animal->userprofiles_id;
            }
        }

        // Só envia se tivermos um cliente identificado
        if ($clienteId) {
            $myJSON = json_encode($myObj);
            
            // Adiciona o ID do cliente ao JSON para validação extra na App
            $myObj->id_cliente = $clienteId;
            $myJSON = json_encode($myObj);

            if ($insert) {
                // Envia para o tópico do CLIENTE
                MqttHelper::publish("INSERT_" . $clienteId . "_MARCACAO", $myJSON);
            } else {
                MqttHelper::publish("UPDATE_" . $clienteId . "_MARCACAO", $myJSON);
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        
        $myObj = new \stdClass();
        $myObj->id = $this->id;
        
        // CORREÇÃO: Obter o ID do Cliente para notificar a remoção
        $clienteId = null;
        // Nota: No afterDelete o registo já foi apagado, mas os dados ainda estão em memória no $this
        if ($this->animais) {
            $clienteId = $this->animais->userprofiles_id;
        } else {
            $animal = $this->getAnimais()->one();
            if ($animal) {
                $clienteId = $animal->userprofiles_id;
            }
        }

        if ($clienteId) {
            $myJSON = json_encode($myObj);
            MqttHelper::publish("DELETE_" . $clienteId . "_MARCACAO", $myJSON);
        }
    }


}
