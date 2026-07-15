# Autenticação no Sistema IoT

## 1. Criar Hash bcrypt (PHP: password_hash)

O PHP fornece a função `password_hash()` para criar hashes seguros:

```php
<?php
// Criar hash para um utilizador
$password = "minha_password123";
$hash = password_hash($password, PASSWORD_BCRYPT);
echo $hash;
// Output: $2y$10$...

// Verificar password
$input = "minha_password123";
if (password_verify($input, $hash)) {
    echo "Password correta!";
} else {
    echo "Password errada!";
}
?>
```

**Script para criar hash rapidamente:**
```php
<?php
// hash_password.php
if ($argc < 2) {
    die("Uso: php hash_password.php <password>\n");
}
echo password_hash($argv[1], PASSWORD_BCRYPT) . "\n";
?>
```

```bash
# Uso
php hash_password.php "admin123"
```

## 2. Formato do Ficheiro utilizadores.txt

```
admin:$2y$10$9I...hash...:admin
joao:$2y$10$8G...hash...:utilizador
```

**Formato:** `username:hash_bcrypt:tipo`

Onde:
- `username` — nome do utilizador
- `hash_bcrypt` — hash gerado por `password_hash()`
- `tipo` — `admin` ou `utilizador`

**Exemplo:**
```
admin:$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi:admin
```

## 3. Sistema de Sessão PHP

### Login (login.php)

```php
<?php
// login.php
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['user'] ?? '';
$password = $data['pass'] ?? '';

// Ler utilizadores.txt
$users = file("utilizadores.txt", FILE_IGNORE_NEW_LINES);
$autenticado = false;

foreach ($users as $linha) {
    $partes = explode(":", $linha, 3);
    if (count($partes) == 3 && $partes[0] == $username) {
        if (password_verify($password, $partes[1])) {
            $autenticado = true;
            $_SESSION['user'] = $username;
            $_SESSION['tipo'] = trim($partes[2]);
            $_SESSION['login_time'] = time();
            break;
        }
    }
}

if ($autenticado) {
    echo json_encode(["status" => "ok", "mensagem" => "Login bem-sucedido"]);
} else {
    http_response_code(401);
    echo json_encode(["erro" => "Credenciais inválidas"]);
}
?>
```

### Verificar Sessão (middleware)

```php
<?php
// auth_middleware.php
session_start();

function esta_autenticado() {
    return isset($_SESSION['user']);
}

function require_auth() {
    if (!esta_autenticado()) {
        http_response_code(401);
        echo json_encode(["erro" => "Não autenticado"]);
        exit;
    }
}

function is_admin() {
    return isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin';
}
?>
```

**Uso nos endpoints protegidos:**
```php
<?php
require_once 'auth_middleware.php';
require_auth();

// Código do endpoint...
?>
```

### Logout (logout.php)

```php
<?php
// logout.php
session_start();
session_destroy();
echo json_encode(["status" => "ok", "mensagem" => "Sessão terminada"]);
?>
```

## 4. API Tokens (Autenticação de Máquina)

Para sensores e atuadores que não fazem login (SBC, MCU), usa-se tokens de API.

### Gerar Token

```php
<?php
// Gerar token aleatório
function gerar_token($length = 32) {
    return bin2hex(random_bytes($length));
}

$token = gerar_token();
echo "Token: " . $token . "\n";
// Exemplo: a1b2c3d4e5f6...
?>
```

### Formato do tokens.txt

```
temp1:a1b2c3d4e5f6...:sensor
led1:ff11aa22bb33...:atuador
sbc:99ee88dd77cc...:sbc
```

**Formato:** `recurso_id:token:tipo`

### Validar Token (middleware)

```php
<?php
// token_middleware.php
function validar_token() {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    // Suporta "Bearer token" e "token" simples
    if (strpos($token, 'Bearer ') === 0) {
        $token = substr($token, 7);
    }

    if (!$token) {
        // Tentar do body
        $data = json_decode(file_get_contents('php://input'), true);
        $token = $data['token'] ?? '';
    }

    // Ler tokens.txt
    if (!file_exists('tokens.txt')) {
        return false;
    }

    $linhas = file('tokens.txt', FILE_IGNORE_NEW_LINES);
    foreach ($linhas as $linha) {
        $partes = explode(":", $linha, 3);
        if (count($partes) == 3 && trim($partes[1]) === $token) {
            return $partes[0]; // retorna o ID do recurso
        }
    }

    return false;
}

function require_token() {
    $recurso = validar_token();
    if (!$recurso) {
        http_response_code(401);
        echo json_encode(["erro" => "Token inválido"]);
        exit;
    }
    return $recurso;
}
?>
```

### Uso nos endpoints da API

```php
<?php
// update.php (protegido por token)
require_once 'token_middleware.php';
$recurso_id = require_token(); // valida e retorna ID

$data = json_decode(file_get_contents('php://input'), true);
$valor = $data['valor'] ?? '';

// Atualizar ficheiros...
file_put_contents("data/resources/$recurso_id/state.txt", $valor);
$log = date("Y-m-d H:i:s") . "|" . $valor . "\n";
file_put_contents("data/resources/$recurso_id/log.txt", $log, FILE_APPEND);

echo json_encode(["status" => "ok"]);
?>
```

### Envio de Token pelo SBC/MCU

```python
# Python (SBC)
headers = {"Authorization": "Bearer meu_token_aqui"}
r = requests.post("http://server/api/update.php",
                  json={"recurso": "temp1", "valor": 23.5},
                  headers=headers)
```

```c
// C (ESP32)
http.addHeader("Authorization", "Bearer meu_token_aqui");
```

## Resumo: Qual Método Usar?

| Situação | Método | Ficheiro |
|----------|--------|----------|
| Login de humanos | Sessão PHP + `password_hash` | `utilizadores.txt` |
| Sensores/atuadores (SBC/MCU) | Token Bearer | `tokens.txt` |
| Dashboard web | Sessão PHP (cookie) | Sessão do servidor |
| Pedidos curl de teste | Token no header | `tokens.txt` |
