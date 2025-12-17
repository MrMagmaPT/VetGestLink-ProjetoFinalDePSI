<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required', 'message' => '{attribute} é obrigatório.'],
            // email has to be a valid email address
            ['email', 'email', 'message' => 'Por favor, insira um endereço de email válido.'],
            // string length validation
            ['name', 'string', 'min' => 2, 'max' => 100, 'tooShort' => 'O nome deve ter pelo menos 2 caracteres.', 'tooLong' => 'O nome não pode ter mais de 100 caracteres.'],
            ['subject', 'string', 'min' => 5, 'max' => 200, 'tooShort' => 'O assunto deve ter pelo menos 5 caracteres.', 'tooLong' => 'O assunto não pode ter mais de 200 caracteres.'],
            ['body', 'string', 'min' => 10, 'max' => 5000, 'tooShort' => 'A mensagem deve ter pelo menos 10 caracteres.', 'tooLong' => 'A mensagem não pode ter mais de 5000 caracteres.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Nome',
            'email' => 'E-mail',
            'subject' => 'Assunto',
            'body' => 'Mensagem',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
