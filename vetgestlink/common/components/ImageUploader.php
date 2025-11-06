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
     * Agora usa pasta compartilhada na raiz do projeto
     */
    public $uploadPath = '@app/../uploads';

    /**
     * Pasta para imagens padrão
     */
    public $defaultImagePath = '@app/../uploads/defaults';

    /**
     * Faz upload de uma imagem
     *
     * @param UploadedFile $file Arquivo a ser enviado
     * @param string $folder Subpasta dentro de uploads (ex: 'animais')
     * @param string $filename Nome do arquivo sem extensão
     * @return bool
     */
    public function upload($file, $folder, $filename)
    {
        if (!$file) {
            return false;
        }

        // Valida a extensão
        if (!in_array(strtolower($file->extension), self::ALLOWED_EXTENSIONS)) {
            return false;
        }

        // Cria o diretório se não existir
        $targetDir = Yii::getAlias($this->uploadPath) . DIRECTORY_SEPARATOR . $folder;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Remove arquivo antigo se existir
        $this->deleteImage($folder, $filename);

        // Salva o novo arquivo
        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $filename . '.' . $file->extension;
        return $file->saveAs($targetPath);
    }

    /**
     * Obtém a URL de uma imagem
     *
     * @param string $folder Subpasta dentro de uploads (ex: 'animais')
     * @param string $filename Nome do arquivo sem extensão
     * @param string $defaultImage Nome da imagem padrão (opcional)
     * @return string URL da imagem ou imagem padrão
     */
    public function getImageUrl($folder, $filename, $defaultImage = 'no-image.svg')
    {
        foreach (self::ALLOWED_EXTENSIONS as $ext) {
            $path = Yii::getAlias($this->uploadPath) . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename . '.' . $ext;
            if (file_exists($path)) {
                // Retorna URL relativa que funciona em backend e frontend
                return '/uploads/' . $folder . '/' . $filename . '.' . $ext;
            }
        }

        // Retorna imagem padrão
        return '/images/' . $defaultImage;
    }

    /**
     * Obtém a URL absoluta de uma imagem (para uso em API)
     *
     * @param string $folder Subpasta dentro de uploads (ex: 'animais')
     * @param string $filename Nome do arquivo sem extensão
     * @param string $defaultImage Nome da imagem padrão (opcional)
     * @return string URL absoluta da imagem
     */
    public function getImageAbsoluteUrl($folder, $filename, $defaultImage = 'no-image.svg')
    {
        $relativeUrl = $this->getImageUrl($folder, $filename, $defaultImage);

        // Determina o domínio base
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
     *
     * @param string $folder Subpasta dentro de uploads (ex: 'animais')
     * @param string $filename Nome do arquivo sem extensão
     * @return void
     */
    public function deleteImage($folder, $filename)
    {
        foreach (self::ALLOWED_EXTENSIONS as $ext) {
            $path = Yii::getAlias($this->uploadPath) . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename . '.' . $ext;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    /**
     * Valida se o arquivo é uma imagem válida
     *
     * @param UploadedFile $file
     * @return bool
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
     *
     * @param string $folder Subpasta dentro de uploads
     * @param string $filename Nome do arquivo sem extensão
     * @return string|null
     */
    public function getImagePath($folder, $filename)
    {
        foreach (self::ALLOWED_EXTENSIONS as $ext) {
            $path = Yii::getAlias($this->uploadPath) . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename . '.' . $ext;
            if (file_exists($path)) {
                return $path;
            }
        }
        return null;
    }

    /**
     * Verifica se existe uma imagem
     *
     * @param string $folder Subpasta dentro de uploads
     * @param string $filename Nome do arquivo sem extensão
     * @return bool
     */
    public function imageExists($folder, $filename)
    {
        return $this->getImagePath($folder, $filename) !== null;
    }
}

