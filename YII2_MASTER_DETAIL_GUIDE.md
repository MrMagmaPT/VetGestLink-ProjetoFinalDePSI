# Guia de Relacionamentos Master-Detail no Yii2 Advanced Template

Este guia explica como criar relacionamentos master-detail (mestre-detalhe) no Yii2 Advanced Template utilizando Gii e técnicas aplicadas neste projeto.

## Índice
1. Configuração da Base de Dados
2. Gerar Models com Gii
3. Criar Relacionamentos nos Models
4. Gerar CRUD com Gii
5. Implementar Views Master-Detail
6. Usar Pjax para Atualizações Dinâmicas
7. Criar Search Models
8. Padrões de Controllers
9. Criar Roles e Permissões com RBAC
10. Criar e Executar Migrations
11. Criar Testes Unitários
12. Criar Testes Funcionais
13. Habilitar Pretty URLs
14. Boas Práticas
15. Exemplo Completo: Sistema de Livros e Capítulos

---

## 1. Configuração da Base de Dados

Antes de criar models, certifique-se que as tabelas da base de dados têm relacionamentos de chave estrangeira adequados.

**Estrutura Master-Detail:**
- Tabela Master: contém os dados principais (exemplo: faturas)
- Tabela Detail: contém os dados relacionados (exemplo: linhas de fatura)
- A tabela detail deve ter uma chave estrangeira que aponta para a tabela master

**Padrão SQL:**
- Criar tabela master com chave primária
- Criar tabela detail com chave primária própria
- Adicionar coluna na tabela detail que referencia o ID da master
- Definir FOREIGN KEY na tabela detail apontando para a master
- Usar NOT NULL na chave estrangeira se o relacionamento for obrigatório

---

## 2. Gerar Models com Gii

**Passos para gerar models:**

1. Navegue para Gii no navegador usando o endereço do backend seguido de /gii
2. Selecione a opção **Model Generator**
3. Preencha os campos necessários:
   - **Table Name**: Nome da tabela da base de dados
   - **Model Class**: Nome da classe do model (primeira letra maiúscula, singular)
   - **Namespace**: Use sempre `common\models` para models partilhados
   - **Enable I18N**: Marque se precisar de internacionalização
   - **Generate Relations**: Marque "from current schema" para gerar relacionamentos automaticamente
4. Clique em Preview para ver o código gerado
5. Clique em Generate para criar o model
6. Repita o processo para a tabela detail

**Importante:** Gere sempre os models no namespace `common\models` para que possam ser acedidos tanto pelo frontend como pelo backend.

---

## 3. Criar Relacionamentos nos Models

Após gerar os models com Gii, os relacionamentos são criados automaticamente mas podem ser personalizados.

**Model Master - Relacionamento Um-para-Muitos:**

No model master, crie um método getter que retorna a relação com os detalhes:
- Use o método `hasMany()` para relacionamentos um-para-muitos
- O primeiro parâmetro é a classe do model detail
- O segundo parâmetro é um array com `['chave_estrangeira' => 'chave_primária']`
- Pode adicionar `where()` para filtrar registos eliminados logicamente
- Use `with()` para carregar dados relacionados (eager loading) e evitar queries N+1
- Pode adicionar `orderBy()` para ordenar os resultados

**Model Detail - Relacionamento Muitos-para-Um:**

No model detail, crie um método getter que retorna a relação com o master:
- Use o método `hasOne()` para relacionamentos muitos-para-um
- Defina a mesma estrutura de array para a relação
- Este método permite aceder ao registo master a partir de um detail

**Pontos-chave:**
- Defina sempre relacionamentos inversos em ambos os models
- Use `where(['eliminado' => 0])` para filtrar soft deletes
- Adicione `with()` nos relacionamentos para carregar dados aninhados
- Use nomes de métodos no plural para hasMany e singular para hasOne

---

## 4. Gerar CRUD com Gii

**Passos para gerar operações CRUD:**

1. Aceda ao Gii no navegador
2. Selecione **CRUD Generator**
3. Preencha os campos:
   - **Model Class**: Caminho completo do model (ex: `common\models\Fatura`)
   - **Search Model Class**: Model de pesquisa no namespace backend/frontend (ex: `backend\models\FaturaSearch`)
   - **Controller Class**: Caminho do controller (ex: `backend\controllers\FaturaController`)
   - **View Path**: Deixe o padrão ou especifique caminho personalizado
   - **Enable Pjax**: Marque para melhor experiência de utilizador
   - **Enable I18N**: Marque se necessário
4. Clique em Preview e depois Generate
5. Repita o processo para o model detail

**Nota:** O search model deve ficar no namespace backend ou frontend, enquanto o model base fica em common.

**Após Geração:**
- Personalize as views geradas conforme necessário
- Adicione validações extras nos controllers
- Configure permissões RBAC nos behaviors do controller
- Ajuste os filtros e colunas nas views de index

---

## 5. Implementar Views Master-Detail

**View Master (index.php) com GridView e Pjax:**

Para criar a listagem dos registos master:
- Envolva o GridView dentro de um bloco Pjax::begin() e Pjax::end()
- Defina um ID único para o container Pjax
- Configure o GridView com dataProvider e filterModel
- Personalize o summary para mostrar informação em português
- Configure o layout do GridView (summary, items, pager)
- Defina um emptyText personalizado para quando não há resultados
- Use tableOptions para adicionar classes Bootstrap
- Configure as colunas necessárias no array columns
- Adicione SerialColumn para numeração automática
- Use ActionColumn para botões de ações (view, update, delete)
- Para atributos relacionados, use funções anónimas no value

**View Detail (view.php) - Mostrar Registos Relacionados:**

Para mostrar os detalhes relacionados ao master:
- Aceda aos registos detail através da propriedade de relação do model
- Crie uma tabela HTML para listar os details
- Use um foreach para iterar pelos registos detail
- Mostre os campos relevantes de cada detail
- Adicione links para editar ou eliminar cada detail
- Use Html::a() para criar links com ícones
- Configure data-confirm para confirmação de eliminação
- Use data-method='post' para o método de eliminação
- Adicione um botão para criar novo detail vinculado ao master atual
- Passe o ID do master como parâmetro no link de criação

**Estrutura da View:**
- Use cards do Bootstrap para organizar visualmente
- Adicione headers aos cards com ícones FontAwesome
- Separe informações em colunas (col-md-X)
- Use badges para estados e informações importantes
- Formate números e datas usando formatters do Yii
- Adicione botões de ação no topo do card

---

## 6. Usar Pjax para Atualizações Dinâmicas

