<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "notas".
 *
 * @property int $id
 * @property string $nota
 * @property string $created_at
 * @property int $userprofiles_id
 * @property int $animais_id
 *
 * @property Animal $animal
 * @property Userprofile $userprofile
 */
class Nota extends \yii\db\ActiveRecord
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
                'updatedAtAttribute' => 'false',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nota', 'animais_id', 'userprofiles_id'], 'required'],
            [['nota'], 'string', 'max' => 500],
            [['animais_id', 'userprofiles_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'nota' => 'Nota',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
            'userprofiles_id' => 'Utilizador',
            'animais_id' => 'Animal',
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
     * Gets query for [[Userprofile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserprofiles()
    {
        return $this->hasOne(Userprofile::class, ['id' => 'userprofiles_id']);
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notas".
 *
 * @property int $id
 * @property string $nota
 * @property string $create_at
 * @property int $userprofiles_id
 * @property int $animais_id
 *
 * @property Animal $animais
 * @property Userprofile $userprofiles
 */
class Nota extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nota', 'create_at', 'userprofiles_id', 'animais_id'], 'required'],
            [['create_at'], 'safe'],
            [['userprofiles_id', 'animais_id'], 'integer'],
            [['nota'], 'string', 'max' => 500],
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
            'nota' => 'Nota',
            'create_at' => 'Create At',
            'userprofiles_id' => 'Userprofiles ID',
            'animais_id' => 'Animais ID',
        ];
    }

    /**
     * Gets query for [[Animais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimais()
    {
        return $this->hasOne(Animal::class, ['id' => 'animais_id']);
    }

    /**
     * Gets query for [[UserProfiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserprofiles()
    {
        return $this->hasOne(Userprofile::class, ['id' => 'userprofiles_id']);
    }

}
