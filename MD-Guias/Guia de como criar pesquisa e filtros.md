# Lógica de Pesquisa e Fluxo de Dados do Projeto

## 1. Pesquisa de Usuários (User)

### 1.1 O Ponto de Entrada: Controller (`actionIndex`)
Tudo começa na função `actionIndex` dentro do ficheiro `UserController.php`.

**O que faz:** Cria uma instância de `UserSearch` (o modelo de pesquisa) e chama o método `search()` passando os parâmetros da URL (`Yii::$app->request->queryParams`). O resultado é um objeto `DataProvider` que é enviado para a view `index` para desenhar a tabela.

### 1.2 A Lógica de Pesquisa: Search Model (`search`)
A inteligência está no ficheiro `UserSearch.php`, método `search($params)`.

**Passo 1: Query Base**
O sistema inicia preparando uma busca na tabela `user`.

**Passo 2: Filtros de Texto Parcial (`like`)**
Diferente de uma busca exata, aqui usamos `like`. Isso permite que o administrador encontre um usuário digitando apenas parte do nome ou email.

```php
$query->andFilterWhere(['like', 'username', $this->username])
      ->andFilterWhere(['like', 'email', $this->email]);
```

**Passo 3: Filtro de Status**
Para o status (Ativo/Inativo), a busca é exata (integer), pois é um campo controlado (dropdown):

```php
$query->andFilterWhere(['status' => $this->status]);
```

### 1.3 A Interface: View (`index.php`)
A GridView recebe o `DataProvider`. Se o usuário digitar "admin" no campo de busca do cabeçalho da tabela, o Grid recarrega, envia "admin" para o `actionIndex` -> `search()` -> filtra a query -> devolve apenas os administradores.

---

## 2. Pesquisa de Pedidos (Order) - Master/Detail

### 2.1 O Ponto de Entrada: Controller (`actionIndex`)
A execução inicia em `OrderController.php`, na `actionIndex`.

**O que faz:** Instancia o `OrderSearch`, carrega os filtros da URL e prepara os dados. Aqui é comum ter validações de acesso para garantir que vendedores vejam apenas seus próprios pedidos, se aplicável.

### 2.2 A Lógica de Pesquisa: Search Model (`search`)
Localizada em `OrderSearch.php`.

**Passo 1: Joins Obrigatórios (`joinWith`)**
Como muitas vezes queremos buscar pelo nome do cliente ou pelo produto comprado, o Search Model precisa "conversar" com outras tabelas.

```php
// Permite buscar dados da tabela relacionada 'customer'
$query->joinWith(['customer']);
```

**Passo 2: Filtro de Data Inteligente**
Geralmente, busca-se pedidos "Do dia X ao dia Y". O Search Model trata isso convertendo strings de data para o formato do banco (Y-m-d H:i:s):

```php
if ($this->created_at) {
    $query->andFilterWhere(['between', 'order_date', $start_date, $end_date]);
}
```

**Passo 3: Ordenação Personalizada**
O sistema é configurado para mostrar os pedidos mais recentes primeiro (`SORT_DESC` no `id` ou `created_at`), facilitando a visualização rápida do que acabou de acontecer.

### 2.3 A Interface: View (`index.php`)
Mostra a lista de pedidos. Graças ao `joinWith` feito no Search Model, a coluna "Cliente" é ordenável e pesquisável, mesmo sendo um dado externo à tabela `order`.

---

## 3. Pesquisa de Produtos (Product)

### 3.1 O Ponto de Entrada: Controller (`actionIndex`)
Executado em `ProductController.php`.

**O que faz:** Simples e direto: chama o `ProductSearch` e renderiza a lista de produtos disponíveis.

### 3.2 A Lógica de Pesquisa: Search Model (`search`)
Reside em `ProductSearch.php`.

**Passo 1: Filtro de Preço (Range)**
Aqui a lógica muda um pouco. Muitas vezes não queremos um preço exato, mas uma faixa. O Search Model pode implementar lógica para "preço mínimo" e "preço máximo":

```php
$query->andFilterWhere(['>=', 'price', $this->min_price])
      ->andFilterWhere(['<=', 'price', $this->max_price]);
```

**Passo 2: Busca por Categoria**
Se o produto tem uma `category_id`, o filtro aqui é exato (dropdown na view), permitindo isolar rapidamente apenas "Eletrônicos" ou "Móveis".

### 3.3 A Interface: View (`index.php`)
A view exibe os produtos e, crucialmente, utiliza widgets como `LinkPager` para paginação, garantindo que se houver 10.000 produtos, o sistema não trave tentando carregar todos de uma vez (carrega 20 por página, definido no `pageSize` do DataProvider).

---

## 4. Controle de Acesso (RBAC)

### 4.1 O Ponto de Entrada: Behaviors no Controller
Diferente dos anteriores, aqui não é uma "pesquisa" visual, mas uma "verificação" invisível que acontece antes de qualquer código rodar.

**Onde ocorre:** No método `behaviors()` de qualquer Controller (ex: `PostController.php`).

### 4.2 A Lógica de Filtragem: AccessControl
O Yii intercepta a requisição antes da Action.

**Passo 1: Verificação de Identidade**
O sistema verifica: "O usuário está logado?". Se não (`?`), redireciona para Login.

**Passo 2: Verificação de Hierarquia**
Se está logado (`@`), o sistema pergunta ao `authManager`: "Este usuário tem a Role 'admin'?".

```php
'roles' => ['admin'], // Apenas admins passam daqui
```

Se a resposta for não, o sistema lança uma `ForbiddenHttpException` (Erro 403) imediatamente, protegendo os dados sensíveis antes mesmo de tentar buscá-los no banco.

---

## 5. Visualização Detalhada (View)

### 5.1 O Ponto de Entrada: Controller (`actionView`)
Quando o usuário clica no "olho" (ícone de ver) na Grid, o `actionView` é acionado recebendo o ID.

### 5.2 A Lógica de Recuperação: `findModel`
Todo controller tem um método auxiliar chamado `findModel($id)`.

**O que faz:** Tenta encontrar o registro pelo ID (`findOne($id)`).
**A Segurança:** Se o registro não existir (ex: usuário mudou o ID na URL manualmente para 99999), este método lança uma `NotFoundHttpException` (Erro 404), impedindo que a aplicação quebre com erros de "Trying to get property of non-object".

### 5.3 A Interface: View (`view.php`)
Exibe os dados estáticos do registro. No caso de Master-Detail (ex: Pedido), é aqui que usamos a relação `hasMany` para iterar e mostrar uma sub-tabela HTML com os itens comprados (`$model->orderItems`), sem precisar fazer novas queries manuais.