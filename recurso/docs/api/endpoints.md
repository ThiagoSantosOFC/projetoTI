# Documentação da API REST

## Endpoints

### Tabela de Endpoints

| Método | Rota | Descrição | Autenticação | Corpo (exemplo) |
|--------|------|-----------|-------------|-----------------|
| `GET` | `/api/recursos.php` | Lista todos os recursos | Sessão PHP | — |
| `GET` | `/api/recursos.php?id=X` | Obtém recurso específico | Sessão PHP | — |
| `POST` | `/api/update.php` | Atualiza valor de um recurso | Token/API Key | `{"recurso":"temp1","valor":23.5}` |
| `GET` | `/api/state.php?id=X` | Obtém estado atual do recurso | Sessão PHP | — |
| `GET` | `/api/log.php?id=X` | Obtém histórico (log.txt) | Sessão PHP | — |
| `POST` | `/api/command.php` | Envia comando para atuador | Sessão PHP | `{"id":"led1","comando":"LED:ON"}` |
| `GET` | `/api/commands.php?id=X` | Lê comando pendente (SBC) | Token | — |
| `POST` | `/api/clear_command.php` | Limpa comando após leitura | Token | `{"id":"led1"}` |
| `POST` | `/api/login.php` | Autenticação de utilizador | — | `{"user":"admin","pass":"1234"}` |
| `GET` | `/api/logout.php` | Termina sessão | Sessão PHP | — |
| `POST` | `/api/webcam.php` | Recebe imagem da webcam | Token | `multipart/form-data` |
| `POST` | `/api/register.php` | Regista novo recurso | Sessão PHP | `{"tipo":"sensor","nome":"temp1"}` |

---

### 1. GET /api/recursos.php

Lista todos os recursos disponíveis.

**Request:**
```
GET /api/recursos.php
Cookie: PHPSESSID=abc123
```

**Response (200 OK):**
```json
{
  "recursos": [
    {"id": "temp1", "tipo": "sensor", "nome": "Temperatura Sala", "valor": "23.5", "estado": "online"},
    {"id": "led1", "tipo": "atuador", "nome": "LED Principal", "valor": "OFF", "estado": "online"},
    {"id": "presenca1", "tipo": "sensor", "nome": "Sensor Presença", "valor": "0", "estado": "online"}
  ]
}
```

**Response (401 Unauthorized):**
```json
{"erro": "Não autenticado"}
```

---

### 2. GET /api/recursos.php?id=X

Obtém detalhes de um recurso específico.

**Request:**
```
GET /api/recursos.php?id=temp1
```

**Response (200 OK):**
```json
{
  "id": "temp1",
  "tipo": "sensor",
  "config": {
    "pino_gpio": 4,
    "intervalo": 5,
    "modelo": "DHT22"
  },
  "state": "23.5",
  "ultima_atualizacao": "2026-07-15 10:00:05"
}
```

---

### 3. POST /api/update.php

Atualiza o valor de um recurso (enviado por SBC ou MCU).

**Request:**
```
POST /api/update.php
Content-Type: application/json
Authorization: Bearer token123

{
  "recurso": "temp1",
  "valor": 23.5,
  "tipo": "temperatura"
}
```

**Response (200 OK):**
```json
{"status": "ok", "mensagem": "Valor atualizado com sucesso"}
```

**Response (400 Bad Request):**
```json
{"erro": "Campos obrigatórios: recurso, valor"}
```

**Response (401 Unauthorized):**
```json
{"erro": "Token inválido"}
```

---

### 4. GET /api/state.php?id=X

Obtém o valor atual do recurso.

**Request:**
```
GET /api/state.php?id=temp1
```

**Response (200 OK):**
```
23.5
```

**Resposta é texto simples** (conteúdo do `state.txt`).

---

### 5. GET /api/log.php?id=X

Obtém o histórico de leituras (últimas 50 linhas do `log.txt`).

**Request:**
```
GET /api/log.php?id=temp1
```

**Response (200 OK):**
```json
{
  "id": "temp1",
  "log": [
    {"data": "2026-07-15 10:00:00", "valor": 23.5},
    {"data": "2026-07-15 10:00:05", "valor": 23.6},
    {"data": "2026-07-15 10:00:10", "valor": 23.4}
  ]
}
```

---

### 6. POST /api/command.php

