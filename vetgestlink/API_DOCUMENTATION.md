# 📚 Documentação da API VetGestLink

<style>
table {
    background-color: #f0faf5;
    border-collapse: collapse;
    width: 100%;
}

thead {
    background-color: #4CB88A;
    color: white;
}

th {
    background-color: #4CB88A !important;
    color: white !important;
    padding: 12px;
    font-weight: bold;
}

tbody tr {
    background-color: #ffffff;
}

tbody tr:nth-child(even) {
    background-color: #e8f7f0;
}

tbody tr:hover {
    background-color: #94E2B6;
}

td {
    padding: 10px;
    border: 1px solid #d4eddf;
    color: #1a1a1a;
    font-weight: 500;
}

code {
    background-color: #d4eddf;
    padding: 2px 6px;
    border-radius: 3px;
    color: #2d5f47;
    font-weight: 600;
}
</style>

## 📋 Índice

1. [Autenticação](#autenticação)
2. [Cliente](#cliente)
3. [Health Check](#health-check)
4. [Imagens](#imagens)

---

## 🔐 Autenticação

Endpoints públicos para login, logout e recuperação de senha.

| HTTP Verb | Endpoint | Descrição | Parâmetros | Pedido | Resposta (JSON) |
|-----------|----------|-----------|------------|--------|-----------------|
| POST | `/auth/login` | Login de cliente | - | `{"username": "carlos", "password": "senha123"}` | `{"success": true, "message": "Login realizado com sucesso", "token": "eyJ0eXAiOiJKV1QiLCJhbGc...", "user": {"id": 1, "username": "carlos", "email": "carlos.mendes@email.com"}}` |
| POST | `/auth/logout` | Logout de cliente | `access-token` (query) | - | `{"success": true, "message": "Logout realizado com sucesso"}` |
| POST | `/auth/forgot-password` | Recuperar senha | - | `{"username": "carlos"}` | `{"success": true, "message": "Email de recuperação enviado"}` |
| GET | `/auth/validate-token` | Validar token | `access-token` (query) | - | `{"success": true, "message": "Token válido", "user": {"id": 1, "username": "carlos", "email": "carlos.mendes@email.com"}}` |

**📱 Utilização na App:**
- Login inicial do usuário
- Botão "Terminar Sessão" nas Definições
- Tela de recuperação de senha
- Validação automática ao abrir a aplicação

---

## 🐾 Cliente

Endpoints protegidos para operações do cliente (dono de animal). **Todos requerem `access-token` como query parameter.**

### Animais

| HTTP Verb | Endpoint | Descrição | Parâmetros | Pedido | Resposta (JSON) |
|-----------|----------|-----------|------------|--------|-----------------|
| GET | `/client/animais` | Lista todos os animais do cliente | `access-token` (query) | - | `[{"id": 1, "nome": "Rex", "especie": "Cão", "raca": "Labrador", "idade": 5, "peso": 30.5, "genero": "M", "datanascimento": "2019-01-15", "microchip": 1, "foto_url": "http://localhost/uploads/animais/1.jpg", "donos_id": 1, "ativo": true}]` |
| GET | `/client/animal/{id}` | Detalhes de um animal específico | `id` (path), `access-token` (query) | - | `{"id": 1, "nome": "Rex", "especie": "Cão", "raca": "Labrador", "idade": 5, "peso": 30.5, "genero": "M", "datanascimento": "2019-01-15", "microchip": 1, "foto_url": "http://localhost/uploads/animais/1.jpg", "notas": [{"id": 1, "texto": "Animal muito ativo", "data_criacao": 1699358400, "data_atualizacao": 1699358400, "autor": "Carlos Silva"}], "ativo": true, "dono": {"id": 1, "nomecompleto": "Carlos Silva", "telemovel": "912345678"}}` |

**📱 Utilização na App:**
- Tab "Perfil" → Seção "Meus Animais"
- Listagem de cards dos animais (Mimi, Bolt, etc.)
- Detalhes ao clicar em um animal

### Marcações (Consultas)

| HTTP Verb | Endpoint | Descrição | Parâmetros | Pedido | Resposta (JSON) |
|-----------|----------|-----------|------------|--------|-----------------|
| GET | `/client/marcacao` | Lista marcações com filtros | `access-token`, `status`, `animal_id`, `data_inicio`, `data_fim`, `search` (query) | - | `[{"id": 1, "data": "2025-11-10", "hora": "10:00", "tipo": "Consulta", "status": "Pendente", "duracao_minutos": 30, "observacoes": "Levar cartão de vacinação", "animal_id": 1, "animal_nome": "Rex", "animal_especie": "Cão", "veterinario_nome": "Dr. Veterinário", "veterinario_especialidade": "Clínica Geral", "clinica_nome": "Clínica VetGestLink", "clinica_morada": "Rua Principal, Lisboa"}]` |
| GET | `/client/marcacao/{id}` | Detalhes de uma marcação | `id` (path), `access-token` (query) | - | `{"id": 1, "data": "2025-11-10", "hora": "10:00", "tipo": "Consulta", "status": "Pendente", "duracao_minutos": 30, "observacoes": "Levar cartão de vacinação", "valor_estimado": 50.00, "animal": {"id": 1, "nome": "Rex", "especie": "Cão", "raca": "Labrador", "idade": 5}, "veterinario": {"id": 1, "nome": "Dr. Veterinário", "especialidade": "Clínica Geral", "crmv": "12345"}, "clinica": {"id": 1, "nome": "Clínica VetGestLink", "morada": "Rua Principal, 123", "localidade": "Lisboa", "telefone": "+351 21 123 4567", "email": "geral@vetgestlink.com"}}` |

**📱 Utilização na App:**
- Tab "Consultas" no menu inferior
- Histórico de consultas (Agendadas, Concluídas)
- Filtros: Todas, Agendadas, Concluídas
- Detalhes de cada consulta

### Faturas e Pagamentos

| HTTP Verb | Endpoint | Descrição | Parâmetros | Pedido | Resposta (JSON) |
|-----------|----------|-----------|------------|--------|-----------------|
| GET | `/client/fatura` | Lista faturas com filtros | `access-token`, `status`, `ano` (query) | - | `[{"id": 1, "numero": "FT2025/001", "data_emissao": "2025-11-01", "data_vencimento": "2025-12-01", "valor_total": 150.00, "valor_pago": 0.00, "valor_pendente": 150.00, "status": "Pendente", "servicos_resumo": "Consulta + Vacina", "animal_nome": null, "data_pagamento": null, "metodo_pagamento": null}]` |
| GET | `/client/fatura/{id}` | Detalhes de uma fatura | `id` (path), `access-token` (query) | - | `{"id": 1, "numero": "FT2025/001", "data_emissao": "2025-11-01", "data_vencimento": "2025-12-01", "valor_total": 150.00, "valor_pago": 0.00, "valor_pendente": 150.00, "status": "Pendente", "itens": [{"id": 1, "descricao": "Consulta", "quantidade": 1, "preco_unitario": 50.00, "subtotal": 50.00}], "pagamentos": []}` |
| POST | `/client/fatura/{id}/pagamento` | Processar pagamento | `id` (path), `access-token` (query) | `{"metodospagamentos_id": 1, "valor": 150.00, "referencia": "REF123"}` | `{"success": true, "message": "Pagamento processado com sucesso", "pagamento": {"id": 1, "valor": 150.00, "metodo": "MBWay", "referencia": "REF123", "data": "2025-11-07"}, "fatura": {"id": 1, "numero": "FT2025/001", "valor_total": 150.00, "valor_pago": 150.00, "valor_pendente": 0.00, "status": "Paga"}}` |
| GET | `/client/fatura/resumo` | Resumo financeiro | `access-token`, `ano` (query) | - | `{"total_pendente": 300.00, "total_pago_ano": 500.00, "total_faturas_pendentes": 2, "total_faturas_pagas_ano": 5, "proxima_fatura_vencimento": "2025-12-01", "ano": 2025}` |

**📱 Utilização na App:**
- Tab "Pagamentos" no menu inferior
- Card "Resumo Financeiro" (Pendente, Pago 2025)
- Histórico de Pagamentos com filtros (Todos, Pendente, Pago)
- Detalhes de cada fatura
- Modal "Realizar Pagamento" com métodos de pagamento

### Métodos de Pagamento

| HTTP Verb | Endpoint | Descrição | Parâmetros | Pedido | Resposta (JSON) |
|-----------|----------|-----------|------------|--------|-----------------|
| GET | `/client/metodos-pagamento` | Lista métodos disponíveis | `access-token` (query) | - | `[{"id": 1, "nome": "MBWay", "descricao": "Pagamento via MBWay", "icone": "mbway", "ativo": true, "taxa": 0.00}, {"id": 2, "nome": "Multibanco", "descricao": "Pagamento via Multibanco", "icone": "multibanco", "ativo": true, "taxa": 0.00}, {"id": 3, "nome": "Cartão de Crédito/Débito", "descricao": "Visa, Mastercard, American Express", "icone": "card", "ativo": true, "taxa": 0.00}]` |

**📱 Utilização na App:**
- Modal "Realizar Pagamento"
- Seleção de método: MBWay, Multibanco, Cartão de Crédito/Débito

### Perfil do Cliente

| HTTP Verb | Endpoint | Descrição | Parâmetros | Pedido | Resposta (JSON) |
|-----------|----------|-----------|------------|--------|-----------------|
| GET | `/client/perfil` | Obter perfil completo | `access-token` (query) | - | `{"id": 1, "nomecompleto": "Carlos Mendes", "email": "carlos.mendes@email.com", "telemovel": "+351 912 345 678", "nif": "123456789", "datanascimento": "1990-01-15", "genero": null, "foto_url": null, "ativo": true, "animais": [{"id": 1, "nome": "Mimi", "especie": "Gato", "raca": "Persa", "idade": 2, "peso": 4.0, "genero": "F", "foto_url": "http://localhost/uploads/animais/1.jpg"}], "morada": {"id": 1, "rua": "Rua das Acácias, 45", "nporta": "45", "cdpostal": "1000-100", "localidade": "Lisboa", "cidade": "Lisboa", "principal": true}, "estatisticas": {"total_animais": 2, "total_consultas": 10, "proxima_consulta": "2025-11-15", "faturas_pendentes": 1, "valor_pendente": 150.00}}` |
| PUT | `/client/perfil` | Atualizar perfil | `access-token` (query) | `{"nomecompleto": "Carlos Silva", "telemovel": "912345678"}` | `{"success": true, "message": "Perfil atualizado com sucesso", "user": {"id": 1, "nomecompleto": "Carlos Silva", "email": "carlos@example.com", "telemovel": "912345678", "updated_at": "2025-11-07T15:30:00Z"}}` |

**📱 Utilização na App:**
- Tab "Perfil" no menu inferior
- Seção "Meus Animais" (quantidade)
- Seção "Dados do Proprietário"
- Botão "Editar" para atualizar informações

### Moradas

| HTTP Verb | Endpoint | Descrição | Parâmetros | Pedido | Resposta (JSON) |
|-----------|----------|-----------|------------|--------|-----------------|
| GET | `/client/moradas` | Lista moradas do cliente | `access-token` (query) | - | `[{"id": 1, "rua": "Rua das Acácias", "nporta": "45", "andar": null, "cdpostal": "1000-100", "localidade": "Lisboa", "cidade": "Lisboa", "tipo": "Residencial", "principal": true, "donos_id": 1}]` |
| PUT | `/client/morada` | Atualizar morada principal | `access-token` (query) | `{"rua": "Rua Nova", "nporta": "456", "andar": "3", "cdpostal": "1000-002", "localidade": "Lisboa", "cidade": "Lisboa"}` | `{"success": true, "message": "Morada atualizada com sucesso", "morada": {"id": 1, "rua": "Rua Nova", "nporta": "456", "cdpostal": "1000-002", "localidade": "Lisboa", "cidade": "Lisboa", "updated_at": "2025-11-07T15:30:00Z"}}` |

**📱 Utilização na App:**
- Tab "Perfil" → Seção "Morada"
- Exibição: Rua, Código Postal, Cidade, País
- Edição via botão "Editar"

### Notas dos Animais

| HTTP Verb | Endpoint | Descrição | Parâmetros | Pedido | Resposta (JSON) |
|-----------|----------|-----------|------------|--------|-----------------|
| GET | `/client/animal/{animal_id}/notas` | Lista notas de um animal | `animal_id` (path), `access-token` (query) | - | `[{"id": 1, "nota": "O Rex tem estado menos ativo nos últimos 2 dias. Comeu menos ração no jantar de ontem.", "animais_id": 1, "donos_id": 1, "created_at": "2025-11-07T15:30:00Z", "updated_at": "2025-11-07T15:30:00Z"}]` |
| POST | `/client/animal/{animal_id}/notas` | Criar nova nota | `animal_id` (path), `access-token` (query) | `{"nota": "Vacinação antirrábica aplicada hoje. Rex ficou um pouco sonolento durante a tarde mas está normal agora."}` | `{"success": true, "message": "Nota criada com sucesso", "nota": {"id": 2, "nota": "Vacinação antirrábica aplicada hoje. Rex ficou um pouco sonolento durante a tarde mas está normal agora.", "animais_id": 1, "donos_id": 1, "created_at": "2025-11-07T15:30:00Z"}}` |
| PUT | `/client/notas/{id}` | Atualizar nota | `id` (path), `access-token` (query) | `{"nota": "Texto atualizado da nota - Animal já está completamente recuperado."}` | `{"success": true, "message": "Nota atualizada com sucesso", "nota": {"id": 1, "nota": "Texto atualizado da nota - Animal já está completamente recuperado.", "animais_id": 1, "donos_id": 1, "updated_at": "2025-11-07T16:00:00Z"}}` |
| DELETE | `/client/notas/{id}` | Deletar nota | `id` (path), `access-token` (query) | - | `{"success": true, "message": "Nota deletada com sucesso"}` |

**📱 Utilização na App:**
- Tab "Notas" no menu inferior
- Seleção de animal (dropdown "Rex - Labrador Retriever")
- Botão "+ Nova Anotação para Rex"
- Lista de notas com ícones de editar (✏️) e deletar (🗑️)
- Exemplos: "Comportamento Estranho", "Pós-Vacinação"

---

## 🏥 Health Check

Endpoint público para verificar status do servidor.

| HTTP Verb | Endpoint | Descrição | Parâmetros | Pedido | Resposta (JSON) |
|-----------|----------|-----------|------------|--------|-----------------|
| GET | `/health` | Verifica status do servidor | - | - | `{"status": "ok", "message": "Servidor funcionando corretamente", "timestamp": "2025-11-07T15:30:00Z", "version": "1.0.0"}` |

**📱 Utilização na App:**
- Tab "Definições" → Configuração do Servidor
- Botão "Testar Conexão"
- Validação da URL do servidor configurada

---

## 🖼️ Imagens

Endpoints para acesso a imagens de animais e usuários.

| HTTP Verb | Endpoint | Descrição | Parâmetros | Pedido | Resposta (JSON) |
|-----------|----------|-----------|------------|--------|-----------------|
| GET | `/image/animal/{id}` | Informações sobre imagem de animal | `id` (path) | - | `{"id": 1, "nome": "Rex", "imageUrl": "/uploads/animais/1.jpg", "imageAbsoluteUrl": "http://localhost/uploads/animais/1.jpg", "hasImage": true}` |
| GET | `/image/user/{id}` | Informações sobre imagem de usuário | `id` (path) | - | `{"id": 1, "nomecompleto": "Carlos Silva", "imageUrl": "/uploads/users/1.jpg", "imageAbsoluteUrl": "http://localhost/uploads/users/1.jpg", "hasImage": true}` |
| GET | `/image/serve` | Serve arquivo de imagem | `type` (animal/user), `id` (query) | - | *Arquivo de imagem binário (JPG/PNG)* |

**📱 Utilização na App:**
- Cards dos animais (foto circular dos pets)
- Avatar do usuário no perfil
- Carregamento dinâmico de imagens

---

## 📝 Notas Importantes

### Autenticação
- **Endpoints Públicos**: `/auth/*`, `/health`, `/image/*`
- **Endpoints Protegidos**: `/client/*` - Requerem `access-token` como query parameter

### Formato de Datas
- **ISO 8601**: `YYYY-MM-DDTHH:mm:ssZ` (ex: `2025-11-07T15:30:00Z`)
- **Data Simples**: `YYYY-MM-DD` (ex: `2025-11-07`)

### Códigos de Status HTTP
- `200` - Sucesso
- `400` - Requisição inválida
- `401` - Não autorizado (token inválido ou expirado)
- `403` - Proibido (usuário sem permissão)
- `404` - Não encontrado
- `500` - Erro interno do servidor

### CORS
Todos os endpoints têm CORS habilitado para aceitar requisições de qualquer origem (`Origin: *`).

### Content-Type
- **Request**: `application/json` ou `application/x-www-form-urlencoded`
- **Response**: `application/json`

---

## 🔗 URL Base

### Produção
```
https://api.vetgestlink.com
```

### Desenvolvimento
```
http://localhost/2_ano_1_semestre/Projeto/vetgestlink/backend/web/api
```

**Exemplo completo:**
```
POST http://localhost/2_ano_1_semestre/Projeto/vetgestlink/backend/web/api/auth/login
```

---

## 📱 Fluxo de Navegação da App

### 1. Login e Autenticação
1. `POST /auth/login` → Obter token
2. `GET /auth/validate-token` → Validar na próxima abertura
3. `POST /auth/logout` → Encerrar sessão

### 2. Visualizar Perfil
1. `GET /client/perfil` → Dados completos do cliente
2. `GET /client/animais` → Lista de pets
3. `GET /client/moradas` → Morada principal

### 3. Consultas
1. `GET /client/marcacao?status=pendente` → Próximas consultas
2. `GET /client/marcacao?status=concluida` → Histórico
3. `GET /client/marcacao/{id}` → Detalhes de uma consulta

### 4. Pagamentos
1. `GET /client/fatura/resumo` → Resumo financeiro
2. `GET /client/fatura?status=Pendente` → Faturas pendentes
3. `GET /client/fatura/{id}` → Detalhes da fatura
4. `GET /client/metodos-pagamento` → Métodos disponíveis
5. `POST /client/fatura/{id}/pagamento` → Processar pagamento

### 5. Notas
1. `GET /client/animal/{animal_id}/notas` → Listar notas
2. `POST /client/animal/{animal_id}/notas` → Criar nota
3. `PUT /client/notas/{id}` → Editar nota
4. `DELETE /client/notas/{id}` → Deletar nota

---

## 📊 Resumo de Endpoints

| Categoria | Total de Endpoints |
|-----------|-------------------|
| Autenticação | 4 |
| Cliente - Animais | 2 |
| Cliente - Marcações | 2 |
| Cliente - Faturas | 4 |
| Cliente - Métodos Pagamento | 1 |
| Cliente - Perfil | 2 |
| Cliente - Moradas | 2 |
| Cliente - Notas | 4 |
| Health Check | 1 |
| Imagens | 3 |
| **TOTAL** | **25** |

---

## 🚀 Exemplos de Uso

### Login
```bash
curl -X POST "http://localhost/2_ano_1_semestre/Projeto/vetgestlink/backend/web/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"username": "carlos", "password": "senha123"}'
```

### Listar Animais
```bash
curl -X GET "http://localhost/2_ano_1_semestre/Projeto/vetgestlink/backend/web/api/client/animais?access-token=SEU_TOKEN_AQUI"
```

### Processar Pagamento
```bash
curl -X POST "http://localhost/2_ano_1_semestre/Projeto/vetgestlink/backend/web/api/client/fatura/1/pagamento?access-token=SEU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{"metodospagamentos_id": 1, "valor": 85.00, "referencia": "REF2025045"}'
```

### Criar Nota
```bash
curl -X POST "http://localhost/2_ano_1_semestre/Projeto/vetgestlink/backend/web/api/client/animal/1/notas?access-token=SEU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{"nota": "Animal apresentou comportamento estranho hoje"}'
```

---

**Documentação atualizada em**: 2025-11-07  
**Versão da API**: 1.0.0  
**Projeto**: VetGestLink - Aplicação Mobile  
**Total de Endpoints Ativos**: 25