Pjax permite atualizações de página via AJAX sem recarregar completamente, melhorando a experiência do utilizador.

**Implementação Básica de Pjax:**

Para usar Pjax numa view:
- Importe a classe `yii\widgets\Pjax` no topo do ficheiro
- Envolva o conteúdo com Pjax::begin() e Pjax::end()
- Defina um ID único no array de configuração
- Configure enablePushState como false para não alterar URL
- Defina timeout em milissegundos (exemplo: 5000 para 5 segundos)
- Coloque dentro o conteúdo que deseja atualizar dinamicamente

**Recarregar Container Pjax com JavaScript:**

Para recarregar um container Pjax manualmente:
- Use jQuery Pjax com $.pjax.reload()
- Especifique o container pelo ID no parâmetro
- Pode passar parâmetros de URL adicionais
- Útil para atualizar após operações AJAX
- Pode ser acionado por eventos de botões ou select

**Casos de Uso:**
- Filtrar e ordenar GridView sem recarregar página
- Submeter formulários dinamicamente
- Atualizar dados em tempo real
- Modais com formulários que atualizam a página principal
- Pesquisas com Select2 que filtram GridView

---

## 7. Criar Search Models

Search models estendem os models base e adicionam funcionalidade de pesquisa.

**Padrão do Search Model:**

Estrutura básica do search model:
- Crie uma classe que estende o model base
- Coloque no namespace backend\models ou frontend\models
- Sobrescreva o método rules() para permitir pesquisa em todos os campos
- Sobrescreva scenarios() para retornar Model::scenarios()
- Implemente o método search() que recebe parâmetros de pesquisa
- Retorne um ActiveDataProvider configurado

**Método search():**

No método search, siga estes passos:
- Crie uma query usando Model::find()
- Adicione filtro where para eliminado = 0 (soft delete)
- Use with() para fazer eager loading de relações
- Crie um ActiveDataProvider passando a query
- Configure sort com defaultOrder
- Configure pagination com pageSize
- Carregue os parâmetros com $this->load()
- Valide os dados com $this->validate()
- Adicione filtros usando andFilterWhere()
- Use operadores de comparação conforme necessário
- Retorne o dataProvider configurado

**Métodos Estáticos Auxiliares:**

Adicione métodos estáticos para:
- Contar totais de registos
- Obter estatísticas agregadas
- Criar listas para dropdowns e Select2
- Filtrar registos por estados específicos
- Retornar dados pré-formatados para views
- Executar queries complexas reutilizáveis

**Pontos-chave:**
- Search models estendem o model base
- Use andFilterWhere() para filtros opcionais
- Configure ordenação padrão no ActiveDataProvider
- Faça eager loading para evitar queries N+1
- Crie métodos estáticos para operações comuns

---

## 8. Padrões de Controllers

**Estrutura Padrão de Controller CRUD:**

Configure o controller com os seguintes componentes:
- Estenda a classe Controller do Yii
- Importe as classes necessárias (Model, SearchModel, NotFoundHttpException)
- Importe os filtros AccessControl e VerbFilter
- Configure o namespace apropriado

**Método behaviors():**

Configure os behaviors do controller:
- Adicione AccessControl para controlo de permissões RBAC
- Defina rules com actions permitidas por role
- Configure VerbFilter para restringir métodos HTTP
- Defina que delete só aceita POST
- Proteja todas as actions com as roles apropriadas

**Action Index:**

Para listar registos:
- Crie uma instância do SearchModel
- Chame o método search() passando queryParams
- Passe searchModel e dataProvider para a view
- Adicione dados extras como contadores se necessário
- Prepare listas para filtros (Select2, dropdowns)

**Action View:**

Para visualizar um registo:
- Receba o ID como parâmetro
- Use findModel() para buscar o registo
- Passe o model para a view
- A view terá acesso às relações através do model

**Action Create:**

Para criar novos registos:
- Crie uma nova instância do model
- Verifique se o request é POST
- Carregue os dados com load()
- Valide e grave com save()
- Defina flash message de sucesso
- Redirecione para view do registo criado
- Se não for POST, mostre o formulário

**Action Update:**

Para atualizar registos:
- Use findModel() para buscar o registo
- Verifique se o request é POST
- Carregue e grave os dados
- Defina flash message de sucesso
- Redirecione para view
- Se não for POST, mostre o formulário preenchido

**Action Delete:**

Para eliminar registos (soft delete):
- Use findModel() para buscar o registo
- Altere o campo eliminado para 1
- Grave as alterações
- Defina flash message de sucesso
- Redirecione para index

**Método findModel():**

Crie um método protegido findModel():
- Receba o ID como parâmetro
- Use findOne() com condições de ID e eliminado
- Retorne o model se encontrado
- Lance NotFoundHttpException se não existir

**Criar Details a partir do Master:**

Para criar registos detail vinculados:
- Receba o ID do master como parâmetro opcional
- Crie nova instância do detail model
- Preencha automaticamente a chave estrangeira se ID fornecido
- Após gravar, redirecione para view do master
- Passe o ID do master no redirect

**Pontos-chave:**
- Use AccessControl para RBAC
- Use VerbFilter para métodos HTTP
- Defina flash messages para feedback
- Valide propriedade antes de editar
- Use soft delete em vez de hard delete
- Redirecione para master após criar/editar detail

---

## 9. Criar Roles e Permissões com RBAC

RBAC (Role-Based Access Control) é o sistema de controlo de acesso baseado em roles do Yii2.

**Configuração Inicial:**

Antes de criar roles e permissões:
- Configure o authManager no ficheiro de configuração comum
- Use DbManager para armazenar RBAC na base de dados
- Ou use PhpManager para armazenar em ficheiros PHP
- Execute as migrations necessárias para criar tabelas RBAC

**Criar Controller de RBAC:**

Para gerir o RBAC via console:
- Crie um RbacController no namespace console\controllers
- Estenda a classe yii\console\Controller
- Crie uma action init() para inicializar o sistema
- Obtenha o authManager via Yii::$app->authManager

**Criar Permissões:**

Para criar permissões individuais:
- Use createPermission() do authManager
- Defina um nome único para a permissão (exemplo: 'createUser')
- Defina uma descrição clara em português
- Use add() para adicionar a permissão ao sistema
- Agrupe permissões por módulos ou funcionalidades

**Padrão de Nomenclatura de Permissões:**

Use convenção CRUD para permissões:
- create + Nome (exemplo: createInvoice, createAnimal)
- view + Nome no plural (exemplo: viewInvoices, viewAnimals)
- update + Nome (exemplo: updateInvoice, updateAnimal)
- delete + Nome (exemplo: deleteInvoice, deleteAnimal)
- Permissões especiais conforme necessário

