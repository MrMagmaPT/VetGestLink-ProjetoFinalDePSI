<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;
use common\validators\NifValidator;
use common\validators\BirthValidator;

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
            ['nomecompleto', 'required', 'message' => 'O nome completo é obrigatório.'],
            ['nif', 'required', 'message' => 'O NIF é obrigatório.'],
            ['nif', NifValidator::class],
            ['telemovel', 'required', 'message' => 'O telemóvel é obrigatório.'],
            ['dtanascimento', 'required', 'message' => 'A data de nascimento é obrigatória.'],
            ['dtanascimento', BirthValidator::class],
            ['user_id', 'required', 'message' => 'Dados do Userprofile estão inválidos, corrija para criar o User.'],
            [['dtanascimento'], 'safe'],
            [['user_id', 'eliminado'], 'integer'],
            [['nomecompleto'], 'string', 'max' => 45],
            [['nif', 'telemovel'], 'string', 'max' => 9],
            [['nif'], 'unique'],
            [['foto'], 'string', 'max' => 255],
            // image upload: optional, only PNG/JPG/JPEG, max 2MB
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'wrongExtension' => 'Apenas ficheiros PNG, JPG ou JPEG são permitidos.', 'maxSize' => 2 * 1024 * 1024, 'tooBig' => 'O ficheiro é demasiado grande. Máx 2MB.', 'checkExtensionByMimeType' => false],
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
     */
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
        // Se não há ficheiro para upload, retorna true (não é erro)
        if (!$this->imageFile instanceof UploadedFile) {
            Yii::info('uploadImage: No file to upload', __METHOD__);
            return true;
        }

        // Usar user_id como identificador (mais confiável que id do profile que pode não existir ainda)
        $userId = $this->user_id ?? $this->id ?? 'temp';
        Yii::info('uploadImage: Starting upload for user ' . $userId, __METHOD__);
        Yii::info('uploadImage: File name: ' . $this->imageFile->name, __METHOD__);
        Yii::info('uploadImage: File size: ' . $this->imageFile->size, __METHOD__);
        Yii::info('uploadImage: File type: ' . $this->imageFile->type, __METHOD__);

        // Apaga imagem antiga (se existir e não for a default)
        if (!empty($this->foto) && !Yii::$app->imageUploader->isDefault($this->foto)) {
            Yii::info('uploadImage: Deleting old photo: ' . $this->foto, __METHOD__);
            Yii::$app->imageUploader->delete($this->foto);
        }

        // Validar se o arquivo é válido antes do upload
        if ($this->imageFile->hasError) {
            Yii::error('uploadImage: File has error code: ' . $this->imageFile->error, __METHOD__);
            return false;
        }

        // Faz upload usando o componente, retorna 'users/filename.ext' ou false
        $result = Yii::$app->imageUploader->upload($this->imageFile, $userId);
        
        if ($result) {
            // Guardar apenas o nome do ficheiro na BD (basename)
            // Se o componente retornou 'users/name.ext' guardamos apenas 'name.ext'
            $this->foto = basename($result);
            Yii::info('uploadImage: Upload successful. Saved as: ' . $this->foto, __METHOD__);
            return true;
        }

        Yii::error('uploadImage: Upload FAILED for user ' . $userId, __METHOD__);
        Yii::error('uploadImage: Check application logs for detailed error from ImageUploader component', __METHOD__);
        return false;
    }

    /**
     * Retorna a URL pública da imagem
     */
    public function getImageUrl()
    {
        // Verificar se o componente está disponível
        if (!Yii::$app->has('imageUploader')) {
            Yii::error('getImageUrl: imageUploader component not found', __METHOD__);
            return null;
        }

        // Se não houver foto no perfil, usa default.jpg dentro do subdir configurado no ImageUploader
        if (empty($this->foto)) {
            Yii::info('getImageUrl: No photo set for user ' . $this->id . ', using default', __METHOD__);
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
            Yii::info('getImageUrl: Using photo: ' . $this->foto . ' -> storedPath: ' . $storedPath, __METHOD__);
        }

        $url = Yii::$app->imageUploader->getUrl($storedPath);
        Yii::info('getImageUrl: Final URL: ' . $url, __METHOD__);
        return $url;
    }

    
    /**
     * Verifica se a imagem do perfil é a imagem padrão (default.jpg)
     * @return bool
     */
    public function isDefaultImage()
    {
        if (empty($this->foto)) {
            return true;
        }
        $foto = strtolower(trim($this->foto));
        return $foto === 'default.jpg' || substr($foto, -11) === '/default.jpg';
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

    /**
     * Obter cidade da primeira morada (propriedade virtual)
     * @return string|null
     */
    public function getMoradaCidade()
    {
        // Se moradas já foi carregado (eager loading), usa o cache
        if ($this->isRelationPopulated('moradas')) {
            $moradas = $this->moradas;
            return !empty($moradas) ? $moradas[0]->cidade : null;
        }
        
        // Se não foi carregado, faz query única
        $morada = $this->getMoradas()->one();
        return $morada ? $morada->cidade : null;
    }
}
