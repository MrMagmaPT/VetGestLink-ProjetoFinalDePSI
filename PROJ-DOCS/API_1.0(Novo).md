# Guia de Testes da API VetGestLink

Este documento contém todos os endpoints da API e exemplos de como testá-los.

## Configuração Inicial

**Base URL:** `http://localhost/vetgestlink/backend/web`

**Token de Teste:** `SEU_TOKEN`

- Usuário: `cliente`
- UserProfile ID: `2` (Maria Silva)

---

## 1. Autenticação (Públicos - Sem Token)

### 1.1 Login

```bash
# POST - Fazer login
curl -X POST "http://localhost/vetgestlink/backend/web/api/auth/login" \
  -H "Content-Type: application/json" \
  -d "{\"username\":\"cliente\",\"password\":\"12345678\"}"
```

**Resposta Esperada:**

```json
{
  "success": true,
  "message": "Login realizado com sucesso",
  "access_token": "SEU_TOKEN",
  "user": {
    "id": 3,
    "username": "cliente",
    "role": "cliente"
  },
  "profile": {
    "id": 2,
    "nomecompleto": "Maria Silva",
    "telemovel": "912345678",
    "email": "maria@example.com"
  }
}
```

### 1.2 Logout

```bash
# POST - Fazer logout
curl -X POST "http://localhost/vetgestlink/backend/web/api/auth/logout?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
{
  "success": true,
  "message": "Logout realizado com sucesso"
}
```

### 1.3 Perfil do Usuário (DEBUGING OU AJUDA NA SEGUNDA ENTREGA)

```bash
# GET - Obter informações do usuário autenticado
curl -X GET "http://localhost/vetgestlink/backend/web/api/auth/profile?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
{
  "success": true,
  "user": {
    "id": 3,
    "username": "cliente",
    "role": "cliente",
    "profile": {
      "id": 2,
      "nomecompleto": "Maria Silva",
      "telemovel": "912345678",
      "email": "maria@example.com"
    }
  }
}
```

### 1.4 Recuperação de Senha

```bash
# POST - Solicitar recuperação de senha
curl -X POST "http://localhost/vetgestlink/backend/web/api/auth/forgot" \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"maria@example.com\"}"
```

**Body Obrigatório:**

```json
{
  "email": "maria@example.com"
}
```

**Resposta Esperada:**

```json
{
  "success": true,
  "message": "Se o email existir, um link de recuperação será enviado"
}
```

**Como funciona:**

1. O usuário fornece seu email
2. Sistema valida o email e verifica se existe um usuário ativo com esse email
3. Gera um token de recuperação único
4. Envia email com link contendo o token para redefinir a senha
5. O link direciona para uma página onde o usuário pode criar uma nova senha

---

## 2. Perfil do Usuário (Protegidos - Requer Token)

### 2.1 Ver Perfil Completo

```bash
# GET - Obter dados completos do perfil
curl -X GET "http://localhost/vetgestlink/backend/web/api/profile?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
{
  "success": true,
  "user": {
    "id": 3,
    "username": "cliente",
    "email": "maria@example.com"
  },
  "profile": {
    "id": 2,
    "nomecompleto": "Maria Silva",
    "telemovel": "912345678",
    "nif": "123456789"
  },
  "morada": {
    "id": 1,
    "rua": "Rua das Flores",
    "nporta": "123",
    "cdpostal": "1000-100",
    "localidade": "Lisboa"
  }
}
```

### 2.2 Atualizar Perfil

```bash
# PUT - Atualizar nome, email, telefone e morada
curl -X PUT "http://localhost/vetgestlink/backend/web/api/profile/update?access-token=SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"nomecompleto\":\"Carlos Mendes\",\"email\":\"carlos.mendes@email.com\",\"telemovel\":\"+351 912 345 678\",\"morada\":{\"rua\":\"Rua das Acácias\",\"nporta\":\"45\",\"cdpostal\":\"1000-100\",\"localidade\":\"Lisboa\"}}"
```

**Body (todos os campos são opcionais):**

```json
{
  "nomecompleto": "Carlos Mendes",
  "email": "carlos.mendes@email.com",
  "telemovel": "+351 912 345 678",
  "morada": {
    "rua": "Rua das Acácias",
    "nporta": "45",
    "cdpostal": "1000-100",
    "localidade": "Lisboa"
  }
}
```