Envia um comando para um atuador (a partir do dashboard).

**Request:**
```
POST /api/command.php
Content-Type: application/json
Cookie: PHPSESSID=abc123

{
  "id": "led1",
  "comando": "LED:ON"
}
```

**Response (200 OK):**
```json
{"status": "ok", "mensagem": "Comando escrito em commands.txt"}
```

---

### 7. GET /api/commands.php?id=X

Lê o comando pendente (usado pelo SBC em polling).

**Request:**
```
GET /api/commands.php?id=led1
Authorization: Bearer token456
```

**Response (200 OK):**
```
LED:ON
```

**Response (204 No Content):** (sem comandos pendentes)

---

### 8. POST /api/clear_command.php

Limpa o comando após o SBC o processar.

**Request:**
```
POST /api/clear_command.php
Content-Type: application/json
Authorization: Bearer token456

{
  "id": "led1"
}
```

**Response (200 OK):**
```json
{"status": "ok"}
```

---

### 9. POST /api/login.php

Autenticação de utilizador.

**Request:**
```
POST /api/login.php
Content-Type: application/json

{
  "user": "admin",
  "pass": "minha_password"
}
```

**Response (200 OK):**
```json
{"status": "ok", "mensagem": "Login bem-sucedido"}
```

**Response (401 Unauthorized):**
```json
{"erro": "Credenciais inválidas"}
```

---

### 10. POST /api/webcam.php

Recebe imagem da webcam.

**Request:**
```
POST /api/webcam.php
Content-Type: multipart/form-data
Authorization: Bearer token789

[ficheiro imagem]
```

**Response (200 OK):**
```json
{"status": "ok", "caminho": "data/resources/cam1/imagem.txt"}
```

---

## Formato dos Ficheiros TXT

Cada recurso tem uma pasta em `data/resources/{id}/` com:

### config.txt
```
tipo=sensor
pino_gpio=4
modelo=DHT22
intervalo=5
unidade=°C
```

### log.txt (formato CSV com pipe)
```
2026-07-15 10:00:00|23.5
2026-07-15 10:00:05|23.6
2026-07-15 10:00:10|23.4
```

### state.txt
Apenas o valor atual (texto simples):
```
23.5
```

### commands.txt
Comando pendente para atuador (ou vazio):
```
LED:ON
```

### imagem.txt
Caminho para a imagem ou base64:
```
data/resources/cam1/snapshot_20260715_100000.jpg
```

---

## Códigos de Erro

| Código | Significado | Causa Comum |
|--------|-------------|-------------|
| `200` | OK | Pedido processado com sucesso |
| `204` | Sem conteúdo | Comando vazio / sem dados |
| `400` | Bad Request | JSON mal formatado, campos em falta |
| `401` | Unauthorized | Token inválido, sessão expirada |
| `403` | Forbidden | Sem permissão para o recurso |
| `404` | Not Found | Recurso não existe |
| `500` | Internal Server Error | Erro no servidor, ficheiro não pode ser escrito |

---

## Exemplo de Fluxo Completo

```
Sensor DHT22 (MCU)
    │
    ├── lê temperatura (23.5°C)
    │
    ├── POST /api/update.php  {recurso: "temp1", valor: 23.5}
    │       │
    │       ▼
    │   Servidor Web
    │       │
    │       ├── atualiza state.txt → "23.5"
    │       ├── adiciona log.txt → "2026-07-15 10:00:00|23.5"
    │       │
    │       ▼
    │   Dashboard (JavaScript)
    │       │
    │       ├── setInterval(5000) → GET /api/state.php?id=temp1
    │       ├── atualiza gráfico com Chart.js
    │       └── mostra valor no ecrã
    │
    └── fim
```

```
Dashboard (utilizador clica "Ligar LED")
    │
    ├── POST /api/command.php  {id: "led1", comando: "LED:ON"}
    │       │
    │       ▼
    │   Servidor Web escreve commands.txt → "LED:ON"
    │
    │   SBC (polling a cada 1s)
    │       │
    │       ├── GET /api/commands.php?id=led1 → "LED:ON"
    │       ├── Envia "LED:ON\n" via Serial para MCU
    │       │       │
    │       │       ▼
    │       │   MCU liga LED (digitalWrite HIGH)
    │       │
    │       └── POST /api/clear_command.php {id: "led1"}
    │
    └── fim
```
