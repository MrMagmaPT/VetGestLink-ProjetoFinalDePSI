<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "userprofiles".
 *
 * @property int $id
 * @property string $nomecompleto
 * @property string $nif
 * @property string $telemovel
 * @property string $dtanascimento
 * @property int $user_id
 * @property int $eliminado
 *
 * @property Animal[] $animais
 * @property Fatura[] $faturas
 * @property Marcacao[] $marcacoes
 * @property Morada[] $moradas
 * @property Nota[] $notas
 * @property User $user
 */
class Userprofile extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userprofiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eliminado'], 'default', 'value' => 0],
            [['nomecompleto', 'nif', 'telemovel', 'dtanascimento', 'user_id'], 'required'],
            [['dtanascimento'], 'safe'],
            [['user_id', 'eliminado'], 'integer'],
            [['nomecompleto'], 'string', 'max' => 45],
            [['nif', 'telemovel'], 'string', 'max' => 9],
            [['nif'], 'unique'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomecompleto' => 'Nome Completo',
            'nif' => 'NIF',
            'telemovel' => 'Telemóvel',
            'dtanascimento' => 'Data de Nascimento',
            'user_id' => 'User ID',
            'eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Animais]].
     *
     * @return \yii\db\ActiveQuery
    public function getAnimais()
    {
        return $this->hasMany(Animal::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Fatura::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Marcacos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarcacoes()
    {
        return $this->hasMany(Marcacao::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Moradas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoradas()
    {
        return $this->hasMany(Morada::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[Notas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotas()
    {
        return $this->hasMany(Nota::class, ['userprofiles_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * Upload da imagem do utilizador usando o componente ImageUploader
     * @return bool
     */
    public function uploadImage()
    {
        return Yii::$app->imageUploader->upload($this->imageFile, 'users', (string)$this->id);
    }

    /**
     * Obter URL da imagem do utilizador
     * @return string
     */
    public function getImageUrl()
    {
        return Yii::$app->imageUploader->getImageUrl('users', (string)$this->id);
    }

    /**
     * Obter URL absoluta da imagem do utilizador (para API)
     * @return string
     */
    public function getImageAbsoluteUrl()
    {
        return Yii::$app->imageUploader->getImageAbsoluteUrl('users', (string)$this->id);
    }

    /**
     * Deletar imagem do utilizador
     * @return void
     */
    public function deleteImage()
    {
        Yii::$app->imageUploader->deleteImage('users', (string)$this->id);
    }

    /**
     * Verifica se o utilizador tem imagem
     * @return bool
     */
    public function hasImage()
    {
        return Yii::$app->imageUploader->imageExists('users', (string)$this->id);
    }

    /**
     * Obter data de registo do utilizador (do modelo User)
     * @param string $format Formato de saída (padrão: 'Y-m-d H:i:s')
     * @return string|null
     */
    public function getCreatedAt($format = 'Y-m-d H:i:s')
    {
        if (!$this->user || !$this->user->created_at) {
            return null;
        }
        return date($format, $this->user->created_at);
    }

    /**
     * Obter data de última atualização do utilizador (do modelo User)
     * @param string $format Formato de saída (padrão: 'Y-m-d H:i:s')
     * @return string|null
     */
    public function getUpdatedAt($format = 'Y-m-d H:i:s')
    {
        if (!$this->user || !$this->user->updated_at) {
            return null;
        }
        return date($format, $this->user->updated_at);
    }

    /**
     * Obter timestamp de registo (para compatibilidade)
     * @return int|null
     */
    public function getCreatedAtTimestamp()
    {
        return $this->user ? $this->user->created_at : null;
    }

    /**
     * Obter timestamp de atualização (para compatibilidade)
     * @return int|null
     */
    public function getUpdatedAtTimestamp()
    {
        return $this->user ? $this->user->updated_at : null;
    }
}
