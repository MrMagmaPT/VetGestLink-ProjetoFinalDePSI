<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Controller para servir imagens via API
 */
class ImageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Remove autenticação para imagens públicas
        unset($behaviors['authenticator']);

        return $behaviors;
    }

    /**
     * Retorna informações sobre uma imagem de animal
     *
     * GET /api/image/animal/123
     *
     * @param int $id ID do animal
     * @return array
     */
    public function actionAnimal($id)
    {
        $animal = \common\models\Animal::findOne($id);

        if (!$animal) {
            throw new NotFoundHttpException('Animal não encontrado');
        }

        return [
            'id' => $animal->id,
            'nome' => $animal->nome,
            'imageUrl' => $animal->getImageUrl(),
            'imageAbsoluteUrl' => $animal->getImageAbsoluteUrl(),
            'hasImage' => $animal->hasImage(),
        ];
    }

    /**
     * Retorna informações sobre uma imagem de usuário
     *
     * GET /api/image/user/123
     *
     * @param int $id ID do userprofile
     * @return array
     */
    public function actionUser($id)
    {
        $userprofile = \common\models\Userprofile::findOne($id);

        if (!$userprofile) {
            throw new NotFoundHttpException('Utilizador não encontrado');
        }

        return [
            'id' => $userprofile->id,
            'nomecompleto' => $userprofile->nomecompleto,
            'imageUrl' => $userprofile->getImageUrl(),
            'imageAbsoluteUrl' => $userprofile->getImageAbsoluteUrl(),
            'hasImage' => $userprofile->hasImage(),
        ];
    }

    /**
     * Serve diretamente o arquivo de imagem
     *
     * GET /api/image/serve?type=animal&id=123
     *
     * @param string $type Tipo (animal ou user)
     * @param int $id ID do registro
     * @return Response
     */
    public function actionServe($type, $id)
    {
        $folder = ($type === 'animal') ? 'animais' : 'users';

        // Buscar arquivo
        $extensions = ['jpg', 'jpeg', 'png'];
        $imagePath = null;

        foreach ($extensions as $ext) {
            $path = Yii::getAlias('@uploads/' . $folder . '/' . $id . '.' . $ext);
            if (file_exists($path)) {
                $imagePath = $path;
                break;
            }
        }

        if (!$imagePath) {
            // Retornar imagem padrão
            $imagePath = Yii::getAlias('@backend/web/images/no-image.svg');
        }

        // Servir o arquivo
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->set('Content-Type', mime_content_type($imagePath));
        Yii::$app->response->headers->set('Cache-Control', 'public, max-age=86400'); // Cache por 1 dia

        return file_get_contents($imagePath);
    }

    /**
     * Lista todas as imagens de animais
     *
     * GET /api/image/animals
     *
     * @return array
     */
    public function actionAnimals()
    {
        $animals = \common\models\Animal::find()
            ->where(['eliminado' => 0])
            ->all();

        $result = [];
        foreach ($animals as $animal) {
            $result[] = [
                'id' => $animal->id,
                'nome' => $animal->nome,
                'imageUrl' => $animal->getImageUrl(),
                'imageAbsoluteUrl' => $animal->getImageAbsoluteUrl(),
                'hasImage' => $animal->hasImage(),
            ];
        }

        return $result;
    }

    /**
     * Lista todas as imagens de usuários
     *
     * GET /api/image/users
     *
     * @return array
     */
    public function actionUsers()
    {
        $userprofiles = \common\models\Userprofile::find()
            ->where(['eliminado' => 0])
            ->all();

        $result = [];
        foreach ($userprofiles as $userprofile) {
            $result[] = [
                'id' => $userprofile->id,
                'nomecompleto' => $userprofile->nomecompleto,
                'imageUrl' => $userprofile->getImageUrl(),
                'imageAbsoluteUrl' => $userprofile->getImageAbsoluteUrl(),
                'hasImage' => $userprofile->hasImage(),
            ];
        }

        return $result;
    }
}

