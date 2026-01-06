<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;

/**
 * Componente simples para gerir upload e remoção de imagens.
 * - Guarda ficheiros em `uploadPath` (aceita alias Yii, por defeito '@uploads')
 * - Retorna caminhos relativos para guardar na BD (ex: "users/filename.png")
 * - `getUrl()` constrói a URL pública a partir de `baseUrl` + caminho relativo
 *
 * Configurar em `common/config/main.php`:
 *  - uploadPath (alias para pasta física, ex: '@uploads' => common/uploads)
 *  - baseUrl (URL pública ou alias, ex: '@uploadsUrl' => '/uploads')
 *  - subdir (subpasta dentro de uploadPath, ex: 'users')
 *  - defaultImage (nome do ficheiro default dentro de subdir, ex: 'default.jpg')
 */
class ImageUploader extends Component
{
    // alias para a pasta base de uploads (por defeito usa '@uploads' = common/uploads)
    public $uploadPath = '@uploads';
    // URL pública correspondente (pode ser alias, ex: '@uploadsUrl' => '/uploads')
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
        if (!$file instanceof UploadedFile) {
            Yii::error('ImageUploader::upload - file is not UploadedFile', __METHOD__);
            return false;
        }

        try {
            $base = Yii::getAlias($this->uploadPath);
            Yii::info('ImageUploader::upload - Base path resolved: ' . $base, __METHOD__);
        } catch (\Exception $e) {
            Yii::error('ImageUploader::upload - invalid uploadPath alias: ' . $e->getMessage(), __METHOD__);
            return false;
        }

        $sub = trim((string)$this->subdir, '/\\');
        $dir = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ($sub === '' ? '' : $sub . DIRECTORY_SEPARATOR);

        Yii::info('ImageUploader::upload - Target directory: ' . $dir, __METHOD__);

        // cria diretório se necessário
        if (!is_dir($dir)) {
            Yii::warning('ImageUploader::upload - Directory does not exist, attempting to create: ' . $dir, __METHOD__);
            if (!@mkdir($dir, 0775, true) && !is_dir($dir)) {
                Yii::error('ImageUploader::upload - FAILED to create directory: ' . $dir, __METHOD__);
                return false;
            }
            Yii::info('ImageUploader::upload - Directory created successfully', __METHOD__);
        }

        // verifica permissões de escrita
        if (!is_writable($dir)) {
            Yii::warning('ImageUploader::upload - Directory not writable, attempting chmod: ' . $dir, __METHOD__);
            @chmod($dir, 0775);
            if (!is_writable($dir)) {
                Yii::error('ImageUploader::upload - Directory STILL not writable after chmod: ' . $dir, __METHOD__);
                Yii::error('ImageUploader::upload - Current permissions: ' . substr(sprintf('%o', fileperms($dir)), -4), __METHOD__);
                return false;
            }
            Yii::info('ImageUploader::upload - Directory is now writable', __METHOD__);
        }

        $ext = $file->extension ? '.' . $file->extension : '';
        // gerar nome único; garantir que não colide com defaultImage e não sobrescreve ficheiros existentes
        do {
            $name = ($id ? "user_{$id}_" : '') . uniqid() . $ext;
            $full = $dir . $name;
        } while (basename($name) === $this->defaultImage || file_exists($full));

        Yii::info('ImageUploader::upload - Target file: ' . $full, __METHOD__);

        if (!$file->saveAs($full)) {
            Yii::error('ImageUploader::upload - saveAs FAILED for: ' . $full, __METHOD__);
            Yii::error('ImageUploader::upload - File error: ' . $file->error, __METHOD__);
            return false;
        }

        Yii::info('ImageUploader::upload - Upload successful: ' . ($sub === '' ? $name : $sub . '/' . $name), __METHOD__);
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

        // Não permitir apagar o ficheiro default
        if ($this->isDefault($storedPath)) {
            Yii::info('ImageUploader::delete - attempted delete of default image, skipping: ' . $storedPath);
            return false;
        }

        $storedPath = ltrim($storedPath, "/\\");

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
     * Verifica se o caminho fornecido corresponde ao ficheiro default configurado
     * Aceita 'default.jpg', 'users/default.jpg' ou caminho relativo
     * @param string|null $storedPath
     * @return bool
     */
    public function isDefault($storedPath)
    {
        if (empty($storedPath)) {
            return false;
        }

        $sp = ltrim((string)$storedPath, "/\\");
        // se veio só basename
        if (basename($sp) === $this->defaultImage) {
            return true;
        }

        // se veio sem subdir, ou com subdir, comparar com subdir/defaultImage
        $sub = trim((string)$this->subdir, '/\\');
        $expected = ($sub === '' ? $this->defaultImage : $sub . '/' . $this->defaultImage);
        return $sp === $expected;
    }

    /**
     * Retorna a URL pública do ficheiro baseado no storedPath (relativo)
     * @param string|null $storedPath
     * @return string|null
     */
    public function getUrl($storedPath = null)
    {
        if (empty($storedPath)) {
            $sub = trim((string)$this->subdir, '/\\');
            $storedPath = ($sub === '') ? $this->defaultImage : $sub . '/' . $this->defaultImage;
        }

        $storedPath = ltrim($storedPath, '/\\');

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
