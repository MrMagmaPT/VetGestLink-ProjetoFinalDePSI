<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;

/**
 * Componente para gerenciamento de upload de imagens
 */
class ImageUploader extends Component
{
    /**
     * Extensões de imagem permitidas
     */
    const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    /**
     * Pasta base para upload de imagens
     * Usa caminho absoluto da raiz do projeto
     */
    public $uploadPath;

    /**
     * Pasta para imagens padrão
     */
    public $defaultImagePath;

    /**
     * Inicialização do componente
     */
    public function init()
    {
        parent::init();

        // Define caminho absoluto para a pasta uploads na raiz do projeto
        if ($this->uploadPath === null) {
            $this->uploadPath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'uploads';
        }

        if ($this->defaultImagePath === null) {
            $this->defaultImagePath = $this->uploadPath . DIRECTORY_SEPARATOR . 'defaults';
        }
    }

    /**
     * Faz upload de uma imagem
     */
    public function upload($file, $folder, $filename)
    {
        if (!$file) {
            return false;
        }

        if (!in_array(strtolower($file->extension), self::ALLOWED_EXTENSIONS)) {
            return false;
        }

        $targetDir = $this->uploadPath . DIRECTORY_SEPARATOR . $folder;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $this->deleteImage($folder, $filename);

        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $filename . '.' . $file->extension;
        return $file->saveAs($targetPath);
    }

    /**
     * Obtém a URL de uma imagem
     */
    public function getImageUrl($folder, $filename, $defaultImage = 'no-image.png')
    {
        foreach (self::ALLOWED_EXTENSIONS as $ext) {
            $path = $this->uploadPath . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename . '.' . $ext;
            if (file_exists($path)) {
                return Yii::getAlias('@web/uploads/' . $folder . '/' . $filename . '.' . $ext);
            }
        }

        return Yii::getAlias('@web/images/' . $defaultImage);
    }

    /**
     * Obtém a URL absoluta de uma imagem (para uso em API)
     */
    public function getImageAbsoluteUrl($folder, $filename, $defaultImage = 'no-image.png')
    {
        $relativeUrl = $this->getImageUrl($folder, $filename, $defaultImage);

        $scheme = isset(Yii::$app->request) && !Yii::$app->request->isConsoleRequest
            ? (Yii::$app->request->isSecureConnection ? 'https' : 'http')
            : 'http';

        $host = isset(Yii::$app->request) && !Yii::$app->request->isConsoleRequest
            ? Yii::$app->request->hostInfo
            : 'http://localhost';

        return $host . $relativeUrl;
    }

    /**
     * Deleta uma imagem
     */
    public function deleteImage($folder, $filename)
    {
        foreach (self::ALLOWED_EXTENSIONS as $ext) {
            $path = $this->uploadPath . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename . '.' . $ext;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    /**
     * Valida se o arquivo é uma imagem válida
     */
    public function validateImage($file)
    {
        if (!$file) {
            return false;
        }

        return in_array(strtolower($file->extension), self::ALLOWED_EXTENSIONS);
    }

    /**
     * Obtém o caminho físico de uma imagem
     */
    public function getImagePath($folder, $filename)
    {
        foreach (self::ALLOWED_EXTENSIONS as $ext) {
            $path = $this->uploadPath . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename . '.' . $ext;
            if (file_exists($path)) {
                return $path;
            }
        }
        return null;
    }

    /**
     * Verifica se existe uma imagem
     */
    public function imageExists($folder, $filename)
    {
        return $this->getImagePath($folder, $filename) !== null;
    }
}
