# Guia Completo: Yii2 Master-Detail (Livros e Capítulos)

## 1. Configuração Inicial do Projeto

Primeiro, criamos o projeto e configuramos a conexão com a base de dados.

### 1.1 Instalar e Inicializar

Execute no terminal:

```bash
composer create-project --prefer-dist yiisoft/yii2-app-advanced teste
cd teste
php init
# Selecione [0] Development e confirme com [yes]
```

### 1.2 Configurar Base de Dados

Edite `common/config/main-local.php`:

```php
'components' => [
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=teste',
        'username' => 'root',
        'password' => '', // A sua password
        'charset' => 'utf8',
    ],
],
```

## 2. Back-End

### 2.1 Configuração de URL (Backend)

Edite `backend/config/main.php`. Isso remove o `index.php` da URL.

```php
'components' => [
    'urlManager' => [
        'enablePrettyUrl' => true, // Ativa URLs amigáveis (sem ?r=controller/action)
        'showScriptName' => false, // Remove 'index.php' da URL
        'rules' => [
            // Regras adicionais podem ser colocadas aqui
        ],
    ],
],
```

### 2.2 Migration (Criação das Tabelas)

Execute: `php yii migrate/create create_book_section_chapter_tables`. Substitua o código pelo abaixo. Os comentários explicam as chaves estrangeiras.

```php
<?php

use yii\db\Migration;

/**
 * Criação das tabelas para o sistema de livros.
 */
class m240101_000000_create_book_section_chapter_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // Garante suporte a caracteres especiais (UTF8) e motor InnoDB
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // --- 1. Tabela MESTRE: Book ---
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(), // Chave primária auto-incremento
            'title' => $this->string(255)->notNull(), // Título obrigatório
            'author' => $this->string(255),
            // created_at será preenchido automaticamente pelo Model
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // --- 2. Tabela LOOKUP: Section (ex: Introdução, Conclusão) ---
        $this->createTable('{{%section}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ], $tableOptions);

        // Insere dados padrão na tabela Section
        $this->batchInsert('{{%section}}', ['name'], [
            ['Introdução'], ['Desenvolvimento'], ['Conclusão'], ['Anexos']
        ]);

        // --- 3. Tabela DETALHE: Chapter ---
        $this->createTable('{{%chapter}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(), // FK para o Livro (Obrigatório)
            'section_id' => $this->integer()->defaultValue(null), // FK para Seção (Opcional)
            'title' => $this->string(255)->notNull(),
            'content' => $this->text(), // Texto longo para conteúdo
        ], $tableOptions);

        // --- CHAVES ESTRANGEIRAS (Foreign Keys) ---

        // FK 1: Relaciona Chapter ao Book
        // onDelete 'CASCADE': Se apagar o Livro, apaga TODOS os seus capítulos automaticamente.
        $this->addForeignKey(
            'fk-chapter-book',
            '{{%chapter}}', 'book_id',
            '{{%book}}', 'id',
            'CASCADE'
        );

        // FK 2: Relaciona Chapter a Section
        // onDelete 'SET NULL': Se apagar a Seção (ex: "Introdução"), o capítulo permanece mas fica sem seção.
        $this->addForeignKey(
            'fk-chapter-section',
            '{{%chapter}}', 'section_id',
            '{{%section}}', 'id',
            'SET NULL'
        );
    }

    public function down()
    {
        // Remove na ordem inversa para evitar erros de integridade
        $this->dropForeignKey('fk-chapter-section', '{{%chapter}}');
        $this->dropForeignKey('fk-chapter-book', '{{%chapter}}');
        $this->dropTable('{{%chapter}}');
        $this->dropTable('{{%section}}');
        $this->dropTable('{{%book}}');
    }
}
```

## 3. Gerando Models e CRUDs com Gii

O Gii é a ferramenta oficial do Yii2 para geração automática de código (Models, Controllers e Views).

### Acessando o Gii

- **Sem Pretty URL:**
  http://localhost/teste/backend/web/index.php?r=gii
