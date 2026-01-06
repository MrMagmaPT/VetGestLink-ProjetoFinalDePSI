<?php

namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class ImageUploadWidget extends Widget
{
    public $model;
    public $attribute = 'imageFile';
    public $previewId;
    public $defaultImage;
    public $imageSize = 150;
    public $borderColor = '#28a745';
    public $helpText = '';
    public $hint = '';
    public $label = 'Imagem de Perfil';
    public $accept = 'image/png, image/jpeg, image/jpg';
    public $form;

    public function init()
    {
        parent::init();
        
        if ($this->previewId === null) {
            $this->previewId = 'image-preview-' . $this->getId();
        }
        
        if ($this->defaultImage === null) {
            $this->defaultImage = \Yii::getAlias('@uploadsUrl') . '/users/default.jpg';
        }
    }

    public function run()
    {
        $inputId = Html::getInputId($this->model, $this->attribute);
        
        return $this->render('imageUpload', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'previewId' => $this->previewId,
            'defaultImage' => $this->defaultImage,
            'imageSize' => $this->imageSize,
            'borderColor' => $this->borderColor,
            'helpText' => $this->helpText,
            'hint' => $this->hint,
            'label' => $this->label,
            'accept' => $this->accept,
            'inputId' => $inputId,
            'form' => $this->form,
        ]);
    }
}