**Notas:**

- Todos os campos são **opcionais** - envie apenas o que deseja atualizar
- O telemóvel aceita formatação (espaços, hífens, parênteses) - será limpo automaticamente
- O telemóvel deve ter no máximo 9 dígitos após a limpeza
- O campo `morada` também é opcional e seus subcampos são independentes

**Exemplos de telemóveis válidos:**

- `"912345678"` → `912345678`
- `"912 345 678"` → `912345678`
- `"+351 912 345 678"` → `351912345678` ❌ (mais de 9 dígitos)
- `"912-345-678"` → `912345678`

**Resposta Esperada:**

```json
{
  "success": true,
  "message": "Perfil atualizado com sucesso",
  "user": {
    "id": 3,
    "username": "cliente",
    "email": "carlos.mendes@email.com"
  },
  "profile": {
    "id": 2,
    "nomecompleto": "Carlos Mendes",
    "telemovel": "912345678",
    "nif": "123456789"
  },
  "morada": {
    "id": 1,
    "rua": "Rua das Acácias",
    "nporta": "45",
    "cdpostal": "1000-100",
    "localidade": "Lisboa"
  }
}
```

### 2.3 Alterar Senha

```bash
# PUT - Alterar senha do usuário
curl -X PUT "http://localhost/vetgestlink/backend/web/api/profile/password?access-token=SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"current_password\":\"12345678\",\"new_password\":\"novaSenha123\"}"
```

**Body Obrigatório:**

```json
{
  "current_password": "12345678",
  "new_password": "novaSenha123"
}
```

**Validações:**
- Senha atual deve estar correta
- Nova senha deve ter no mínimo 6 caracteres

**Resposta Esperada:**

```json
{
  "success": true,
  "message": "Senha alterada com sucesso",
  "token": "NovoTokenGerado123"
}
```

**⚠️ Importante:** Após alterar a senha, um novo token é gerado e o antigo é invalidado. Use o novo token nas próximas requisições.

---

## 3. Animais (Protegidos - Requer Token)

### 3.1 Listar Todos os Animais

```bash
# GET - Listar todos os animais do cliente
curl -X GET "http://localhost/vetgestlink/backend/web/api/animal/all?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
[
  {
    "id": 1,
    "nome": "Bobby",
    "especie": "Cão",
    "especie_id": 1,
    "raca": "Golden Retriever",
    "raca_id": 1,
    "idade": 3,
    "peso": 25.5,
    "sexo": "M",
    "datanascimento": "2021-05-15",
    "microchip": "123456789",
    "foto_url": "http://localhost/vetgestlink/frontend/web/uploads/bobby.jpg",
    "userprofiles_id": 2,
    "ativo": true
  }
]
```

### 3.2 Ver Detalhes de um Animal

```bash
# GET - Ver detalhes de um animal específico
curl -X GET "http://localhost/vetgestlink/backend/web/api/animal/view/1?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
{
  "id": 1,
  "nome": "Bobby",
  "especie": "Cão",
  "especie_id": 1,
  "raca": "Golden Retriever",
  "raca_id": 1,
  "idade": 3,
  "peso": 25.5,
  "sexo": "M",
  "datanascimento": "2021-05-15",
  "microchip": "123456789",
  "foto_url": "http://localhost/vetgestlink/frontend/web/uploads/bobby.jpg",
  "notas": [
    {
      "id": 1,
      "texto": "Animal apresentou sintomas leves de alergia.",
      "created_at": "2024-01-15 10:30:00",
      "updated_at": "2024-01-15 10:30:00",
      "autor": "Maria Silva"
    }
  ],
  "ativo": true,
  "dono": {
    "id": 2,
    "nomecompleto": "Maria Silva",
    "telemovel": "912345678"
  }
}
```

### 3.3 Ver Notas de um Animal

```bash
# GET - Listar notas de um animal específico
curl -X GET "http://localhost/vetgestlink/backend/web/api/animal/1/notes?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
[
  {
    "id": 1,
    "nota": "Animal apresentou sintomas leves de alergia.",
    "animais_id": 1,
    "userprofiles_id": 2,
    "created_at": "2024-01-15 10:30:00",
    "updated_at": "2024-01-15 10:30:00",
    "autor": "Maria Silva"
  }
]
```

