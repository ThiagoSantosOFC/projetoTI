# Comunicação SBC-MCU: 6 Pares Obrigatórios

## Tabela dos 6 Pares de Comunicação

| # | Origem | Destino | Meio | Implementação |
|---|--------|---------|------|---------------|
| 1 | Código Python (SBC) | Atuadores no MCU | UART Serial | SBC envia `LED:ON\n` → MCU recebe e atua no GPIO |
| 2 | Código Python (SBC) | Dashboard | HTTP POST | SBC faz POST `/api/update.php` → PHP atualiza ficheiros |
| 3 | Botão/evento no Dashboard | Atuadores no MCU | HTTP + Serial | PHP escreve `commands.txt` → SBC lê (polling) → reencaminha via Serial |
| 4 | Botão/evento no Dashboard | Código Python (SBC) | HTTP POST | PHP escreve comando → SBC faz polling GET `/api/commands.php` |
| 5 | Código C (MCU) | Dashboard | HTTP POST (WiFi) | MCU (ESP32) faz POST diretamente para API PHP |
| 6 | Código C (MCU) | Código Python (SBC) | UART Serial | MCU envia `TEMP:23.5\n` → SBC lê da Serial |

---

## Par 1: Python (SBC) → Atuadores no MCU

**SBC envia comando via UART para o MCU.**

```python
# SBC (Python)
import serial
import time

ser = serial.Serial('/dev/ttyUSB0', 9600, timeout=1)
time.sleep(2)  # Aguardar reset do MCU

def enviar_comando(atuador, acao):
    comando = f"{atuador}:{acao}\n"
    ser.write(comando.encode())
    print(f"Enviado: {comando.strip()}")
```

```c
// MCU (C)
#include <Arduino.h>

void setup() {
    Serial.begin(9600);
    pinMode(18, OUTPUT);  // LED
    pinMode(22, OUTPUT);  // Buzzer
}

void loop() {
    if (Serial.available()) {
        String cmd = Serial.readStringUntil('\n');
        cmd.trim();

        if (cmd == "LED:ON") digitalWrite(18, HIGH);
        else if (cmd == "LED:OFF") digitalWrite(18, LOW);
        else if (cmd == "BUZZER:ON") digitalWrite(22, HIGH);
        else if (cmd == "BUZZER:OFF") digitalWrite(22, LOW);
    }
}
```

---

## Par 2: Python (SBC) → Dashboard

**SBC lê sensores e envia para o dashboard via HTTP.**

```python
# SBC (Python) - Envio de leituras
import requests
import time

def enviar_leitura(recurso_id, valor, tipo="temperatura"):
    data = {
        "recurso": recurso_id,
        "valor": valor,
        "tipo": tipo,
        "token": "meu_token"
    }
    r = requests.post(
        "http://server/api/update.php",
        json=data
    )
    return r.status_code == 200

# Uso:
enviar_leitura("temp1", 23.5, "temperatura")
enviar_leitura("hum1", 65.2, "humidade")
```

```php
// PHP (API) - update.php
<?php
$data = json_decode(file_get_contents('php://input'), true);
$recurso = $data['recurso'] ?? '';
$valor = $data['valor'] ?? '';
$tipo = $data['tipo'] ?? '';

if (!$recurso) {
    http_response_code(400);
    echo json_encode(["erro" => "Campos obrigatórios: recurso, valor"]);
    exit;
}

$dir = "data/resources/$recurso/";
if (!is_dir($dir)) mkdir($dir, 0777, true);

// Atualizar state.txt
file_put_contents("$dir/state.txt", $valor);

// Adicionar ao log.txt
$linha = date("Y-m-d H:i:s") . "|" . $valor . "\n";
file_put_contents("$dir/log.txt", $linha, FILE_APPEND);

echo json_encode(["status" => "ok"]);
?>
```

---

## Par 3: Dashboard → Atuadores no MCU

**Utilizador clica no dashboard → comando chega ao MCU via SBC.**

```php
// PHP - command.php (escrita do comando)
<?php
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';
$comando = $data['comando'] ?? '';

if ($id && $comando) {
    file_put_contents("data/resources/$id/commands.txt", $comando);
    echo json_encode(["status" => "ok"]);
} else {
    http_response_code(400);
}
?>
```

```python
# SBC (Python) - Polling de comandos
import requests
import serial

ser = serial.Serial('/dev/ttyUSB0', 9600)

while True:
    # Ler comando pendente
    r = requests.get("http://server/api/commands.php?id=led1")
    if r.status_code == 200 and r.text.strip():
        comando = r.text.strip()
        # Reencaminhar para MCU via Serial
        ser.write((comando + "\n").encode())
        # Limpar comando na API
        requests.post("http://server/api/clear_command.php",
                      json={"id": "led1"})
        print(f"Comando executado: {comando}")
    time.sleep(1)
```

---

## Par 4: Dashboard → Código Python (SBC)

