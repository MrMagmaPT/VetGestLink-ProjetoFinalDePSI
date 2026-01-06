# VetGestLink - Documentação da API

## Informações de Autenticação

### Credenciais de Teste

- **Username**: `cliente1`
- **Password**: `cliente123`
- **Access Token**: `L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`

### Autenticação

A maioria dos endpoints requer autenticação via token. Inclua o token no query parameter:

```
?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP
```

---

## 1. AUTENTICAÇÃO (Auth Controller)

### 1.1 Login

- **Endpoint**: `POST /api/auth/login`
- **Autenticação**: ❌ Não requer
- **Descrição**: Realiza login e retorna o token de autenticação
- **Body**:

```json
{
  "username": "cliente1",
  "password": "cliente123"
}
```

- **Resposta**:

```json
{
  "success": true,
  "message": "Login bem-sucedido",
  "token": "L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP",
  "user": {
    "id": 1,
    "username": "cliente1",
    "email": "cliente1@example.com"
  }
}
```

### 1.2 Logout

- **Endpoint**: `POST /api/auth/logout?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Realiza logout do cliente
- **Resposta**:

```json
{
  "success": true,
  "message": "Logout realizado com sucesso"
}
```

### 1.3 Recuperação de Senha

- **Endpoint**: `POST /api/auth/forgot`
- **Autenticação**: ❌ Não requer
- **Descrição**: Solicita recuperação de senha via email
- **Body**:

```json
{
  "email": "cliente1@example.com"
}
```

- **Resposta**:

```json
{
  "success": true,
  "message": "Verifique seu email para instruções de recuperação de senha."
}
```

---

## 2. PERFIL (Profile Controller)

### 2.1 Obter Perfil

- **Endpoint**: `GET /api/profile?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Retorna dados completos do perfil do usuário autenticado
- **Resposta**:

```json
{
  "success": true,
  "user": {
    "id": 1,
    "username": "cliente1",
    "email": "cliente1@example.com"
  },
  "profile": {
    "id": 1,
    "nomecompleto": "Cliente Um",
    "telemovel": "912345678",
    "nif": "123456789"
  },
  "morada": {
    "id": 1,
    "rua": "Rua Exemplo",
    "nporta": "123",
    "cdpostal": "1000-100",
    "localidade": "Lisboa"
  }
}
```

### 2.2 Atualizar Perfil

- **Endpoint**: `PUT /api/profile/update?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Atualiza dados do perfil (nome, email, telefone e morada)
- **Body**:

```json
{
  "nomecompleto": "Cliente Um Atualizado",
  "email": "novoemail@example.com",
  "telemovel": "912345678",
  "morada": {
    "rua": "Nova Rua",
    "nporta": "456",
    "cdpostal": "2000-200",
    "localidade": "Porto"
  }
}
```

- **Resposta**:

```json
{
  "success": true,
  "message": "Perfil atualizado com sucesso",
  "user": {...},
  "profile": {...},
  "morada": {...}
}
```

### 2.3 Alterar Senha

- **Endpoint**: `PUT /api/profile/password?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Altera a senha do usuário
- **Body**:

```json
{
  "current_password": "cliente123",
  "new_password": "novasenha123"
}
```

- **Resposta**:

```json
{
  "success": true,
  "message": "Senha alterada com sucesso",
  "token": "novo_token_gerado"
}
```

---

## 3. ANIMAIS (Animal Controller)

### 3.1 Listar Todos os Animais