**Criar Roles:**

Para criar roles:
- Use createRole() do authManager
- Defina um nome para o role (exemplo: 'admin', 'veterinario', 'rececionista')
- Adicione descrição em português
- Use add() para adicionar o role ao sistema
- Roles representam grupos de utilizadores

**Atribuir Permissões a Roles:**

Para associar permissões a roles:
- Use addChild() do authManager
- O primeiro parâmetro é o role
- O segundo parâmetro é a permissão
- Um role pode ter múltiplas permissões
- Organize permissões logicamente por role

**Criar Hierarquia de Roles:**

Para criar hierarquia entre roles:
- Use addChild() passando dois roles
- O role pai herda todas as permissões do role filho
- Permite criar estruturas hierárquicas (exemplo: admin > veterinario > rececionista)
- Simplifica gestão de permissões

**Atribuir Roles a Utilizadores:**

Para atribuir um role a um utilizador:
- Use assign() do authManager
- Passe o role como primeiro parâmetro
- Passe o ID do utilizador como segundo parâmetro
- Um utilizador pode ter múltiplos roles
- Faça isto durante criação ou atualização do utilizador

**Executar Inicialização RBAC:**

Para aplicar as configurações:
- Execute o comando via terminal Yii
- Use php yii rbac/init (ou caminho do comando)
- Isto cria todas as permissões e roles definidos
- Execute sempre que alterar a estrutura RBAC
- Use removeAll() no início para limpar dados antigos

**Verificar Permissões no Controller:**

Para proteger actions:
- Use AccessControl behavior no controller
- Configure rules com actions e roles
- Use allow => true para permitir acesso
- Use roles => ['nomeDaPermissao'] para verificar
- Pode verificar permissões ou roles

**Verificar Permissões em Views:**

Para mostrar/ocultar elementos:
- Use Yii::$app->user->can('nomePermissao')
- Retorna true se utilizador tem permissão
- Use em condições if para mostrar botões
- Proteja links sensíveis
- Melhora experiência do utilizador

**Pontos-chave:**
- Planeie estrutura de permissões antes de implementar
- Use nomenclatura consistente para permissões
- Documente cada permissão e role
- Teste todas as combinações de permissões
- Execute rbac/init após alterações
- Proteja todas as actions sensíveis

---

## 10. Criar e Executar Migrations

Migrations são ficheiros que gerem alterações na estrutura da base de dados de forma controlada e versionada.

**O que são Migrations:**

Migrations permitem:
- Versionar alterações na base de dados
- Aplicar mudanças incrementais na estrutura
- Reverter alterações se necessário
- Manter consistência entre ambientes
- Trabalhar em equipa sem conflitos

**Criar Nova Migration:**

Para criar uma migration:
- Use comando Yii via terminal console
- Execute php yii migrate/create nome_descritivo
- Substitua nome_descritivo por descrição da alteração
- Use snake_case para o nome
- Exemplo: create_faturas_table ou add_email_to_users

**Estrutura da Migration:**

Uma migration contém:
- Método up() para aplicar alterações
- Método down() para reverter alterações
- Ou métodos safeUp() e safeDown() com transação automática
- Timestamp automático no nome do ficheiro
- Classe que estende yii\db\Migration

**Criar Tabelas:**

Para criar tabelas na migration:
- Use createTable() passando nome e estrutura
- Defina colunas com métodos de tipo (string, integer, text)
- Use primaryKey() para chave primária com auto increment
- Use notNull() para campos obrigatórios
- Use defaultValue() para valores padrão
- Adicione índices e chaves estrangeiras

**Adicionar Colunas:**

Para adicionar colunas a tabelas existentes:
- Use addColumn() com nome da tabela e coluna
- Especifique tipo da coluna
- Configure nullable ou not null
- Defina valor padrão se necessário
- No método down(), use dropColumn() para reverter

**Remover Colunas:**

Para remover colunas:
- Use dropColumn() com nome da tabela e coluna
- No método down(), use addColumn() para reverter
- Atenção: perda de dados é irreversível
- Considere backup antes de executar

**Modificar Colunas:**

Para alterar colunas existentes:
- Use alterColumn() com tabela, coluna e novo tipo
- Pode alterar tipo, tamanho, nullable
- No método down(), reverta para tipo original
- Teste em ambiente de desenvolvimento primeiro

**Criar Foreign Keys:**

Para adicionar chaves estrangeiras:
- Use addForeignKey() após criar tabelas
- Defina nome único para a constraint
- Especifique tabela e coluna origem
- Especifique tabela e coluna destino
- Configure onDelete e onUpdate (CASCADE, RESTRICT, SET NULL)
- No down(), use dropForeignKey()

**Criar Índices:**

Para adicionar índices:
- Use createIndex() para melhorar performance
- Defina nome único para o índice
- Especifique tabela e coluna(s)
- Use índices compostos quando necessário
- No down(), use dropIndex()

**Executar Migrations:**

Para aplicar migrations:
- Execute php yii migrate no terminal
- O sistema mostra migrations pendentes
- Confirme para aplicar
- Migrations executadas ficam registadas
- Não edite migrations já aplicadas

**Reverter Migrations:**

Para desfazer migrations:
- Use php yii migrate/down para reverter última
- Use php yii migrate/down 3 para reverter últimas 3
- Verifique método down() está correto
- Cuidado com perda de dados
- Teste em desenvolvimento primeiro

**Migration de Dados:**

Para manipular dados em migrations:
- Use insert(), batchInsert() para inserir dados
- Use update() para atualizar registos
- Use delete() para remover dados
- Use execute() para SQL direto quando necessário
- Útil para popular dados iniciais

**Boas Práticas em Migrations:**

Siga estas práticas:
- Crie migration para cada alteração lógica
- Use nomes descritivos e claros
- Implemente sempre método down()
- Teste em desenvolvimento antes de produção
- Não edite migrations já executadas
- Use transações (safeUp/safeDown) quando possível
- Documente alterações complexas com comentários
- Faça backup antes de migrations destrutivas

**Pontos-chave:**
- Migrations são versionamento de base de dados
- Sempre implemente up() e down()
- Use comandos migrate para aplicar/reverter
- Teste migrations em ambiente seguro
- Mantenha migrations organizadas e documentadas
- Não edite migrations já aplicadas em produção

---

## 11. Criar Testes Unitários

Testes unitários verificam o comportamento de componentes individuais (models, validators, components) de forma isolada.

**O que são Testes Unitários:**