- **Com Pretty URL:**
  http://localhost/teste/backend/web/gii

### [Criar Models]

#### Book

- Clique em **Model Generator**
- Table Name: `book`
- Model Class: `Book`
- Namespace: `common\models`
- Clique **Preview** → depois **Generate**

#### Chapter

- Clique em **Model Generator**
- Table Name: `chapter`
- Model Class: `Chapter`
- Namespace: `common\models`
- Clique **Preview** → depois **Generate**

#### Section

- Clique em **Model Generator**
- Table Name: `section`
- Model Class: `Section`
- Namespace: `common\models`
- Clique **Preview** → depois **Generate**

---

### [Criar CRUDs]

#### Book

- Clique em **CRUD Generator**
- Model Class: `common\models\Book`
- Controller Class: `backend\controllers\BookController`
- View Path: `@backend/views/book`
- Clique **Preview** → depois **Generate**

#### Chapter

- Clique em **CRUD Generator**
- Model Class: `common\models\Chapter`
- Controller Class: `backend\controllers\ChapterController`
- View Path: `@backend/views/chapter`
- Clique **Preview** → depois **Generate**

#### Section

- Clique em **CRUD Generator**
- Model Class: `common\models\Section`
- Controller Class: `backend\controllers\SectionController`
- View Path: `@backend/views/section`
- Clique **Preview** → depois **Generate**

---

### Garantir que os Models têm as Relações

Após gerar os Models, **verifique e adicione os relacionamentos** entre as tabelas no código dos Models, conforme a estrutura do banco de dados.

Exemplo para o `Book`:

```php
public function getChapters()
{
        return $this->hasMany(Chapter::class, ['book_id' => 'id']);
}
```

Exemplo para o `Chapter`:

```php
public function getBook()
{
        return $this->hasOne(Book::class, ['id' => 'book_id']);
}
public function getSection()
{
        return $this->hasOne(Section::class, ['id' => 'section_id']);
}
```

Exemplo de relação 1:1 (Um para Um) no `Section`:

```php
public function getSection()
{
    return $this->hasOne(Section::class, ['id' => 'section_id']);
}
```

---

### 2.3 Models (Camada de Dados)

### 3.1 Model Book (`common/models/Book.php`)

```php
<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Este é o model para a tabela "book".
 * * @property int $id
 * @property string $title
 * @property string|null $author
 * @property string|null $created_at
 * * @property Chapter[] $chapters
 */
class Book extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'book';
    }

    // Behaviors: Comportamentos automáticos
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at', // Define qual campo recebe a data
                'updatedAtAttribute' => false,        // Desativa updated_at pois não criamos na tabela
                'value' => new Expression('NOW()'),   // Usa a hora do banco de dados (MySQL)
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title'], 'required', 'message' => 'O título é obrigatório.'], // Validação de obrigatório
            [['created_at'], 'safe'], // Permite manipulação segura de data
            [['title', 'author'], 'string', 'max' => 255], // Validação de tamanho
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Título do Livro', // Rótulo que aparece nos formulários
            'author' => 'Autor',
            'created_at' => 'Data de Cadastro',
        ];
    }

    /**
     * RELACIONAMENTO 1:N
     * Um Livro TEM MUITOS (Has Many) Capítulos.
     * Útil para: $book->chapters (retorna array de objetos Chapter)
     */
    public function getChapters()
    {
        return $this->hasMany(Chapter::class, ['book_id' => 'id']);
    }
}
```

### 3.2 Model Chapter (`common/models/Chapter.php`)