---

## 4. Marcações (Protegidos - Requer Token)

### 4.1 Listar Todas as Marcações

```bash
# GET - Listar todas as marcações do cliente
curl -X GET "http://localhost/vetgestlink/backend/web/api/marcacao/all?access-token=SEU_TOKEN"
```

**Parâmetros Opcionais:**

- `status` - Filtrar por estado (ex: `Pendente`, `Confirmada`, `Concluída`, `Cancelada`)
- `animal_id` - Filtrar por ID do animal
- `data_inicio` - Filtrar por data inicial (formato: `YYYY-MM-DD`)
- `data_fim` - Filtrar por data final (formato: `YYYY-MM-DD`)
- `search` - Pesquisar no diagnóstico

**Exemplo com filtros:**

```bash
curl -X GET "http://localhost/vetgestlink/backend/web/api/marcacao/all?access-token=SEU_TOKEN&status=Confirmada&animal_id=1"
```

**Resposta Esperada:**

```json
[
  {
    "id": 1,
    "data": "2024-12-15",
    "horainicio": "10:00:00",
    "horafim": "10:30:00",
    "estado": "Confirmada",
    "duracao_minutos": 30,
    "diagnostico": "Consulta de rotina",
    "servico_nome": "Consulta Geral",
    "animal_nome": "Bobby",
    "animal_especie": "Cão",
    "created_at": "2024-12-01 09:00:00",
    "updated_at": "2024-12-01 09:00:00"
  }
]
```

### 4.2 Ver Detalhes de uma Marcação

```bash
# GET - Ver detalhes de uma marcação específica
curl -X GET "http://localhost/vetgestlink/backend/web/api/marcacao/view/1?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
{
  "id": 1,
  "data": "2024-12-15",
  "horainicio": "10:00:00",
  "horafim": "10:30:00",
  "estado": "Confirmada",
  "duracao_minutos": 30,
  "diagnostico": "Consulta de rotina",
  "servicos_id": 1,
  "servico_nome": "Consulta Geral",
  "animais_id": 1,
  "userprofiles_id": 2,
  "created_at": "2024-12-01 09:00:00",
  "updated_at": "2024-12-01 09:00:00",
  "animal": {
    "id": 1,
    "nome": "Bobby",
    "especie": "Cão",
    "raca": "Golden Retriever",
    "idade": 3,
    "peso": 25.5,
    "sexo": "M"
  }
}
```

---

## 5. Faturas (Protegidos - Requer Token)

### 5.1 Listar Todas as Faturas

```bash
# GET - Listar todas as faturas do cliente
curl -X GET "http://localhost/vetgestlink/backend/web/api/fatura/all?access-token=SEU_TOKEN"
```

**Parâmetros Opcionais:**

- `estado` - Filtrar por estado (0 = Não Paga, 1 = Paga)
- `ano` - Filtrar por ano (formato: `YYYY`)

**Exemplo com filtros:**

```bash
curl -X GET "http://localhost/vetgestlink/backend/web/api/fatura/all?access-token=SEU_TOKEN&estado=0&ano=2024"
```

**Resposta Esperada:**

```json
[
  {
    "id": 1,
    "total": 150.5,
    "estado": 1,
    "metodospagamentos_id": 1,
    "metodo_pagamento": "Multibanco",
    "userprofiles_id": 2,
    "created_at": "2024-01-20 14:30:00",
    "numero_itens": 3
  }
]
```

### 5.2 Ver Detalhes de uma Fatura

```bash
# GET - Ver detalhes de uma fatura específica
curl -X GET "http://localhost/vetgestlink/backend/web/api/fatura/view/1?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
{
  "id": 1,
  "total": 150.5,
  "estado": 1,
  "metodospagamentos_id": 1,
  "metodo_pagamento": "Multibanco",
  "userprofiles_id": 2,
  "created_at": "2024-01-20 14:30:00",
  "cliente": {
    "id": 2,
    "nomecompleto": "Maria Silva",
    "nif": "123456789"
  },
  "linhas": [
    {
      "id": 1,
      "descricao": "Consulta Geral",
      "quantidade": 1,
      "preco": 50.0,
      "subtotal": 50.0
    },
    {
      "id": 2,
      "descricao": "Vacina Raiva",
      "quantidade": 2,
      "preco": 25.25,
      "subtotal": 50.5
    }
  ]
}
```