Testes unitários:
- Testam uma unidade específica de código
- Verificam comportamento de models e suas regras
- Validam lógica de negócio isoladamente
- Usam fixtures para dados de teste
- Executam rapidamente sem dependências externas

**Estrutura de Testes Unitários:**

Testes unitários no Yii2:
- Ficam em common/tests/unit ou backend/tests/unit
- Estendem Codeception\Test\Unit
- Usam Codeception como framework de testes
- Seguem convenção NomeDoModelTest.php
- Organizam-se por tipo (models, validators, components)

**Criar Teste Unitário:**

Para criar um teste unitário:
- Crie ficheiro na pasta tests/unit/models
- Nomeie como NomeModelTest.php
- Estenda Codeception\Test\Unit
- Defina namespace apropriado
- Importe o model a ser testado
- Defina propriedade tester protegida

**Configurar Fixtures:**

Para usar fixtures nos testes:
- Implemente método _fixtures() no teste
- Retorne array com fixtures necessárias
- Use NomeFixture::class para cada fixture
- Fixtures carregam dados de teste na base de dados
- Permitem testar com dados consistentes

**Estrutura de um Teste:**

Cada método de teste deve:
- Começar com prefixo test (exemplo: testDeveValidarEmail)
- Ser público e não retornar valor
- Testar um comportamento específico
- Ter nome descritivo em português
- Incluir comentário explicativo
- Usar assertions para verificar resultados

**Assertions Comuns:**

Use assertions para verificar:
- assertTrue() e assertFalse() para booleanos
- assertEquals() para comparar valores
- assertNotEmpty() para verificar não vazio
- assertInstanceOf() para verificar tipo de objeto
- assertIsNumeric() para verificar se é número
- assertArrayHasKey() para verificar chaves em array
- assertCount() para verificar tamanho de array

**Testar Validações:**

Para testar regras de validação:
- Crie instância do model com dados
- Chame validate() no model
- Use hasErrors() para verificar se há erros
- Use getErrors() para obter mensagens de erro
- Teste cenários válidos e inválidos
- Verifique cada regra individualmente

**Testar Relacionamentos:**

Para testar relações entre models:
- Use fixtures para models relacionados
- Carregue model via fixture
- Aceda propriedade de relação
- Verifique se retorna tipo correto (array ou object)
- Use assertInstanceOf() para verificar classe
- Teste eager loading e lazy loading

**Testar Métodos do Model:**

Para testar métodos personalizados:
- Crie instância do model
- Chame o método a testar
- Verifique retorno com assertions
- Teste diferentes cenários e inputs
- Verifique comportamento esperado
- Teste casos limite (edge cases)

**Executar Testes Unitários:**

Para executar os testes:
- Use comando codecept no terminal
- Execute vendor/bin/codecept run unit
- Especifique teste específico com caminho do ficheiro
- Use --debug para ver output detalhado
- Verifique relatório de cobertura
- Todos os testes devem passar

**Organização dos Testes:**

Organize testes por:
- Pasta models/ para testes de models
- Pasta validators/ para testes de validators
- Pasta components/ para testes de components
- Um ficheiro de teste por classe testada
- Agrupe testes relacionados no mesmo ficheiro

**Pontos-chave:**
- Teste uma unidade de cada vez
- Use fixtures para dados consistentes
- Nomeie testes de forma descritiva
- Teste cenários válidos e inválidos
- Mantenha testes independentes entre si
- Execute testes regularmente durante desenvolvimento
- Cubra regras de validação e métodos importantes

---

## 12. Criar Testes Funcionais

Testes funcionais verificam o comportamento da aplicação do ponto de vista do utilizador, testando interações completas.

**O que são Testes Funcionais:**

Testes funcionais:
- Testam fluxos completos de utilizador
- Simulam interações com a aplicação
- Verificam comportamento de controllers e views
- Testam formulários, autenticação, navegação
- Usam FunctionalTester para simular requests

**Estrutura de Testes Funcionais:**

Testes funcionais:
- Ficam em frontend/tests/functional ou backend/tests/functional
- Usam convenção NomeCest.php (Cest = Codeception Test)
- Contêm múltiplos cenários de teste
- Simulam utilizador navegando na aplicação
- Não usam browser real (mais rápidos que acceptance)

**Criar Teste Funcional:**

Para criar teste funcional:
- Crie ficheiro na pasta tests/functional
- Nomeie como NomeCest.php
- Não precisa estender classe específica
- Cada método é um cenário de teste
- Receba FunctionalTester como parâmetro em cada teste

**Configurar Fixtures:**

Para fixtures em testes funcionais:
- Implemente método _fixtures()
- Retorne array com fixtures necessárias
- Pode especificar dataFile personalizado
- Fixtures são carregadas antes de cada teste
- Use fixtures de User para testes de autenticação

**Método _before():**

Para preparação antes de testes:
- Implemente _before(FunctionalTester $I)
- Execute ações comuns antes de cada teste
- Use para navegar para página inicial
- Configure estado inicial da aplicação
- Útil para login ou configurações globais

**Navegar entre Páginas:**

Para navegar na aplicação:
- Use amOnPage() com URL
- Use amOnRoute() com route do Yii
- Use click() para clicar em links
- Use seeCurrentUrlEquals() para verificar URL
- Use seeInTitle() para verificar título da página

**Testar Formulários:**

Para testar submissão de formulários:
- Use fillField() para preencher campos
- Use selectOption() para dropdowns
- Use checkOption() para checkboxes
- Use submitForm() para submeter com dados
- Passe ID ou CSS selector do formulário
- Passe array com dados do formulário

**Verificar Conteúdo:**

Para verificar conteúdo na página:
- Use see() para verificar texto visível
- Use dontSee() para verificar ausência
- Use seeElement() para verificar elemento HTML
- Use seeLink() para verificar links
- Use seeInField() para verificar valor em campo
- Use seeCheckboxIsChecked() para checkboxes

**Testar Validação:**

Para testar mensagens de validação:
- Submeta formulário com dados inválidos
- Use seeValidationError() para verificar mensagem
- Teste campos obrigatórios vazios
- Teste formatos inválidos
- Verifique que formulário não é submetido

**Testar Autenticação:**

Para testar login e logout:
- Use submitForm() com credenciais
- Verifique redirecionamento após login
- Use see() para verificar nome de utilizador
- Teste login com credenciais inválidas
- Teste logout e verificar redirecionamento
- Use fixtures para utilizadores de teste

**Testar Autorização:**

Para testar controlo de acesso:
- Tente aceder páginas protegidas sem login
- Verifique redirecionamento para login
- Teste acesso com utilizadores de diferentes roles
- Verifique mensagens de erro apropriadas
- Use amLoggedInAs() para simular login

