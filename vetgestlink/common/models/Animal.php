<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "animais".
 *
 * @property int $id
 * @property string $nome
 * @property string $dtanascimento
 * @property float $peso
 * @property int $microship
 * @property string $sexo
 * @property int $especies_id
 * @property int $userprofiles_id
 * @property int|null $racas_id
 * @property int $eliminado
 *
 * @property Especie $especies
 * @property Marcacao[] $marcacoes
 * @property Nota[] $notas
 * @property Raca $racas
 * @property Userprofile $userprofiles
 */
class Animal extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * ENUM field values
     */
    const SEXO_M = 'M';
    const SEXO_F = 'F';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'animais';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['racas_id'], 'default', 'value' => null],
            [['eliminado'], 'default', 'value' => 0],
            [['nome', 'dtanascimento', 'peso', 'microship', 'sexo', 'especies_id', 'userprofiles_id'], 'required'],
            [['dtanascimento'], 'safe'],
            [['peso'], 'number'],
            [['microship', 'especies_id', 'userprofiles_id', 'racas_id', 'eliminado'], 'integer'],
            [['sexo'], 'string'],
            [['nome'], 'string', 'max' => 45],
            ['sexo', 'in', 'range' => array_keys(self::optsSexo())],
//            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['especies_id'], 'exist', 'skipOnError' => true, 'targetClass' => Especie::class, 'targetAttribute' => ['especies_id' => 'id']],
            [['racas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Raca::class, 'targetAttribute' => ['racas_id' => 'id']],
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
            'nome' => 'Nome',
            'dtanascimento' => 'Data de Nascimento',
            'peso' => 'Peso',
            'microship' => 'Microchip',
            'sexo' => 'Sexo',
            'especies_id' => 'Especies ID',
            'userprofiles_id' => 'Userprofiles ID',
            'racas_id' => 'Racas ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Especies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEspecies()
    {
        return $this->hasOne(Especie::class, ['id' => 'especies_id']);
    }

    /**
     * Gets query for [[Marcacos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarcacoes()
    {
        return $this->hasMany(Marcacao::class, ['animais_id' => 'id']);
    }

    /**
     * Gets query for [[Notas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotas()
    {
        return $this->hasMany(Nota::class, ['animais_id' => 'id'])
            ->orderBy(['create_at' => SORT_DESC]);// ordenar as notas por ordem descendente
    }

    /**
     * Gets query for [[Racas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRacas()
    {
        return $this->hasOne(Raca::class, ['id' => 'racas_id']);
    }

    /**
     * Gets query for [[Userprofiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserprofiles()
    {
        return $this->hasOne(Userprofile::class, ['id' => 'userprofiles_id']);
    }


    /**
     * column sexo ENUM value labels
     * @return string[]
     */
    public static function optsSexo()
    {
        return [
            self::SEXO_M => 'M',
            self::SEXO_F => 'F',
        ];
    }

    /**
     * @return string
     */
    public function displaySexo()
    {
        return self::optsSexo()[$this->sexo];
    }

    /**
     * @return bool
     */
    public function isSexoM()
    {
        return $this->sexo === self::SEXO_M;
    }

    public function setSexoToM()
    {
        $this->sexo = self::SEXO_M;
    }

    /**
     * @return bool
     */
    public function isSexoF()
    {
        return $this->sexo === self::SEXO_F;
    }

    public function setSexoToF()
    {
        $this->sexo = self::SEXO_F;
    }

    /**
     * Upload da imagem do animal usando o componente ImageUploader
     * @return bool
     */
    public function uploadImage()
    {
        return Yii::$app->imageUploader->upload($this->imageFile, 'animal', (string)$this->id);
    }

    /**
     * Obter URL da imagem do animal
     * @return string
     */
    public function getImageUrl()
    {
        return Yii::$app->imageUploader->getImageUrl('animal', (string)$this->id);
    }

    /**
     * Obter URL absoluta da imagem do animal (para API)
     * @return string
     */
    public function getImageAbsoluteUrl()
    {
        return Yii::$app->imageUploader->getImageAbsoluteUrl('animal', (string)$this->id);
    }

    /**
     * Calcular idade do animal em anos
     * @return int
     */
    public function getIdade()
    {
        if (empty($this->datanascimento)) {
            return 0;
        }

        $dataNascimento = new \DateTime($this->datanascimento);
        $hoje = new \DateTime();
        $idade = $hoje->diff($dataNascimento);

        return $idade->y; // retorna anos
    }

    /**
     * Deletar imagem do animal
     * @return void
     */
    public function deleteImage()
    {
        Yii::$app->imageUploader->deleteImage('animal', (string)$this->id);
    }

    /**
     * Verifica se o animal tem imagem
     * @return bool
     */
    public function hasImage()
    {
        return Yii::$app->imageUploader->imageExists('animal', (string)$this->id);
    }
}