### 5.3 Listar Métodos de Pagamento

```bash
# GET - Listar métodos de pagamento disponíveis
curl -X GET "http://localhost/vetgestlink/backend/web/api/fatura/paymentmethods?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
[
  {
    "id": 1,
    "nome": "Multibanco",
    "vigor": 1
  },
  {
    "id": 2,
    "nome": "Dinheiro",
    "vigor": 1
  },
  {
    "id": 3,
    "nome": "MB WAY",
    "vigor": 1
  }
]
```

### 5.4 Pagar uma Fatura

```bash
# PUT - Pagar uma fatura (alterar estado para pago)
curl -X PUT "http://localhost/vetgestlink/backend/web/api/fatura/pay/1?access-token=SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"metodospagamentos_id\":1}"
```

**Body (opcional):**

```json
{
  "metodospagamentos_id": 1
}
```

**Resposta Esperada:**

```json
{
  "success": true,
  "message": "Fatura paga com sucesso",
  "fatura": {
    "id": 1,
    "total": 150.5,
    "estado": 1,
    "metodospagamentos_id": 1,
    "created_at": "2024-01-20 14:30:00"
  }
}
```

**Possíveis Erros:**

- `404` - Fatura não encontrada
- `400` - Fatura já está paga
- `400` - Método de pagamento inválido

---

## 6. Notas (Protegidos - Requer Token)

### 6.1 Listar Todas as Notas

```bash
# GET - Listar todas as notas do cliente
curl -X GET "http://localhost/vetgestlink/backend/web/api/nota/all?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
[
  {
    "id": 1,
    "nota": "Animal apresentou sintomas leves de alergia.",
    "animais_id": 1,
    "animal_nome": "Bobby",
    "userprofiles_id": 2,
    "autor": "Maria Silva",
    "created_at": "2024-01-15 10:30:00",
    "updated_at": "2024-01-15 10:30:00"
  },
  {
    "id": 2,
    "nota": "Recomendado reforço de vacina em 6 meses.",
    "animais_id": 2,
    "animal_nome": "Luna",
    "userprofiles_id": 2,
    "autor": "Maria Silva",
    "created_at": "2024-02-10 15:45:00",
    "updated_at": "2024-02-10 15:45:00"
  }
]
```

### 6.2 Ver Detalhes de uma Nota

```bash
# GET - Ver detalhes de uma nota específica
curl -X GET "http://localhost/vetgestlink/backend/web/api/nota/view/1?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
{
  "id": 1,
  "nota": "Animal apresentou sintomas leves de alergia.",
  "animais_id": 1,
  "animal_nome": "Bobby",
  "userprofiles_id": 2,
  "autor": "Maria Silva",
  "created_at": "2024-01-15 10:30:00",
  "updated_at": "2024-01-15 10:30:00"
}
```

### 6.3 Criar Nova Nota

```bash
# POST - Criar uma nova nota
curl -X POST "http://localhost/vetgestlink/backend/web/api/nota/create?access-token=SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"animais_id\":1,\"nota\":\"Nova nota de teste\"}"
```

**Body Obrigatório:**

```json
{
  "animais_id": 1,
  "nota": "Texto da nota aqui"
}
```

**Resposta Esperada:**

```json
{
  "success": true,
  "message": "Nota criada com sucesso",
  "nota": {
    "id": 3,
    "nota": "Nova nota de teste",
    "animais_id": 1,
    "userprofiles_id": 2,
    "created_at": "2024-12-10 16:20:00"
  }
}
```

### 6.4 Atualizar Nota

```bash
# PUT - Atualizar uma nota existente
curl -X PUT "http://localhost/vetgestlink/backend/web/api/nota/update/1?access-token=SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"nota\":\"Nota atualizada\"}"
```

**Body Obrigatório:**

```json
{
  "nota": "Texto atualizado da nota"
}
```

**Resposta Esperada:**