**Verificar Respostas HTTP:**

Para verificar códigos de resposta:
- Use seeResponseCodeIs() para verificar código
- 200 para sucesso
- 302 para redirecionamento
- 404 para não encontrado
- 403 para acesso negado
- 500 para erro de servidor

**Executar Testes Funcionais:**

Para executar os testes:
- Use comando codecept
- Execute vendor/bin/codecept run functional
- Especifique teste ou classe específica
- Use --debug para ver output detalhado
- Use --steps para ver cada passo executado
- Verifique relatório de resultados

**Organização dos Testes:**

Organize testes funcionais por:
- Funcionalidade ou módulo
- Um Cest por fluxo de utilizador
- Múltiplos cenários no mesmo Cest
- Nomes descritivos para cenários
- Agrupe testes relacionados

**Pontos-chave:**
- Teste fluxos completos de utilizador
- Simule interações reais com aplicação
- Teste formulários e validações
- Verifique autenticação e autorização
- Use fixtures para dados consistentes
- Nomeie cenários de forma descritiva
- Execute regularmente para detectar regressões
- Complemente com testes unitários

---

## 13. Habilitar Pretty URLs

Pretty URLs removem o index.php da URL e tornam os endereços mais amigáveis e legíveis.

**O que são Pretty URLs:**

Pretty URLs transformam:
- De: http://localhost/backend/web/index.php?r=fatura/view&id=1
- Para: http://localhost/backend/web/fatura/view/1
- Melhora SEO e experiência do utilizador
- URLs mais limpas e profissionais
- Facilita partilha de links

**Configurar UrlManager:**

Para habilitar pretty URLs:
- Aceda ao ficheiro de configuração (backend/config/main.php ou frontend/config/main.php)
- Localize a seção components
- Configure o componente urlManager
- Defina enablePrettyUrl como true
- Defina showScriptName como false
- Configure rules conforme necessário

**Regras Básicas:**

Para configurar regras de URL:
- Use array vazio para regras padrão
- Adicione regras personalizadas se necessário
- Formato: 'padrão' => 'rota'
- Exemplo: 'sobre' => 'site/about'
- Exemplo: 'contacto' => 'site/contact'

**Configurar Apache (.htaccess):**

Para Apache com mod_rewrite:
- Crie ficheiro .htaccess na pasta web/
- Configure RewriteEngine On
- Defina RewriteBase apropriado
- Adicione regra para redirecionar para index.php
- Verifique se mod_rewrite está ativo no Apache

**Conteúdo do .htaccess:**

O ficheiro deve conter:
- RewriteEngine on
- RewriteCond para verificar se ficheiro/pasta não existe
- RewriteRule para redirecionar para index.php
- Opcional: regras para forçar trailing slash
- Opcional: regras para WWW ou não-WWW

**Configurar Nginx:**

Para servidor Nginx:
- Edite ficheiro de configuração do site
- Adicione bloco location para processar URLs
- Use try_files para verificar ficheiros
- Redirecione para index.php com query string
- Reinicie Nginx após alterações

**Testar Pretty URLs:**

Para verificar se funciona:
- Aceda à aplicação pelo navegador
- Clique em links da aplicação
- Verifique se URLs não têm index.php
- Teste navegação direta digitando URL
- Verifique logs de erro se não funcionar

**Problemas Comuns:**

Se pretty URLs não funcionarem:
- Verifique se mod_rewrite está ativo (Apache)
- Confirme que .htaccess existe e está correto
- Verifique permissões do ficheiro .htaccess
- Limpe cache da aplicação
- Verifique configuração do urlManager
- Consulte logs de erro do servidor web

**Pretty URLs em Ambos Frontend e Backend:**

Para aplicação advanced:
- Configure urlManager no frontend/config/main.php
- Configure urlManager no backend/config/main.php
- Crie .htaccess em frontend/web/
- Crie .htaccess em backend/web/
- Configure virtual hosts separados se necessário

**Criar Links com Pretty URLs:**

Para criar links na aplicação:
- Use sempre Html::a() para links
- Use Url::to() para gerar URLs
- Passe array com rota e parâmetros
- O Yii gera automaticamente URL formatada
- Exemplo: ['fatura/view', 'id' => $model->id]

**Pontos-chave:**
- Pretty URLs melhoram usabilidade e SEO
- Configure urlManager em ambos frontend e backend
- Crie .htaccess apropriado para Apache
- Use sempre helpers do Yii para gerar links
- Teste após configurar
- Verifique logs se houver problemas

---

## 14. Boas Práticas

### Boas Práticas para Models

**Soft Deletes:**
- Use sempre eliminação lógica em vez de física
- Adicione campo eliminado (padrão 0)
- Filtre sempre por eliminado = 0 nas queries
- Permite recuperar dados eliminados se necessário

**Timestamps Automáticos:**
- Use TimestampBehavior para gerir datas automaticamente
- Configure createdAtAttribute para created_at
- Configure updatedAtAttribute para updated_at
- Use Expression('NOW()') para valor da data

**Validação de Chaves Estrangeiras:**
- Use exist validator para validar chaves estrangeiras
- Configure skipOnError como true
- Defina targetClass apontando para o model relacionado
- Especifique targetAttribute com o mapeamento de chaves

**Eager Loading:**
- Use with() para carregar relações antecipadamente
- Evita problema de N+1 queries
- Carregue múltiplas relações num só with()
- Use notação de ponto para relações aninhadas

### Boas Práticas para Views

**GridView com Pjax:**
- Envolva sempre GridView com Pjax para melhor performance
- Define ID único para cada container Pjax
- Permite filtragem e ordenação sem reload completo
- Melhora significativamente a experiência do utilizador

**Classes Bootstrap:**
- Use classes Bootstrap para styling consistente
- Utilize componentes como cards, badges, buttons
- Mantenha design responsivo com grid system
- Adicione ícones FontAwesome para melhor visual

**Mensagens de Empty State:**
- Defina mensagens significativas quando não há dados
- Use alerts do Bootstrap para destacar
- Seja específico sobre o que não foi encontrado
- Adicione ícones para melhor comunicação visual

**Formatação de Dados:**
- Use Yii formatters para datas (asDate, asDatetime)
- Use number_format() para valores monetários
- Mantenha formato consistente em toda aplicação
- Configure locale para formato português

### Boas Práticas para Controllers

**AccessControl:**
- Configure sempre AccessControl para autorização RBAC
- Defina roles específicas para cada action
- Separe permissões de visualização e edição
- Proteja todas as actions sensíveis

