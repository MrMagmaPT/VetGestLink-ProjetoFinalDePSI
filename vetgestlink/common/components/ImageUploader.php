<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;

/**
 * Componente simples para gerir upload e remoção de imagens.
 * - Guarda ficheiros em `uploadPath` (aceita alias Yii)
 * - Retorna caminhos relativos para guardar na BD (ex: "users/filename.png")
 * - `getUrl()` constrói a URL pública a partir de `baseUrl` + caminho relativo
 *
 * Propriedades configuráveis no `common/config/main.php`:
 *  - uploadPath (alias para pasta física, ex: '@uploads' => backend/web/uploads)
 *  - baseUrl (URL pública ou alias, ex: '@uploadsUrl' => '/backend/uploads')
 *  - subdir (subpasta dentro de uploadPath, ex: 'users')
 *  - defaultImage (nome do ficheiro default dentro de subdir, ex: 'default.jpg')
 */
class ImageUploader extends Component
{
    // alias ou caminho físico para a pasta base de uploads
    public $uploadPath = '@backend/web/uploads';
    // URL pública correspondente (pode ser alias, ex: '@uploadsUrl')
    public $baseUrl = '/uploads';
    // subdiretório dentro de uploadPath onde armazenamos ficheiros
    public $subdir = 'users';
    // nome do ficheiro default (dentro de subdir)
    public $defaultImage = 'default.jpg';

    /**
     * Faz upload de um UploadedFile.
     * @param UploadedFile $file
     * @param mixed $id opcional - usado para criar nomes únicos (ex: user_123_...)
     * @return string|false caminho relativo (subdir/name.ext) ou false em falha
     */
    public function upload(UploadedFile $file, $id = null)
    {
        // valida tipo
        if (!$file instanceof UploadedFile) {
            Yii::error('ImageUploader::upload - file is not UploadedFile');
            return false;
        }

        // resolve alias uploadPath
        try {
            $base = Yii::getAlias($this->uploadPath);
        } catch (\Exception $e) {
            Yii::error('ImageUploader::upload - invalid uploadPath alias: ' . $e->getMessage());
            return false;
        }

        $sub = trim((string)$this->subdir, '/\\');
        $dir = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ($sub === '' ? '' : $sub . DIRECTORY_SEPARATOR);

        // cria diretório se necessário
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0775, true) && !is_dir($dir)) {
                Yii::error('ImageUploader::upload - failed to create directory: ' . $dir);
                return false;
            }
        }

        // verifica permissões de escrita
        if (!is_writable($dir)) {
            @chmod($dir, 0775);
            if (!is_writable($dir)) {
                Yii::error('ImageUploader::upload - directory not writable: ' . $dir);
                return false;
            }
        }

        $ext = $file->extension ? '.' . $file->extension : '';
        $name = ($id ? "user_{$id}_" : '') . uniqid() . $ext;
        $full = $dir . $name;

        // guarda o ficheiro físico
        if (!$file->saveAs($full)) {
            Yii::error('ImageUploader::upload - saveAs failed for: ' . $full);
            return false;
        }

        // retorna caminho relativo para armazenamento na BD: "subdir/name.ext" ou apenas "name.ext" se subdir vazio
        return ($sub === '' ? $name : $sub . '/' . $name);
    }

    /**
     * Elimina um ficheiro cujo caminho relativo está armazenado na BD.
     * @param string $storedPath Ex: 'users/name.png' ou 'name.png'
     * @return bool
     */
    public function delete($storedPath)
    {
        if (empty($storedPath)) {
            return false;
        }

        $storedPath = ltrim($storedPath, "/\\");

        // If storedPath does not contain a directory, prefix the configured subdir
        $sub = trim((string)$this->subdir, '/\\');
        if (strpos($storedPath, '/') === false && $sub !== '') {
            $storedPath = $sub . '/' . $storedPath;
        }

        try {
            $full = rtrim(Yii::getAlias($this->uploadPath), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $storedPath;
        } catch (\Exception $e) {
            Yii::error('ImageUploader::delete - invalid uploadPath alias: ' . $e->getMessage());
            return false;
        }

        return is_file($full) ? @unlink($full) : false;
    }

    /**
     * Retorna a URL pública do ficheiro baseado no storedPath (relativo)
     * @param string|null $storedPath
     * @return string|null
     */
    public function getUrl($storedPath = null)
    {
        // If no storedPath provided, use configured default image inside subdir
        if (empty($storedPath)) {
            $sub = trim((string)$this->subdir, '/\\');
            $storedPath = ($sub === '') ? $this->defaultImage : $sub . '/' . $this->defaultImage;
        }

        $storedPath = ltrim($storedPath, '/\\');

        // If storedPath does not contain a directory, prefix the configured subdir
        $sub = trim((string)$this->subdir, '/\\');
        if (strpos($storedPath, '/') === false && $sub !== '') {
            $storedPath = $sub . '/' . $storedPath;
        }

        $base = $this->baseUrl;
        if (is_string($base) && strpos($base, '@') === 0) {
            try {
                $base = Yii::getAlias($base);
            } catch (\Exception $e) {
                Yii::error('ImageUploader::getUrl - invalid baseUrl alias: ' . $e->getMessage());
                return null;
            }
        }

        return rtrim($base, '/') . '/' . ltrim($storedPath, '/');
    }
}
