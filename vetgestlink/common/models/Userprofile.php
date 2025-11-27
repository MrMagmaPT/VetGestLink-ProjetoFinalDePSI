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
 * @property string|null $foto
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
            [['foto'], 'string', 'max' => 255],
            // image upload: optional, only PNG/JPG/JPEG, max 2MB
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 2 * 1024 * 1024, 'tooBig' => 'O ficheiro é demasiado grande. Máx 2MB.'],
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
            'foto' => 'Foto',
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
     * Upload da foto do utilizador usando o componente ImageUploader
     * @return bool
     */
    public function uploadImage()
    {
        // imageFile pode ser null
        if (!$this->imageFile instanceof UploadedFile) {
            return false;
        }

        // Apaga imagem antiga
        if (!empty($this->foto)) {
            Yii::$app->imageUploader->delete($this->foto);
        }

        // Faz upload usando o componente, retorna 'users/filename.ext' ou false
        $result = Yii::$app->imageUploader->upload($this->imageFile, $this->id);
        if ($result) {
            // Guardar apenas o nome do ficheiro na BD (basename)
            // Se o componente retornou 'users/name.ext' guardamos apenas 'name.ext'
            $this->foto = basename($result);
            return true;
        }

        return false;
    }

    /**
     * Retorna a URL pública da imagem
     */
    public function getImageUrl()
    {
        // Se não houver foto no perfil, usa default.jpg dentro do subdir configurado no ImageUploader
        if (empty($this->foto)) {
            if (!Yii::$app->has('imageUploader')) {
                return null;
            }
            $sub = trim(Yii::$app->imageUploader->subdir, '/');
            $storedPath = $sub !== '' ? $sub . '/default.jpg' : 'default.jpg';
        } else {
            // Se `foto` contém um caminho (ex: 'users/name.png'), usa-o; caso contrário prefixa com o subdir configurado
            $photo = ltrim($this->foto, "/\\");
            if (strpos($photo, '/') !== false) {
                $storedPath = $photo;
            } else {
                $sub = trim(Yii::$app->imageUploader->subdir, '/');
                $storedPath = ($sub !== '' ? $sub . '/' : '') . $photo;
            }
        }

        return Yii::$app->imageUploader->getUrl($storedPath);
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