**VerbFilter:**
- Use VerbFilter para restringir métodos HTTP
- Delete deve ser sempre POST
- Previne operações indesejadas via GET
- Aumenta segurança da aplicação

**Flash Messages:**
- Defina flash messages para feedback ao utilizador
- Use categorias (success, error, warning, info)
- Mensagens em português e claras
- Exiba na view layout principal

**Validação de Propriedade:**
- Valide propriedade antes de permitir edições
- Verifique se utilizador tem permissão sobre o registo
- Use findModel() para centralizar validação
- Lance exceções apropriadas quando não autorizado

**Transações:**
- Use transações quando modificar múltiplas tabelas
- Use beginTransaction() antes das operações
- Faça commit() se tudo correr bem
- Faça rollBack() em caso de erro
- Previne inconsistências na base de dados

### Boas Práticas para Search Models

**Métodos Estáticos:**
- Crie métodos estáticos para queries comuns
- Obtenha estatísticas e contadores
- Prepare listas para Select2 e dropdowns
- Centralize lógica de queries complexas
- Retorne dados formatados prontos para view

**Filtros Opcionais:**
- Use andFilterWhere() para filtros opcionais
- Não adiciona condição se valor for vazio
- Permite filtros flexíveis na interface
- Melhora usabilidade do sistema

**Ordenação Padrão:**
- Configure defaultOrder no ActiveDataProvider
- Define como dados aparecem inicialmente
- Use SORT_DESC para registos mais recentes primeiro
- Permite utilizador alterar ordenação depois

**Paginação:**
- Configure pageSize apropriado (10-50 registos)
- Considere performance com muitos dados
- Permita utilizador alterar pageSize se necessário
- Balance entre usabilidade e performance

**Eager Loading:**
- Faça eager loading de relações na query do search
- Evita queries N+1 na GridView
- Melhora significativamente performance
- Carregue apenas relações necessárias

### Boas Práticas para Relacionamentos

**Relacionamentos Inversos:**
- Defina sempre relacionamentos inversos em ambos models
- Permite navegação bidirecional entre models
- Facilita acesso a dados relacionados
- Melhora legibilidade do código

**Filtros em Relacionamentos:**
- Use where() nos métodos de relacionamento
- Filtre soft deletes automaticamente
- Aplique condições específicas do negócio
- Mantém queries limpas no resto do código

**Ordenação em Relacionamentos:**
- Use orderBy() para ordenar relações
- Define ordem consistente dos dados
- Especialmente útil para hasMany
- Evita surpresas na apresentação de dados

**Relações Aninhadas:**
- Use notação de ponto para with() aninhado
- Carregue relações de múltiplos níveis
- Mantém código limpo e eficiente
- Exemplo: with(['linhasfaturas.servicos'])

---

## Resumo de Padrões Comuns

**Fluxo de Trabalho Master-Detail:**

1. Criar tabelas na base de dados com foreign keys
2. Criar migration para as tabelas
3. Executar migration com php yii migrate
4. Gerar models com Gii no namespace common\models
5. Verificar e personalizar relacionamentos nos models
6. Criar testes unitários para os models
7. Gerar CRUD para ambos os models
8. Criar search models no namespace backend\models
9. Personalizar controllers com access control
10. Configurar RBAC com roles e permissões
11. Executar php yii rbac/init para aplicar RBAC
12. Configurar Pretty URLs no urlManager
13. Criar .htaccess para Apache ou configurar Nginx
14. Construir view master com GridView e Pjax
15. Construir view detail mostrando registos relacionados
16. Adicionar links criar/editar/eliminar para details
17. Criar testes funcionais para os fluxos principais
18. Testar workflow completo manualmente
19. Executar testes automatizados com codecept

**Organização de Ficheiros:**

- **Models**: common/models/ (partilhados entre frontend e backend)
- **Search Models**: backend/models/ ou frontend/models/
- **Controllers**: backend/controllers/ ou frontend/controllers/
- **Views**: backend/views/ ou frontend/views/
- **Assets**: backend/assets/ ou frontend/assets/
- **Widgets**: common/widgets/ (componentes reutilizáveis)

**Convenções de Nomenclatura:**

- **Models**: Singular, primeira letra maiúscula (Fatura, Animal)
- **Tabelas**: Plural, minúsculas (faturas, animais)
- **Controllers**: Nome do model + Controller (FaturaController)
- **Views**: Pasta com nome plural minúsculo do model (fatura/)
- **Relações hasMany**: Nome plural (getLinhasfaturas)
- **Relações hasOne**: Nome singular (getFatura)

---

## Recursos Adicionais

- **Yii2 Guide**: Documentação oficial completa do framework
- **Gii Documentation**: Guia de uso do gerador de código
- **Active Record**: Documentação sobre ORM e relacionamentos
- **GridView Widget**: Documentação do widget de tabelas
- **Pjax**: Guia de implementação de AJAX no Yii2
- **RBAC**: Sistema de controlo de acesso baseado em roles
- **Migrations**: Guia de versionamento de base de dados
- **Testing**: Documentação sobre testes com Codeception
- **Kartik Widgets**: Biblioteca de widgets Bootstrap para Yii2

---

**Versão do Documento**: 1.0  
**Criado**: Janeiro 2025  
**Projeto**: Yii2 Advanced Template com Relacionamentos Master-Detail

---

## 15. Exemplo Completo: Sistema de Livros e Capítulos

Este exemplo demonstra como implementar um sistema completo de gestão de livros com capítulos, aplicando todos os conceitos anteriores.

### Visão Geral do Sistema

**Estrutura:**
- **Livros (Master)**: Tabela principal com informação dos livros
- **Capítulos (Detail)**: Tabela relacionada com capítulos de cada livro
- Cada livro pode ter múltiplos capítulos
- Cada capítulo pertence a apenas um livro
- Cada capítulo tem uma descrição única

### Passo 1: Criar Estrutura da Base de Dados

**Tabela Livros (Master):**

Campos necessários:
- id (chave primária, auto increment)
- titulo (string, obrigatório)
- autor (string, obrigatório)
- isbn (string, único)
- ano_publicacao (integer)
- editora (string)
- eliminado (integer, padrão 0)
- created_at (datetime)
- updated_at (datetime)

**Tabela Capítulos (Detail):**

Campos necessários:
- id (chave primária, auto increment)
- livros_id (integer, obrigatório, foreign key)
- numero (integer, número do capítulo)
- titulo (string, obrigatório)
- descricao (text, descrição única do capítulo)
- pagina_inicial (integer)
- eliminado (integer, padrão 0)
- created_at (datetime)
- updated_at (datetime)