```php
<?php
namespace common\models;

use Yii;

class Chapter extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'chapter';
    }

    public function rules()
    {
        return [
            [['book_id', 'title'], 'required'],
            [['book_id', 'section_id'], 'integer'],
            [['content'], 'string'], // text vira string sem limite definido aqui
            [['title'], 'string', 'max' => 255],

            // Valida se o ID do livro existe realmente na tabela 'book'
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['book_id' => 'id']],

            // Valida se o ID da seção existe realmente na tabela 'section'
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Section::class, 'targetAttribute' => ['section_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'book_id' => 'Livro Pertencente',
            'section_id' => 'Tipo de Seção',
            'title' => 'Título do Capítulo',
            'content' => 'Texto do Capítulo',
        ];
    }

    /**
     * RELACIONAMENTO N:1
     * Um Capítulo TEM UM (Has One) Livro.
     * Útil para: $chapter->book->title
     */
    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    /**
     * RELACIONAMENTO N:1
     * Um Capítulo TEM UMA (Has One) Seção.
     * Útil para: $chapter->section->name
     */
    public function getSection()
    {
        return $this->hasOne(Section::class, ['id' => 'section_id']);
    }
}
```

### 2.4 Controllers (Lógica de Negócio)

### 4.1 BookController (`backend/controllers/BookController.php`)

```php
<?php
namespace backend\controllers;

use common\models\Book;
use backend\models\BookSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class BookController extends Controller
{
    /**
     * Define comportamentos padrão.
     * VerbFilter garante segurança, permitindo DELETE apenas via método POST.
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view'],
                            'allow' => true,
                            'roles' => ['viewBook'],
                        ],
                        [
                            'actions' => ['create'],
                            'allow' => true,
                            'roles' => ['createBook'],
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => true,
                            'roles' => ['updateBook'],
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => true,
                            'roles' => ['deleteBook'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'], // Bloqueia acesso via URL direta (GET) para deletar
                    ],
                ],
            ]
        );
    }

    /**
     * Lista todos os livros com paginação e filtros.
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Exibe um único livro.
     * A view 'view.php' usará $model->chapters para mostrar os detalhes.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Cria um novo livro.
     */
    public function actionCreate()
    {
        $model = new Book();

        // Verifica se o formulário foi submetido (POST)
        if ($this->request->isPost) {
            // Carrega os dados do POST no model e tenta salvar
            if ($model->load($this->request->post()) && $model->save()) {
                // Se sucesso, redireciona para a página de visualização
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Atualiza um livro existente.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id); // Busca o livro ou dá erro 404

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deleta um livro.
     * Graças à Migration (onDelete CASCADE), os capítulos também serão deletados.
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Busca o modelo baseada na chave primária (ID).
     * Se não encontrar, lança exceção 404 (Página não encontrada).
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('O livro solicitado não existe.');
    }
}
```

### 4.2 ChapterController (`backend/controllers/ChapterController.php`)

```php
<?php
namespace backend\controllers;

use common\models\Chapter;
use backend\models\ChapterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ChapterController extends Controller
{
    /**
     * Define comportamentos padrão.
     * VerbFilter garante segurança, permitindo DELETE apenas via método POST.
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view'],
                            'allow' => true,
                            'roles' => ['viewChapter'],
                        ],
                        [
                            'actions' => ['create'],
                            'allow' => true,
                            'roles' => ['createChapter'],
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => true,
                            'roles' => ['updateChapter'],
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => true,
                            'roles' => ['deleteChapter'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'], // Bloqueia acesso via URL direta (GET) para deletar
                    ],
                ],
            ]
        );
    }

    /**
     * Lista todos os capítulos com paginação e filtros.
     */
    public function actionIndex()
    {
        $searchModel = new ChapterSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Exibe um único capítulo.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Cria um novo capítulo.
     */
    public function actionCreate()
    {
        $model = new Chapter();

        // Verifica se o formulário foi submetido (POST)
        if ($this->request->isPost) {
            // Carrega os dados do POST no model e tenta salvar
            if ($model->load($this->request->post()) && $model->save()) {
                // Se sucesso, redireciona para a página de visualização do livro pai
                return $this->redirect(['book/view', 'id' => $model->book_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Atualiza um capítulo existente.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id); // Busca o capítulo ou dá erro 404

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            // Redireciona para o Livro Pai, para manter o fluxo
            return $this->redirect(['book/view', 'id' => $model->book_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deleta um capítulo.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $bookId = $model->book_id; // Salva o ID do pai antes de deletar

        $model->delete();

        // Redireciona para o Livro Pai
        return $this->redirect(['book/view', 'id' => $bookId]);
    }

    /**
     * Busca o modelo baseada na chave primária (ID).
     * Se não encontrar, lança exceção 404 (Página não encontrada).
     */
    protected function findModel($id)
    {
        if (($model = Chapter::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('O capítulo solicitado não existe.');
    }
}
```

