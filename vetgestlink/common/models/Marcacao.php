<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


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
        if ($this->isRelationPopulated('servicos')) {
            $servico = $this->servicos;
            return $servico ? $servico->nome : null;
        }
        $servico = $this->getServicos()->one();
        return $servico ? $servico->nome : null;
    }




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
        $myObj->userprofiles_id = $this->userprofiles_id;
        $myObj->diagnostico = $this->diagnostico;
        $myObj->created_at = $this->created_at;
        $myObj->updated_at = $this->updated_at;
        
        $myJSON = json_encode($myObj);
        if ($insert) {
            $this->FazPublishNoMosquitto("INSERT", $myJSON);
        } else {
            $this->FazPublishNoMosquitto("UPDATE", $myJSON);
        }
    }


    public function afterDelete()
    {
        parent::afterDelete();
        $myObj = new \stdClass();
        $myObj->id = $this->id;
        $myJSON = json_encode($myObj);
        $this->FazPublishNoMosquitto("DELETE", $myJSON);
    }

    public function FazPublishNoMosquitto($canal, $msg)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $username = ""; // defina se necessário
        $password = ""; // defina se necessário
        $client_id = "phpMQTT-publisher"; // deve ser único
        $mqtt = new \Bluerhinos\phpmqtt($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        } else {
            file_put_contents("debug.output", "Time out!");
        }
    }
}