**Dashboard envia ação que o SBC executa (sem passar pelo MCU).**

```javascript
// JavaScript (Dashboard)
async function acaoSBC(acao) {
    await fetch('/api/command.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: 'sbc_acao', comando: acao})
    });
}

// Uso:
document.getElementById('btnCapturar').onclick = () => {
    acaoSBC('CAPTURAR_IMAGEM');
};
```

```python
# SBC (Python) - Processar ação do dashboard
while True:
    r = requests.get("http://server/api/commands.php?id=sbc_acao")
    if r.status_code == 200:
        comando = r.text.strip()
        if comando == "CAPTURAR_IMAGEM":
            import subprocess
            subprocess.run(["fswebcam", "snapshot.jpg"])
            with open("snapshot.jpg", "rb") as f:
                requests.post("http://server/api/webcam.php",
                              files={"imagem": f})
        requests.post("http://server/api/clear_command.php",
                      json={"id": "sbc_acao"})
    time.sleep(1)
```

---

## Par 5: Código C (MCU) → Dashboard

**MCU (ESP32 com WiFi) envia leituras diretamente para a API.**

```c
// MCU (ESP32)
#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

#define DHTPIN 4
#define DHTTYPE DHT22
DHT dht(DHTPIN, DHTTYPE);

const char* ssid = "minha_rede";
const char* password = "minha_password";
const char* serverUrl = "http://server/api/update.php";
const char* token = "meu_token_mcu";

void setup() {
    Serial.begin(115200);
    dht.begin();

    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("\nWiFi conectado!");
}

void loop() {
    float temp = dht.readTemperature();
    float hum = dht.readHumidity();

    if (isnan(temp) || isnan(hum)) {
        Serial.println("Erro no DHT22");
        delay(2000);
        return;
    }

    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");

    String body = "{\"recurso\":\"temp1\",\"valor\":" + String(temp) +
                  ",\"tipo\":\"temperatura\",\"token\":\"" + token + "\"}";

    int httpCode = http.POST(body);
    if (httpCode > 0) {
        Serial.printf("HTTP: %d\n", httpCode);
    } else {
        Serial.printf("Erro HTTP: %s\n", http.errorToString(httpCode).c_str());
    }
    http.end();

    delay(5000);
}
```

---

## Par 6: Código C (MCU) → Código Python (SBC)

**MCU envia dados pela Serial, SBC lê e processa.**

```c
// MCU (C) - Envio de dados via Serial
void loop() {
    float temp = dht.readTemperature();
    float hum = dht.readHumidity();

    if (!isnan(temp) && !isnan(hum)) {
        // Formato: ID:TIPO:VALOR
        Serial.print("temp1:TEMPERATURA:");
        Serial.println(temp);

        Serial.print("hum1:HUMIDADE:");
        Serial.println(hum);
    }

    delay(2000);
}
```

```python
# SBC (Python) - Leitura da Serial
import serial
import requests
import threading

def ler_serial():
    ser = serial.Serial('/dev/ttyUSB0', 9600, timeout=1)
    while True:
        linha = ser.readline().decode().strip()
        if not linha:
            continue

        # Parse: recurso:tipo:valor
        partes = linha.split(":")
        if len(partes) >= 3:
            recurso_id = partes[0]
            tipo = partes[1]
            valor = partes[2]

            print(f"MCU → {recurso_id}: {valor} ({tipo})")

            # Reencaminhar para API
            data = {
                "recurso": recurso_id,
                "valor": float(valor),
                "tipo": tipo
            }
            requests.post("http://server/api/update.php", json=data)

# Thread para ler serial em background
thread = threading.Thread(target=ler_serial, daemon=True)
thread.start()
```

## Diagrama Geral de Fluxo

```
┌──────────────┐     HTTP      ┌──────────────┐
│   Dashboard   │◄───────────►│  Servidor Web │
│   (Browser)   │    (3)(4)    │  (PHP + TXT)  │
└──────────────┘              └──────┬───────┘
                                     │
                            (2) HTTP │  (5) HTTP
                                     │
                    ┌────────────────┴────────────────┐
                    │                                 │
              ┌─────▼──────┐                  ┌───────▼───┐
              │  SBC (Python) │                 │ MCU (C)   │
              │              │◄─── UART (6) ───►│           │
              │  GPIO/Sens   │                 │ GPIO/Atu   │
              └──────┬───────┘                 └───────▲────┘
                     │  (1) UART                      │
                     └────────────────────────────────┘
```

**Legenda:**
- **(1)** SBC → MCU via UART: comandos para atuadores
- **(2)** SBC → Servidor via HTTP: leituras para dashboard
- **(3)** Dashboard → MCU via HTTP + UART: comandos do utilizador
- **(4)** Dashboard → SBC via HTTP: ações no código Python
- **(5)** MCU → Servidor via HTTP: leituras diretas (ESP32 WiFi)
- **(6)** MCU → SBC via UART: dados de sensores