### 2.5 View Master-Detail (`backend/views/book/view.php`)

Esta é a parte visual onde a "mágica" acontece: mostramos o Livro e, logo abaixo, uma lista (Grid) dos seus Capítulos.

```php
<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model common\models\Book */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Livros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar Livro', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Excluir Livro', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem certeza? Isso apagará todos os capítulos!',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'author',
            'created_at:datetime', // Formata a data automaticamente
        ],
    ]) ?>

    <hr>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                Capítulos
                <?= Html::a('Adicionar Capítulo',
                    ['chapter/create', 'book_id' => $model->id],
                    ['class' => 'btn btn-success btn-sm pull-right']
                ) ?>
            </h3>
        </div>
        <div class="panel-body">
            <?= GridView::widget([
                // Cria um DataProvider na hora usando a relação 'getChapters' do model
                'dataProvider' => new ActiveDataProvider([
                    'query' => $model->getChapters(),
                    'pagination' => ['pageSize' => 10],
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'], // Numerador (1, 2, 3...)

                    'title', // Título do Capítulo

                    // Coluna Personalizada para mostrar o nome da Seção
                    [
                        'attribute' => 'section_id',
                        'label' => 'Seção',
                        'value' => function($chapter) {
                            // Verifica se existe seção, senão retorna traço
                            return $chapter->section ? $chapter->section->name : '-';
                        }
                    ],

                    // Botões de Ação (Ver, Editar, Excluir) para o CAPÍTULO
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'controller' => 'chapter', // Força o uso do ChapterController
                        'template' => '{update} {delete}', // Mostra apenas editar e deletar
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>
```

---

## 3. Segurança com RBAC (Role-Based Access Control)

Para controlar quem pode fazer o quê no sistema, vamos implementar o RBAC.

### 3.1 Criando o `RbacController`

Crie o arquivo `console/controllers/RbacController.php`. Este controller será executado via terminal para configurar as permissões e papéis (roles).

```php
<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * RBAC generator
 */
class RbacController extends Controller
{
    /**
     * Generates the RBAC authorization data.
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Limpa todas as regras, permissões e roles existentes
        $auth->removeAll();

        // --- PERMISSÕES PARA LIVROS (BOOK) ---
        $viewBook = $auth->createPermission('viewBook');
        $viewBook->description = 'Ver os detalhes de um livro';
        $auth->add($viewBook);

        $createBook = $auth->createPermission('createBook');
        $createBook->description = 'Criar um novo livro';
        $auth->add($createBook);

        $updateBook = $auth->createPermission('updateBook');
        $updateBook->description = 'Atualizar um livro existente';
        $auth->add($updateBook);

        $deleteBook = $auth->createPermission('deleteBook');
        $deleteBook->description = 'Excluir um livro';
        $auth->add($deleteBook);

        // --- PERMISSÕES PARA CAPÍTULOS (CHAPTER) ---
        $viewChapter = $auth->createPermission('viewChapter');
        $viewChapter->description = 'Ver os detalhes de um capítulo';
        $auth->add($viewChapter);

        $createChapter = $auth->createPermission('createChapter');
        $createChapter->description = 'Criar um novo capítulo';
        $auth->add($createChapter);

        $updateChapter = $auth->createPermission('updateChapter');
        $updateChapter->description = 'Atualizar um capítulo existente';
        $auth->add($updateChapter);

        $deleteChapter = $auth->createPermission('deleteChapter');
        $deleteChapter->description = 'Excluir um capítulo';
        $auth->add($deleteChapter);

        // --- ROLES (PAPÉIS) ---

        // Role: leitor (só pode visualizar)
        $leitor = $auth->createRole('leitor');
        $leitor->description = 'Pode visualizar livros e capítulos';
        $auth->add($leitor);
        $auth->addChild($leitor, $viewBook);
        $auth->addChild($leitor, $viewChapter);

        // Role: editor (pode gerenciar tudo)
        $editor = $auth->createRole('editor');
        $editor->description = 'Pode criar, atualizar e visualizar livros e capítulos';
        $auth->add($editor);
        $auth->addChild($editor, $leitor); // Herda as permissões de leitor
        $auth->addChild($editor, $createBook);
        $auth->addChild($editor, $updateBook);
        $auth->addChild($editor, $createChapter);
        $auth->addChild($editor, $updateChapter);

        // Role: admin (poder total, incluindo excluir)
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrador com acesso total';
        $auth->add($admin);
        $auth->addChild($admin, $editor); // Herda as permissões de editor
        $auth->addChild($admin, $deleteBook);
        $auth->addChild($admin, $deleteChapter);

        // Atribui o role de admin ao usuário com ID 1
        $auth->assign($admin, 1);

        echo "RBAC com roles e permissões gerado com sucesso.\n";
    }
}
```

