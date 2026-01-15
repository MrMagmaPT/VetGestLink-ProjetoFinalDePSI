# Guia de Implementação e Documentação: API de Calculadora

Este documento serve como um guia completo para a implementação e utilização dos novos endpoints da API de calculadora. Ele está dividido em duas seções principais:

1.  **Documentação da API:** Descreve como consumir os endpoints, incluindo autenticação, permissões e exemplos.
2.  **Implementação Técnica:** Fornece o código-fonte completo e as configurações necessárias para adicionar esta funcionalidade ao projeto.

---

## Parte 1: Documentação da API

Esta seção destina-se a desenvolvedores que irão consumir a API da calculadora.

### Autenticação

Todas as requisições para estes endpoints exigem autenticação. O `access-token` do usuário deve ser fornecido como um parâmetro de query na URL.

**Exemplo de URL:**
`GET /api/calculadora/adicao?a=10&b=5&access-token=SEU_TOKEN_AQUI`

### Permissões

Para utilizar qualquer um dos endpoints da calculadora, o usuário autenticado deve possuir a permissão `useCalculator`. Se a permissão não for concedida, a API retornará um erro `401 Unauthorized`.

### Endpoints

| Operação | Método | Endpoint | Parâmetros de Query | Exemplo de Resposta (JSON) |
| :--- | :--- | :--- | :--- | :--- |
| **Adição** | `GET` | `/api/calculadora/adicao` | `a` (number), `b` (number) | `{ "operacao": "adição", "a": "10", "b": "5", "resultado": 15 }` |
| **Subtração** | `GET` | `/api/calculadora/subtracao` | `a` (number), `b` (number) | `{ "operacao": "subtração", "a": "10", "b": "5", "resultado": 5 }` |
| **Multiplicação**| `GET` | `/api/calculadora/multiplicacao`| `a` (number), `b` (number) | `{ "operacao": "multiplicação", "a": "7", "b": "3", "resultado": 21 }` |
| **Divisão** | `GET` | `/api/calculadora/divisao` | `a` (number), `b` (number) | `{ "operacao": "divisão", "a": "10", "b": "2", "resultado": 5 }` |
| **Raiz Quadrada**| `GET` | `/api/calculadora/raiz` | `a` (number) | `{ "operacao": "raiz quadrada", "a": "25", "resultado": 5 }` |

---

## Parte 2: Implementação Técnica

Esta seção contém o código-fonte e as configurações para adicionar a API da calculadora ao projeto.

### 2.1. Controller (`CalculadoraController.php`)

Crie o seguinte arquivo no diretório `backend/modules/api/controllers/`. Este controller herda de `yii\rest\Controller` e contém a lógica para cada operação matemática, incluindo validação de parâmetros e verificação de permissões.

```php
// filepath: backend/modules/api/controllers/CalculadoraController.php
<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\Cors;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;
use yii\filters\auth\QueryParamAuth;

/**
 * Controller de Calculadora
 * Endpoints para realizar operações matemáticas básicas.
 */
class CalculadoraController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Configuração padrão do projeto para CORS, Autenticação e Resposta JSON
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];
        
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    /**
     * Adição: GET /api/calculadora/adicao?a=5&b=3
     */
    public function actionAdicao($a, $b)
    {
        $this->checkPermission();
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new BadRequestHttpException('Parâmetros inválidos. Ambos devem ser números.');
        }
        $resultado = (float)$a + (float)$b;
        return ['operacao' => 'adição', 'a' => $a, 'b' => $b, 'resultado' => $resultado];
    }

    /**
     * Subtração: GET /api/calculadora/subtracao?a=5&b=3
     */
    public function actionSubtracao($a, $b)
    {
        $this->checkPermission();
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new BadRequestHttpException('Parâmetros inválidos. Ambos devem ser números.');
        }
        $resultado = (float)$a - (float)$b;
        return ['operacao' => 'subtração', 'a' => $a, 'b' => $b, 'resultado' => $resultado];
    }

    /**
     * Multiplicação: GET /api/calculadora/multiplicacao?a=5&b=3
     */
    public function actionMultiplicacao($a, $b)
    {
        $this->checkPermission();
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new BadRequestHttpException('Parâmetros inválidos. Ambos devem ser números.');
        }
        $resultado = (float)$a * (float)$b;
        return ['operacao' => 'multiplicação', 'a' => $a, 'b' => $b, 'resultado' => $resultado];
    }

    /**
     * Divisão: GET /api/calculadora/divisao?a=6&b=3
     */
    public function actionDivisao($a, $b)
    {
        $this->checkPermission();
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new BadRequestHttpException('Parâmetros inválidos. Ambos devem ser números.');
        }
        if ((float)$b === 0.0) {
            throw new BadRequestHttpException('Divisão por zero não é permitida.');
        }
        $resultado = (float)$a / (float)$b;
        return ['operacao' => 'divisão', 'a' => $a, 'b' => $b, 'resultado' => $resultado];
    }

    /**
     * Raiz Quadrada: GET /api/calculadora/raiz?a=9
     */
    public function actionRaiz($a)
    {
        $this->checkPermission();
        if (!is_numeric($a)) {
            throw new BadRequestHttpException('Parâmetro inválido. O valor deve ser um número.');
        }
        if ((float)$a < 0) {
            throw new BadRequestHttpException('Não é possível calcular a raiz quadrada de um número negativo.');
        }
        $resultado = sqrt((float)$a);
        return ['operacao' => 'raiz quadrada', 'a' => $a, 'resultado' => $resultado];
    }

    /**
     * Verifica se o usuário tem permissão para usar a calculadora.
     * É necessário criar uma permissão chamada 'useCalculator' no sistema RBAC.
     */
    private function checkPermission()
    {
        if (!Yii::$app->user->can('useCalculator')) {
            throw new UnauthorizedHttpException('Você não tem permissão para executar esta ação.');
        }
    }
}
```

### 2.2. Configuração de Rota (`main.php`)

Adicione a seguinte regra ao array `rules` dentro do `urlManager` no arquivo `backend/config/main.php`. Isso tornará as ações do `CalculadoraController` acessíveis através das URLs definidas.

```php
// filepath: backend/config/main.php
// ...
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        // ... outras regras existentes ...

        // ========== CALCULADORA CONTROLLER (Protegido) ==========
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => 'api/calculadora',
            'pluralize' => false,
            'extraPatterns' => [
                'GET adicao' => 'adicao',
                'GET subtracao' => 'subtracao',
                'GET multiplicacao' => 'multiplicacao',
                'GET divisao' => 'divisao',
                'GET raiz' => 'raiz',
            ],
        ],
    ],
],
// ...
```