- **Endpoint**: `GET /api/animal/all?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista todos os animais do cliente autenticado
- **Resposta**:

```json
[
  {
    "id": 1,
    "nome": "Rex",
    "especie": "Cão",
    "especie_id": 1,
    "raca": "Labrador",
    "raca_id": 1,
    "idade": 5,
    "peso": 30.5,
    "sexo": "M",
    "datanascimento": "2019-01-15",
    "microchip": "123456789012345",
    "foto_url": "http://exemplo.com/foto.jpg",
    "userprofiles_id": 1,
    "created_at": "2024-01-01 10:00:00",
    "updated_at": "2024-01-01 10:00:00",
    "ativo": true
  }
]
```

### 3.2 Ver Detalhes de um Animal

- **Endpoint**: `GET /api/animal/view/{id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Retorna detalhes completos de um animal específico
- **Exemplo**: `GET /api/animal/view/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
{
  "id": 1,
  "nome": "Rex",
  "especie": "Cão",
  "especie_id": 1,
  "raca": "Labrador",
  "raca_id": 1,
  "idade": 5,
  "peso": 30.5,
  "sexo": "M",
  "datanascimento": "2019-01-15",
  "microchip": "123456789012345",
  "foto_url": "http://exemplo.com/foto.jpg",
  "notas": [
    {
      "id": 1,
      "texto": "Primeira consulta",
      "created_at": "2024-01-01 10:00:00",
      "autor": "Veterinário Silva"
    }
  ],
  "created_at": "2024-01-01 10:00:00",
  "updated_at": "2024-01-01 10:00:00",
  "ativo": true,
  "dono": {
    "id": 1,
    "nomecompleto": "Cliente Um",
    "telemovel": "912345678"
  }
}
```

### 3.3 Listar Notas de um Animal

- **Endpoint**: `GET /api/animal/{id}/notas?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista todas as notas de um animal específico
- **Exemplo**: `GET /api/animal/1/notas?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
[
  {
    "id": 1,
    "nota": "Primeira consulta - animal saudável",
    "animais_id": 1,
    "userprofiles_id": 2,
    "created_at": "2024-01-01 10:00:00",
    "autor": "Veterinário Silva"
  }
]
```

### 3.4 Contar Total de Animais

- **Endpoint**: `GET /api/animal/count?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Retorna o total de animais do cliente
- **Resposta**:

```json
{
  "count": 5
}
```

### 3.5 Listar Nomes dos Animais

- **Endpoint**: `GET /api/animal/nomes?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista apenas ID e nomes dos animais
- **Resposta**:

```json
[
  {
    "id": 1,
    "nome": "Rex"
  },
  {
    "id": 2,
    "nome": "Mia"
  }
]
```

### 3.6 Verificar Microchip do Animal

- **Endpoint**: `GET /api/animal/{id}/microchip?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Verifica se o animal possui microchip (retorna 1 se possui, 0 se não possui)
- **Exemplo**: `GET /api/animal/1/microchip?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
{
  "id": 1,
  "tem_microchip": 1
}
```

### 3.7 Listar Animais por Espécie

- **Endpoint**: `GET /api/animal/especie/{especie_id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista animais de uma espécie específica
- **Exemplo**: `GET /api/animal/especie/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
[
  {
    "id": 1,
    "nome": "Rex",
    "raca": "Labrador",
    "peso": 30.5,
    "sexo": "M"
  }
]
```

---

## 4. MARCAÇÕES/CONSULTAS (Marcacao Controller)

**Nota:** Todos os endpoints de marcações retornam apenas as marcações dos animais pertencentes ao cliente autenticado.

### 4.1 Listar Todas as Marcações

- **Endpoint**: `GET /api/marcacao/all?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista todas as marcações dos animais do cliente (todos os estados)
- **Resposta**:

```json
[
  {
    "id": 1,
    "data": "2024-01-15",
    "horainicio": "10:00:00",
    "horafim": "10:30:00",
    "estado": "pendente",
    "duracao_minutos": 30,
    "diagnostico": "Consulta de rotina",
    "servico_nome": "Consulta Geral",
    "animal_nome": "Rex",
    "animal_especie": "Cão",
    "created_at": "2024-01-01 10:00:00",
    "updated_at": "2024-01-01 10:00:00"
  }
]
```

### 4.2 Ver Detalhes de uma Marcação

- **Endpoint**: `GET /api/marcacao/view/{id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Retorna detalhes completos de uma marcação
- **Exemplo**: `GET /api/marcacao/view/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
{
  "id": 1,
  "data": "2024-01-15",
  "horainicio": "10:00:00",
  "horafim": "10:30:00",
  "estado": "pendente",
  "duracao_minutos": 30,
  "diagnostico": "Consulta de rotina",
  "servicos_id": 1,
  "servico_nome": "Consulta Geral",
  "animais_id": 1,
  "userprofiles_id": 1,
  "created_at": "2024-01-01 10:00:00",
  "updated_at": "2024-01-01 10:00:00",
  "animal": {
    "id": 1,
    "nome": "Rex",
    "especie": "Cão",
    "raca": "Labrador",
    "idade": 5,
    "peso": 30.5,
    "sexo": "M"
  }
}
```

### 4.3 Contar Total de Marcações

- **Endpoint**: `GET /api/marcacao/count?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Retorna o total de marcações do cliente
- **Resposta**:

```json
{
  "count": 10
}
```

### 4.4 Listar Marcações por Estado

- **Endpoint**: `GET /api/marcacao/estado/{estado}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista marcações por estado específico
- **Estados**: `pendente`, `realizada`, `cancelada`
- **Exemplo**: `GET /api/marcacao/estado/pendente?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
[
  {
    "id": 1,
    "data": "2024-01-15",
    "horainicio": "10:00:00",
    "horafim": "10:30:00",
    "estado": "pendente",
    "animal_nome": "Rex",
    "servico_nome": "Consulta Geral",
    "diagnostico": "Consulta de rotina"
  }
]
```

### 4.5 Listar Marcações por Data

- **Endpoint**: `GET /api/marcacao/data/{ano}/{mes}/{dia}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista marcações de uma data específica
- **Exemplo**: `GET /api/marcacao/data/2024/01/15?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
{
  "data": "2024-01-15",
  "total": 3,
  "marcacoes": [
    {
      "id": 1,
      "horainicio": "10:00:00",
      "horafim": "10:30:00",
      "estado": "pendente",
      "duracao_minutos": 30,
      "animal_nome": "Rex",
      "servico_nome": "Consulta Geral",
      "diagnostico": "Consulta de rotina"
    }
  ]
}
```

---

## 5. FATURAS (Fatura Controller)

### 5.1 Listar Todas as Faturas

- **Endpoint**: `GET /api/fatura/all?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista todas as faturas do cliente (todos os estados)
- **Resposta**:

```json
[
  {
    "id": 1,
    "total": 150.5,
    "estado": "0",
    "metodospagamentos_id": 1,
    "metodo_pagamento": "Multibanco",
    "userprofiles_id": 1,
    "created_at": "2024-01-15 10:00:00",
    "numero_itens": 3
  }
]
```

### 5.2 Ver Detalhes de uma Fatura

- **Endpoint**: `GET /api/fatura/view/{id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Retorna detalhes completos de uma fatura com linhas
- **Exemplo**: `GET /api/fatura/view/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
{
  "id": 1,
  "total": 150.5,
  "estado": "0",
  "metodospagamentos_id": 1,
  "metodo_pagamento": "Multibanco",
  "userprofiles_id": 1,
  "created_at": "2024-01-15 10:00:00",
  "cliente": {
    "id": 1,
    "nomecompleto": "Cliente Um",
    "nif": "123456789"
  },
  "linhas": [
    {
      "id": 1,
      "descricao": "Consulta Geral",
      "tipo": "servico",
      "quantidade": 1,
      "preco_unitario": 50.0,
      "total": 50.0
    },
    {
      "id": 2,
      "descricao": "Vacina Antirrábica",
      "tipo": "medicamento",
      "quantidade": 2,
      "preco_unitario": 50.25,
      "total": 100.5
    }
  ]
}
```

### 5.3 Listar Métodos de Pagamento

- **Endpoint**: `GET /api/fatura/paymentmethods?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista métodos de pagamento disponíveis
- **Resposta**:

```json
[
  {
    "id": 1,
    "nome": "Multibanco",
    "vigor": 1
  },
  {
    "id": 2,
    "nome": "Cartão de Crédito",
    "vigor": 1
  }
]
```

### 5.4 Pagar Fatura

- **Endpoint**: `PUT /api/fatura/pay/{id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Marca uma fatura como paga
- **Exemplo**: `PUT /api/fatura/pay/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Body (opcional)**:

```json
{
  "metodospagamentos_id": 1
}
```

- **Resposta**:

```json
{
  "success": true,
  "message": "Fatura paga com sucesso",
  "fatura": {
    "id": 1,
    "total": 150.5,
    "estado": "1",
    "metodospagamentos_id": 1,
    "created_at": "2024-01-15 10:00:00"
  }
}
```

### 5.5 Contar Total de Faturas

- **Endpoint**: `GET /api/fatura/count?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Retorna o total de faturas do cliente
- **Resposta**:

```json
{
  "count": 15
}
```

### 5.6 Total Geral de Faturas

- **Endpoint**: `GET /api/fatura/total?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Retorna a soma total de todas as faturas
- **Resposta**:

```json
{
  "total": 2350.75,
  "moeda": "EUR"
}
```

### 5.7 Listar Faturas por Ano

- **Endpoint**: `GET /api/fatura/ano/{ano}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista faturas de um ano específico com resumo
- **Exemplo**: `GET /api/fatura/ano/2024?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
{
  "ano": 2024,
  "resumo": {
    "total_geral": 2350.75,
    "quantidade": 15,
    "pagas": 10,
    "pendentes": 5
  },
  "faturas": [
    {
      "id": 1,
      "total": 150.5,
      "estado": "1",
      "estado_label": "Paga",
      "metodo_pagamento": "Multibanco",
      "created_at": "2024-01-15 10:00:00"
    }
  ]
}
```

---

## 6. NOTAS (Nota Controller)

### 6.1 Listar Todas as Notas

- **Endpoint**: `GET /api/nota/all?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista todas as notas dos animais do cliente
- **Resposta**:

```json
[
  {
    "id": 1,
    "nota": "Animal apresenta boa saúde",
    "animais_id": 1,
    "animal_nome": "Rex",
    "userprofiles_id": 2,
    "autor": "Veterinário Silva",
    "created_at": "2024-01-15 10:00:00"
  }
]
```

### 6.2 Ver Detalhes de uma Nota

- **Endpoint**: `GET /api/nota/view/{id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Retorna detalhes de uma nota específica
- **Exemplo**: `GET /api/nota/view/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
{
  "id": 1,
  "nota": "Animal apresenta boa saúde",
  "animais_id": 1,
  "animal_nome": "Rex",
  "userprofiles_id": 2,
  "autor": "Veterinário Silva",
  "created_at": "2024-01-15 10:00:00"
}
```

### 6.3 Criar Nova Nota

- **Endpoint**: `POST /api/nota/create?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Cria uma nova nota para um animal
- **Body**:

```json
{
  "animais_id": 1,
  "nota": "Animal vacinado hoje"
}
```

- **Resposta**:

```json
{
  "success": true,
  "message": "Nota criada com sucesso",
  "nota": {
    "id": 2,
    "nota": "Animal vacinado hoje",
    "animais_id": 1,
    "userprofiles_id": 1,
    "created_at": "2024-01-15 11:00:00"
  }
}
```

### 6.4 Atualizar Nota

- **Endpoint**: `PUT /api/nota/update/{id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Atualiza uma nota existente
- **Exemplo**: `PUT /api/nota/update/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Body**:

```json
{
  "nota": "Texto da nota atualizado"
}
```

- **Resposta**:

```json
{
  "success": true,
  "message": "Nota atualizada com sucesso",
  "nota": {
    "id": 1,
    "nota": "Texto da nota atualizado",
    "animais_id": 1,
    "userprofiles_id": 1
  }
}
```

### 6.5 Deletar Nota

- **Endpoint**: `DELETE /api/nota/delete/{id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Deleta uma nota existente
- **Exemplo**: `DELETE /api/nota/delete/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
{
  "success": true,
  "message": "Nota deletada com sucesso"
}
```

---

## 7. LEMBRETES (Lembrete Controller)

### 7.1 Listar Todos os Lembretes

- **Endpoint**: `GET /api/lembrete/all?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Lista todos os lembretes do cliente
- **Resposta**:

```json
[
  {
    "id": 1,
    "descricao": "Vacinar Rex na próxima semana",
    "created_at": "2024-01-15 10:00:00",
    "updated_at": "2024-01-15 10:00:00",
    "userprofiles_id": 1
  }
]
```

### 7.2 Ver Detalhes de um Lembrete

- **Endpoint**: `GET /api/lembrete/view/{id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Retorna detalhes de um lembrete específico
- **Exemplo**: `GET /api/lembrete/view/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
{
  "id": 1,
  "descricao": "Vacinar Rex na próxima semana",
  "created_at": "2024-01-15 10:00:00",
  "updated_at": "2024-01-15 10:00:00",
  "userprofiles_id": 1
}
```

### 7.3 Criar Novo Lembrete

- **Endpoint**: `POST /api/lembrete/create?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Cria um novo lembrete
- **Body**:

```json
{
  "descricao": "Marcar consulta para Mia"
}
```

- **Resposta**:

```json
{
  "success": true,
  "message": "Lembrete criado com sucesso",
  "lembrete": {
    "descricao": "Marcar consulta para Mia"
  }
}
```

### 7.4 Atualizar Lembrete

- **Endpoint**: `PUT /api/lembrete/update/{id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Atualiza um lembrete existente
- **Exemplo**: `PUT /api/lembrete/update/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Body**:

```json
{
  "descricao": "Lembrete atualizado"
}
```

- **Resposta**:

```json
{
  "success": true,
  "message": "Lembrete atualizado com sucesso",
  "lembrete": {
    "id": 1,
    "descricao": "Lembrete atualizado"
  }
}
```

### 7.5 Deletar Lembrete

- **Endpoint**: `DELETE /api/lembrete/delete/{id}?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Autenticação**: ✅ Requer token
- **Descrição**: Deleta um lembrete existente
- **Exemplo**: `DELETE /api/lembrete/delete/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP`
- **Resposta**:

```json
{
  "success": true,
  "message": "Lembrete deletado com sucesso"
}
```

---

## 8. HEALTH CHECK (Health Controller)

### 8.1 Verificar Status do Servidor

- **Endpoint**: `GET /api/health`
- **Autenticação**: ❌ Não requer
- **Descrição**: Verifica se o servidor está funcionando
- **Resposta**:

```json
{
  "status": "ok",
  "message": "Servidor funcionando corretamente",
  "timestamp": "2024-01-15T10:00:00Z",
  "version": "1.0.0"
}
```

---

## Códigos de Status HTTP

- **200 OK**: Requisição bem-sucedida
- **201 Created**: Recurso criado com sucesso
- **400 Bad Request**: Dados inválidos ou faltando
- **401 Unauthorized**: Token inválido ou ausente
- **403 Forbidden**: Sem permissão para acessar recurso
- **404 Not Found**: Recurso não encontrado
- **500 Internal Server Error**: Erro no servidor

---

## Exemplos de Uso com cURL

### Login

```bash
curl -X POST "http://localhost/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"username":"cliente1","password":"cliente123"}'
```

### Listar Animais

```bash
curl -X GET "http://localhost/api/animal/all?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP"
```

### Criar Nota

```bash
curl -X POST "http://localhost/api/nota/create?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP" \
  -H "Content-Type: application/json" \
  -d '{"animais_id":1,"nota":"Nova nota para o animal"}'
```

### Pagar Fatura

```bash
curl -X PUT "http://localhost/api/fatura/pay/1?access-token=L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP" \
  -H "Content-Type: application/json" \
  -d '{"metodospagamentos_id":1}'
```

---

## Notas Importantes

1. **Formato de Resposta**: Todos os endpoints retornam JSON
2. **CORS**: Configurado para aceitar requisições de qualquer origem (\*)
3. **Autenticação**: Token deve ser enviado via query parameter `access-token`
4. **Timezone**: Todas as datas/horas estão no formato UTC
5. **Eliminação**: Registros marcados como "eliminado=1" não aparecem nas listagens
6. **Permissões**: Usuários com role "cliente" têm acesso apenas aos seus próprios dados

---

**Última Atualização**: Janeiro 2026  
**Versão da API**: 1.0.0