```json
{
  "success": true,
  "message": "Nota atualizada com sucesso",
  "nota": {
    "id": 1,
    "nota": "Nota atualizada",
    "animais_id": 1,
    "userprofiles_id": 2,
    "updated_at": "2024-12-10 16:25:00"
  }
}
```

### 6.5 Deletar Nota

```bash
# DELETE - Deletar uma nota (soft delete)
curl -X DELETE "http://localhost/vetgestlink/backend/web/api/nota/delete/1?access-token=SEU_TOKEN"
```

**Resposta Esperada:**

```json
{
  "success": true,
  "message": "Nota deletada com sucesso"
}
```

---

## 7. Health Check (Público - Sem Token)

### 7.1 Verificar Status do Servidor

```bash
# GET - Verificar se o servidor está funcionando
curl -X GET "http://localhost/vetgestlink/backend/web/api/health"
```

**Resposta Esperada:**

```json
{
  "status": "ok",
  "message": "Servidor funcionando corretamente",
  "timestamp": "2024-12-10T16:30:00Z",
  "version": "1.0.0"
}
```

---

## 8. Códigos de Status HTTP

| Código | Significado           | Descrição                   |
| ------ | --------------------- | --------------------------- |
| 200    | OK                    | Requisição bem-sucedida     |
| 201    | Created               | Recurso criado com sucesso  |
| 400    | Bad Request           | Dados inválidos ou ausentes |
| 401    | Unauthorized          | Token inválido ou ausente   |
| 403    | Forbidden             | Sem permissão para acessar  |
| 404    | Not Found             | Recurso não encontrado      |
| 500    | Internal Server Error | Erro interno do servidor    |

---

## 9. Erros Comuns

### Token Inválido ou Expirado

```json
{
  "name": "Unauthorized",
  "message": "Your request was made with invalid credentials.",
  "code": 0,
  "status": 401
}
```

### Dados Obrigatórios Ausentes

```json
{
  "name": "Bad Request",
  "message": "Animal e texto da nota são obrigatórios",
  "code": 0,
  "status": 400
}
```

### Recurso Não Encontrado

```json
{
  "name": "Not Found",
  "message": "Nota não encontrada",
  "code": 0,
  "status": 404
}
```

---

## 10. Testando com Postman

1. **Importar Collection:**

   - Crie uma nova collection no Postman
   - Configure a variável `{{baseUrl}}` = `http://localhost/vetgestlink/backend/web`
   - Configure a variável `{{token}}` = seu token de acesso

2. **Configurar Autenticação:**

   - Nas requisições protegidas, adicione o parâmetro de query: `access-token={{token}}`

3. **Testar Sequência Completa:**
   - Login → Obter token
   - Listar animais
   - Ver detalhes de um animal
   - Criar nota
   - Atualizar nota
   - Deletar nota
   - Logout

---

## 11. Testando com PowerShell

```powershell
# Login
$response = Invoke-RestMethod -Uri "http://localhost/vetgestlink/backend/web/api/auth/login" `
  -Method Post `
  -ContentType "application/json" `
  -Body '{"username":"cliente","password":"12345678"}'

$token = $response.access_token
Write-Host "Token: $token"

# Listar animais
$animais = Invoke-RestMethod -Uri "http://localhost/vetgestlink/backend/web/api/animal/all?access-token=$token" `
  -Method Get

$animais | ConvertTo-Json -Depth 5

# Criar nota
$novaNota = Invoke-RestMethod -Uri "http://localhost/vetgestlink/backend/web/api/nota/create?access-token=$token" `
  -Method Post `
  -ContentType "application/json" `
  -Body '{"animais_id":1,"nota":"Teste via PowerShell"}'

$novaNota | ConvertTo-Json -Depth 5
```

---

## 12. Notas Importantes

- ✅ Todos os endpoints protegidos requerem `access-token` como parâmetro de query
- ✅ CORS está habilitado para todas as origens (`*`)
- ✅ Todas as respostas são em formato JSON
- ✅ Os endpoints de Auth e Health não requerem autenticação
- ✅ Soft delete é usado (campo `eliminado` = 1, não apaga do banco)
- ✅ Usuários só podem ver seus próprios dados (filtrado por `userprofiles_id`)