### 3.2 Executando o RBAC

Para aplicar estas regras, execute o comando no terminal na raiz do seu projeto:

```bash
php yii rbac/init
```

### 3.3 Protegendo os Controllers

Agora, adicione o `AccessControl` filter nos `behaviors` dos seus controllers (`BookController.php` e `ChapterController.php`) para proteger as actions.

**Exemplo para `BookController`:**

```php
// ... (imports)
use yii\filters\AccessControl;

class BookController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['viewBook'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['createBook'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['updateBook'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['deleteBook'],
                    ],
                ],
            ],
            'verbs' => [
                // ... (verb filter existente)
            ],
        ];
    }
    // ... (resto do controller)
}
```

_Faça o mesmo para o `ChapterController`, usando as permissões `viewChapter`, `createChapter`, etc._

---

## 4. Testes Automatizados

Vamos garantir a qualidade do nosso código com testes unitários (para os Models) e funcionais (para a interação do usuário).

### 4.1 Testes Unitários para o Model `Book`

Este teste valida as regras e o relacionamento do `Book` model.

**Crie o arquivo `common/tests/unit/models/BookTest.php`:**

```php
<?php
namespace common\tests\unit\models;

use common\models\Book;
use common\fixtures\BookFixture;
use Codeception\Test\Unit;

class BookTest extends Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'books' => [
                'class' => BookFixture::class,
                'dataFile' => codecept_data_dir() . 'book.php'
            ]
        ];
    }

    public function testRules()
    {
        $model = new Book();

        // Sem dados, deve ter 2 erros de validação (título e autor)
        $this->assertFalse($model->validate());
        $this->assertCount(1, $model->getErrors('title'));
        $this->assertCount(0, $model->getErrors('author'));

        // Com dados inválidos (título muito curto)
        $model->title = 'A';
        $this->assertFalse($model->validate());
        $this->assertCount(1, $model->getErrors('title'));

        // Dados válidos
        $model->title = 'Um Título Válido';
        $model->author = 'Autor Exemplo';
        $this->assertTrue($model->validate());
    }

    public function testRelations()
    {
        $model = Book::findOne(1); // Supondo que exista um livro com ID 1

        // Verifica se a relação com Chapter está funcionando
        $this->assertNotEmpty($model->chapters);
        $this->assertInstanceOf(Chapter::class, $model->chapters[0]);
    }
}
```

**Crie as fixtures (dados de teste):**

1.  `common/fixtures/BookFixture.php`
2.  `common/fixtures/ChapterFixture.php`
3.  `common/tests/_data/book.php`
4.  `common/tests/_data/chapter.php`

_(O código para as fixtures foi detalhado nos passos anteriores)._