**Foreign Key:**
- capitulos.livros_id referencia livros.id
- onDelete: CASCADE (elimina capítulos quando livro é eliminado)
- onUpdate: CASCADE

### Passo 2: Criar Migration

**Criar ficheiro de migration:**

Execute no terminal:
- php yii migrate/create create_livros_and_capitulos_tables

**Implementar método up():**

No método up() da migration:
- Use createTable() para criar tabela livros
- Defina todas as colunas com tipos apropriados
- Use primaryKey() para id
- Use notNull() para campos obrigatórios
- Use createTable() para criar tabela capitulos
- Adicione coluna livros_id como integer notNull
- Use addForeignKey() para criar relação
- Defina nome como fk-capitulos-livros_id
- Configure CASCADE para delete e update

**Implementar método down():**

No método down():
- Use dropForeignKey() primeiro
- Use dropTable() para capitulos
- Use dropTable() para livros
- Ordem inversa da criação

**Executar migration:**
- Execute php yii migrate
- Confirme a execução
- Verifique tabelas na base de dados

### Passo 3: Gerar Models com Gii

**Gerar Model Livro:**

Acesse Gii Model Generator:
- Table Name: livros
- Model Class: Livro
- Namespace: common\models
- Marque Generate Relations
- Preview e Generate

**Gerar Model Capitulo:**

Acesse Gii Model Generator:
- Table Name: capitulos
- Model Class: Capitulo
- Namespace: common\models
- Marque Generate Relations
- Preview e Generate

### Passo 4: Personalizar Models

**No Model Livro:**

Adicione behaviors:
- Configure TimestampBehavior para created_at e updated_at
- Use Expression('NOW()') como valor

Personalize método getCapitulos():
- Retorna hasMany(Capitulo::class, ['livros_id' => 'id'])
- Adicione where(['eliminado' => 0])
- Adicione orderBy(['numero' => SORT_ASC])
- Permite carregar capítulos ordenados

Adicione método getNumeroCapitulos():
- Retorna count dos capítulos não eliminados
- Use count() na relação
- Útil para mostrar estatística

Personalize attributeLabels():
- Traduza labels para português
- Exemplo: 'titulo' => 'Título'
- Exemplo: 'autor' => 'Autor'
- Exemplo: 'ano_publicacao' => 'Ano de Publicação'

**No Model Capitulo:**

Adicione behaviors:
- Configure TimestampBehavior igual ao Livro

Personalize método getLivro():
- Retorna hasOne(Livro::class, ['id' => 'livros_id'])

Adicione validação personalizada:
- Valide que numero é único dentro do mesmo livro
- Use validator unique com targetAttribute

Personalize attributeLabels():
- Traduza para português
- Exemplo: 'numero' => 'Número'
- Exemplo: 'titulo' => 'Título'
- Exemplo: 'descricao' => 'Descrição'
- Exemplo: 'pagina_inicial' => 'Página Inicial'

### Passo 5: Gerar CRUD com Gii

**Gerar CRUD para Livro:**

Acesse Gii CRUD Generator:
- Model Class: common\models\Livro
- Search Model Class: backend\models\LivroSearch
- Controller Class: backend\controllers\LivroController
- View Path: padrão
- Marque Enable Pjax
- Generate

**Gerar CRUD para Capitulo:**

Acesse Gii CRUD Generator:
- Model Class: common\models\Capitulo
- Search Model Class: backend\models\CapituloSearch
- Controller Class: backend\controllers\CapituloController
- View Path: padrão
- Marque Enable Pjax
- Generate

### Passo 6: Criar Search Models

**LivroSearch:**

Personalize método search():
- Adicione where(['eliminado' => 0])
- Configure defaultOrder por titulo ASC
- Configure pageSize como 20
- Adicione filtros para titulo, autor, isbn
- Use andFilterWhere(['like', 'titulo', $this->titulo])

Adicione métodos estáticos:
- getTotalCount(): retorna total de livros ativos
- getLivrosComCapitulos(): retorna livros com count de capítulos
- getLivrosParaDropdown(): retorna array id => titulo

**CapituloSearch:**

Personalize método search():
- Adicione where(['eliminado' => 0])
- Configure defaultOrder por numero ASC
- Configure eager loading: with(['livro'])
- Adicione filtro por livros_id
- Permita filtrar por titulo do capítulo

Adicione métodos estáticos:
- getCapitulosPorLivro($livro_id): retorna capítulos de um livro
- getTotalCapitulos(): total de capítulos no sistema

### Passo 7: Configurar RBAC

**Criar Permissões:**

No RbacController action init():
- createPermission('viewBooks') - ver livros
- createPermission('createBook') - criar livro
- createPermission('updateBook') - editar livro
- createPermission('deleteBook') - eliminar livro
- createPermission('viewChapters') - ver capítulos
- createPermission('createChapter') - criar capítulo
- createPermission('updateChapter') - editar capítulo
- createPermission('deleteChapter') - eliminar capítulo

**Criar Roles:**

Defina roles apropriadas:
- Role 'leitor': apenas viewBooks e viewChapters
- Role 'editor': todas as permissões de books e chapters
- Role 'admin': herda todas as permissões

**Atribuir Permissões:**

Use addChild() para cada role:
- Leitor recebe view permissions
- Editor recebe todas as permissions
- Admin herda role de editor

**Executar:**
- Execute php yii rbac/init
- Verifique criação na base de dados

### Passo 8: Configurar Controllers

**LivroController:**

Configure behaviors():
- Adicione AccessControl com rules
- viewBooks para index e view
- createBook para create
- updateBook para update
- deleteBook para delete
- Configure VerbFilter para delete POST

Personalize actionIndex():
- Adicione contadores usando LivroSearch
- Passe para view para exibir cards

Personalize actionView():
- Carregue livro com capítulos
- Conte número de capítulos
- Passe dados extras para view

Personalize actionDelete():
- Implemente soft delete
- Altere eliminado para 1
- Mantenha dados na base

**CapituloController:**

Configure behaviors():
- Adicione AccessControl similar
- Use permissões de chapters

Personalize actionCreate():
- Aceite parâmetro livro_id opcional
- Preencha automaticamente se fornecido
- Após save, redirecione para livro/view
- Passe livro_id no redirect

Personalize actionUpdate():
- Verifique se capítulo existe
- Após save, redirecione para livro/view
- Mantenha contexto do livro

Personalize actionDelete():
- Soft delete do capítulo
- Redirecione para view do livro
- Mostre flash message

### Passo 9: Criar Views

