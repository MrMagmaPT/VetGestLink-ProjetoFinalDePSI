# Workflow Técnico de Desenvolvimento Yii2

## 1. Setup do Yii
1.  **Composer:** Execute o comando `composer create-project` (basic ou advanced) para baixar o framework e dependências.
2.  **Configuração DB:** Edite o arquivo `config/db.php` (ou `common/config/main-local.php` no advanced) com as credenciais do banco.
3.  **Init (Apenas Advanced):** Rode `php init` no terminal para inicializar os ambientes (dev/prod) e criar os arquivos de entrada.

---

## 2. Model (ActiveRecord)
1.  **DB:** Crie a tabela física no banco de dados (ex: `cliente`) definindo chaves primárias e tipos de dados.
2.  **Gii:** Acesse o *Model Generator*. Selecione a tabela `cliente`.
3.  **Class:** O Gii gera a classe `Cliente.php` dentro de `models/`, mapeando automaticamente as colunas da tabela para atributos e criando as regras de validação (`rules`) baseadas no schema (ex: `required`, `integer`, `string`).

---

## 3. Controller
1.  **Gii:** Acesse o *Controller Generator*.
2.  **Definição:** Defina a classe do Controller (ex: `app\controllers\ClienteController`).
3.  **Actions:** O Gii gera a classe estendendo `yii\web\Controller` com as Actions básicas (`actionIndex`, `actionView`, etc).
4.  **Rotas:** O Yii mapeia automaticamente a URL `cliente/create` para o método `actionCreate` deste controller.

---

## 4. Search Model
1.  **Conceito:** É uma classe separada usada especificamente para filtrar dados em Grids e Listas.
2.  **Gii:** Ao usar o *CRUD Generator*, o Gii cria automaticamente (ex: `ClienteSearch.php`).
3.  **Estrutura:** Esta classe estende o Model original (`Cliente`), mas adiciona regras de validação "safe" para todos os campos pesquisáveis.
4.  **Logica:** Implementa o método `search($params)` que retorna um `ActiveDataProvider`, aplicando filtros `andFilterWhere` na query baseados no input do usuário.

---

## 5. Master-Detail (Relacionamento Pai/Filho)
1.  **DB:** Crie duas tabelas. A tabela "Filho" (Detail) **obrigatoriamente** deve ter uma Foreign Key (FK) apontando para a tabela "Pai" (Master).
2.  **Models:** Gere os modelos para ambas via Gii. Certifique-se de que o Model Pai tenha a relação `hasMany` (ex: `getItens`) e o Filho tenha `hasOne` (ex: `getPedido`).
3.  **Controllers:** Gere os controllers padrão.
4.  **Logica de Criação:** No Controller do Filho (Detail), a `actionCreate` deve receber o ID do Pai como parâmetro (ex: `actionCreate($master_id)`).
5.  **Vínculo:** Dentro dessa action, ao instanciar o novo modelo Filho, atribua o valor recebido à propriedade da FK (ex: `$model->pedido_id = $master_id`) antes de salvar, garantindo que o item pertença àquele pai específico.

---

## 6. RBAC (Role-Based Access Control)
1.  **Configuração:** Ative o componente `authManager` no arquivo de configuração (`web.php` ou `main.php`) apontando para `yii\rbac\DbManager`.
2.  **Migrations:** Rode as migrations nativas do Yii (`yii migrate --migrationPath=@yii/rbac/migrations`) para criar as 4 tabelas de controle de acesso no banco.
3.  **Definição (Console):** Crie um Controller de Console (ex: `RbacController`) para definir programaticamente a hierarquia: criar Permissões (ex: `criarPost`), criar Roles (ex: `admin`), e adicionar as permissões às roles (`addChild`).
4.  **Linkar:** Atribua as Roles aos IDs dos usuários (`auth->assign`).
5.  **Behaviors:** Nos Controllers da aplicação, adicione o behavior `AccessControl` definindo quais Roles podem acessar quais Actions.