### 4.2 Testes Funcionais para o CRUD de `Book`

**Crie o arquivo `backend/tests/functional/BookCest.php`:**

```php
<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\Book;
use common\models\Chapter;
use common\models\Section;

class BookCest
{
    public function _before(FunctionalTester $I)
    {
        // Executado antes de cada teste desta classe
    }

    public function _after(FunctionalTester $I)
    {
        // Executado após cada teste desta classe
    }

    public function testCRUD(FunctionalTester $I)
    {
        $I->wantTo('realizar operações CRUD em um livro');

        // 1. Acessar a página de criação de livro
        $I->amOnPage('/book/create');
        $I->see('Criar Livro', 'h1');

        // 2. Preencher e submeter o formulário de criação
        $I->fillField('Book[title]', 'Livro de Teste');
        $I->fillField('Book[author]', 'Autor Teste');
        $I->click('Salvar');
        $I->see('Livro foi salvo com sucesso.');

        // 3. Verificar se o livro foi criado
        $livro = Book::findOne(['title' => 'Livro de Teste']);
        $I->assertNotNull($livro, 'Livro não foi criado na base de dados.');

        // 4. Editar o livro criado
        $I->amOnPage(['book/update', 'id' => $livro->id]);
        $I->see('Atualizar Livro', 'h1');
        $I->fillField('Book[title]', 'Livro de Teste Atualizado');
        $I->click('Salvar');
        $I->see('Livro foi atualizado com sucesso.');

        // 5. Verificar se o livro foi atualizado
        $livro->refresh();
        $I->assertEquals('Livro de Teste Atualizado', $livro->title);

        // 6. Deletar o livro
        $I->amOnPage(['book/view', 'id' => $livro->id]);
        $I->click('Excluir Livro');
        $I->see('Tem certeza? Isso apagará todos os capítulos!');
        $I->click('Sim, excluir');
        $I->see('Livro foi excluído com sucesso.');

        // 7. Verificar se o livro foi deletado
        $livroDeletado = Book::findOne($livro->id);
        $I->assertNull($livroDeletado, 'Livro não foi removido da base de dados.');
    }
}
```

### 4.3 Executando os Testes

Para rodar os testes, você precisará ter o [Codeception](https://codeception.com/) instalado, o que já acontece quando você cria um projeto com o `yii2-app-advanced`.

**1. Build (Construir os Atores de Teste):**

Antes de executar os testes pela primeira vez (ou sempre que você criar um novo arquivo de teste `*Cest.php` ou `*Test.php`), você precisa "construir" os atores do Codeception. Este comando lê seus arquivos de teste e gera as classes de ator (`Tester`) com os métodos apropriados.

```bash
# Na raiz do projeto
php vendor/bin/codecept build
```

**2. Executar Todas as Suites de Teste:**

Para rodar todos os testes de uma só vez (unitários, funcionais e de aceitação), use o comando `run`.

```bash
# Na raiz do projeto
php vendor/bin/codecept run
```

**3. Executar Suites Específicas:**

É mais comum executar as suites (conjuntos) de testes separadamente.

```bash
# Para rodar todos os testes unitários
php vendor/bin/codecept run unit

# Para rodar todos os testes funcionais
php vendor/bin/codecept run functional
```

**4. Executar um Arquivo de Teste Específico:**

Quando você está trabalhando em uma funcionalidade específica, pode querer executar apenas o arquivo de teste correspondente para agilizar o processo.

```bash
# Exemplo: Rodar apenas o teste unitário do Book
php vendor/bin/codecept run unit common/tests/unit/models/BookTest.php

# Exemplo: Rodar apenas o teste funcional do CRUD de Book
php vendor/bin/codecept run functional backend/tests/functional/BookCest.php
```

**5. Ver Saída Detalhada (Debug):**

Se um teste falhar e você precisar de mais informações sobre o que aconteceu passo a passo, use a flag `--debug`.

```bash
php vendor/bin/codecept run functional --debug
```