**livro/index.php:**

Estruture a view:
- Adicione cards com estatísticas no topo
- Total de livros, livros com capítulos, etc
- Envolva GridView com Pjax
- Configure ID único: livro-grid

Configure GridView:
- Coluna titulo com link para view
- Coluna autor
- Coluna ano_publicacao
- Coluna personalizada para número de capítulos
- Use value function para contar
- ActionColumn com view, update, delete
- Configure emptyText em português
- Use tableOptions para classes Bootstrap

**livro/view.php:**

Organize em seções:
- Card com informações do livro
- Mostre titulo, autor, isbn, editora, ano
- Use badges para destacar informações
- Formate datas com formatter do Yii

Adicione seção de capítulos:
- Card separado para listar capítulos
- Tabela HTML com colunas: número, título, páginas
- Botão para adicionar novo capítulo
- Passe livro_id como parâmetro no link
- Mostre descrição resumida (primeiros 100 caracteres)
- Links para editar e eliminar cada capítulo
- Configure data-confirm para delete
- Use data-method post

Adicione botões de ação:
- Update livro
- Delete livro (com confirmação)
- Voltar para index

**livro/_form.php:**

Configure formulário:
- Use ActiveForm com Bootstrap
- Campo titulo como textInput
- Campo autor como textInput
- Campo isbn como textInput
- Campo ano_publicacao como número
- Campo editora como textInput
- Agrupe campos relacionados em rows
- Use col-md-6 para campos menores

**capitulo/index.php:**

Configure GridView:
- Adicione filtro por livro (Select2)
- Coluna numero
- Coluna titulo
- Coluna livro (com link)
- Use value function para aceder livro->titulo
- Coluna pagina_inicial
- Coluna com preview da descrição
- ActionColumn

**capitulo/view.php:**

Mostre informações completas:
- Card com dados do capítulo
- Número, título, página inicial
- Link para o livro pai
- Card separado para descrição completa
- Use text area ou div para descrição longa
- Botões de editar e eliminar
- Botão para voltar ao livro

**capitulo/_form.php:**

Configure campos:
- Hidden field para livros_id (se vier do livro)
- Ou Select2 para escolher livro
- Campo numero como número
- Campo titulo como texto
- Campo descricao como textarea
- Configure rows => 6 para textarea
- Campo pagina_inicial como número
- Submeter e cancelar buttons

### Passo 10: Configurar Pretty URLs

**No backend/config/main.php:**

Configure urlManager:
- enablePrettyUrl como true
- showScriptName como false
- Adicione rules vazias ou personalizadas

**Criar .htaccess em backend/web/:**

Configure mod_rewrite:
- RewriteEngine On
- RewriteCond para verificar ficheiros
- RewriteRule para index.php
- Teste navegação após configurar

### Passo 11: Criar Testes

**Teste Unitário LivroTest:**

Crie em common/tests/unit/models/LivroTest.php:
- Teste validação de campos obrigatórios
- Teste relacionamento getCapitulos()
- Teste método getNumeroCapitulos()
- Use fixtures para dados de teste
- Verifique retornos corretos

**Teste Unitário CapituloTest:**

Crie em common/tests/unit/models/CapituloTest.php:
- Teste validação de campos
- Teste relacionamento getLivro()
- Teste unicidade de numero por livro
- Verifique validações personalizadas

**Teste Funcional LivroCest:**

Crie em backend/tests/functional/LivroCest.php:
- Teste acesso à página index
- Teste criação de livro
- Teste edição de livro
- Teste visualização de livro
- Teste eliminação de livro
- Use fixtures apropriadas

**Teste Funcional CapituloCest:**

Crie em backend/tests/functional/CapituloCest.php:
- Teste criação de capítulo vinculado a livro
- Teste edição de capítulo
- Teste eliminação e redirect para livro
- Verifique relacionamento funciona

**Executar testes:**
- Execute vendor/bin/codecept run unit
- Execute vendor/bin/codecept run functional
- Verifique todos passam

### Passo 12: Testar Sistema Completo

**Fluxo de Teste Manual:**

1. Aceda ao backend da aplicação
2. Navegue para livros/index
3. Crie um novo livro
4. Preencha todos os campos obrigatórios
5. Grave e verifique redirecionamento para view
6. Na view do livro, clique em adicionar capítulo
7. Preencha dados do capítulo com descrição
8. Grave e verifique volta para livro
9. Verifique capítulo aparece na lista
10. Adicione mais capítulos
11. Teste edição de capítulo
12. Teste eliminação de capítulo
13. Teste edição de livro
14. Teste listagem com filtros
15. Verifique pretty URLs funcionam
16. Teste permissões RBAC
17. Verifique soft delete funciona

**Verificações Importantes:**

Confirme que:
- Relacionamento master-detail funciona
- Capítulos aparecem ordenados por número
- Descrição única de cada capítulo é guardada
- Soft delete não remove fisicamente
- Pretty URLs estão ativos
- Pjax funciona sem reload
- Filtros e pesquisa funcionam
- Permissões RBAC protegem actions
- Flash messages aparecem
- Validações funcionam corretamente

### Resumo do Exemplo

Este exemplo demonstrou:
- Criação de estrutura master-detail completa
- Uso de migrations para versionar base de dados
- Geração e personalização de models
- Implementação de CRUD com Gii
- Configuração de RBAC para segurança
- Criação de views com Pjax e Bootstrap
- Implementação de relacionamentos bidirecionais
- Pretty URLs para melhor UX
- Testes unitários e funcionais
- Soft deletes em todo o sistema
- Eager loading para performance
- Ordenação automática de dados relacionados

**Estrutura Final de Ficheiros:**

Models:
- common/models/Livro.php
- common/models/Capitulo.php

Search Models:
- backend/models/LivroSearch.php
- backend/models/CapituloSearch.php

Controllers:
- backend/controllers/LivroController.php
- backend/controllers/CapituloController.php

Views:
- backend/views/livro/ (index, view, create, update, _form)
- backend/views/capitulo/ (index, view, create, update, _form)

Migrations:
- console/migrations/m[timestamp]_create_livros_and_capitulos_tables.php

RBAC:
- console/controllers/RbacController.php

Testes:
- common/tests/unit/models/LivroTest.php
- common/tests/unit/models/CapituloTest.php
- backend/tests/functional/LivroCest.php
- backend/tests/functional/CapituloCest.php

Configuração:
- backend/config/main.php (urlManager)
- backend/web/.htaccess

Este exemplo pode ser adaptado para qualquer outro relacionamento master-detail seguindo os mesmos princípios e padrões estabelecidos.

---
