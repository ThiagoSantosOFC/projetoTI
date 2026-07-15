# Como Adicionar um Novo Sensor — Tutorial Passo-a-Passo

## Passo 1: Montar o Circuito na Breadboard

1. Consultar o guia do circuito em `docs/hardware/circuits/`
2. Colocar o sensor na breadboard respeitando a orientação dos pinos
3. Ligar VCC à linha de alimentação positiva (3.3V ou 5V conforme o sensor)
4. Ligar GND à linha de alimentação negativa
5. Ligar o pino de dados ao GPIO escolhido
6. Adicionar resistores de pull-up/pull-down se necessário

**Exemplo (DHT22):**
- VCC → 3.3V (jumper vermelho)
- DATA → GPIO 4 (jumper laranja)
- GND → GND (jumper preto)
- Resistor 10kΩ entre VCC e DATA (pull-up)

## Passo 2: Criar Pasta do Recurso na API

No servidor web, criar a pasta do recurso:

```bash
mkdir -p /var/www/html/data/resources/temp1/
```

Criar `config.txt`:
```
tipo=sensor
pino_gpio=4
modelo=DHT22
intervalo=5
unidade=°C
```

Criar `state.txt` (vazio inicialmente):
```
```

Criar `log.txt` (vazio inicialmente):
```
```

## Passo 3: Adicionar ao Config.py (SBC — Python)

Editar `config.py` no SBC:

```python
# config.py
SENSORES = {
    "temp1": {
        "id": "temp1",
        "tipo": "dht22",
        "pino": 4,
        "api_url": "http://server/api/update.php",
        "intervalo": 5,
        "token": "meu_token"
    },
    # Adicionar novos sensores aqui:
    "presenca1": {
        "id": "presenca1",
        "tipo": "pir",
        "pino": 17,
        "api_url": "http://server/api/update.php",
        "intervalo": 1,
        "token": "meu_token"
    },
}
```

## Passo 4: Adicionar ao config.h (MCU — C/C++)

Editar `config.h` no MCU:

```c
// config.h
#define WIFI_SSID "minha_rede"
#define WIFI_PASS "minha_password"
#define API_URL "http://server/api/update.php"
#define API_TOKEN "meu_token"

// Sensores
#define TEMP1_PIN 4
#define PRESENCA1_PIN 34

// Intervalos (ms)
#define TEMP1_INTERVAL 5000
#define PRESENCA1_INTERVAL 1000
```

## Passo 5: Instanciar no main.py

```python
# main.py
import time
from config import SENSORES
from lib.devices.dht22 import DHT22
from lib.devices.pir import PIR
import requests

# Instanciar sensores a partir da config
dispositivos = {}
for id, cfg in SENSORES.items():
    if cfg["tipo"] == "dht22":
        dispositivos[id] = DHT22(pin=cfg["pino"])
    elif cfg["tipo"] == "pir":
        dispositivos[id] = PIR(pin=cfg["pino"])
    # Adicionar novos tipos aqui

# Loop principal
while True:
    for id, sensor in dispositivos.items():
        cfg = SENSORES[id]
        valor = sensor.read()
        print(f"{id}: {valor}")

        # Enviar para API
        data = {"recurso": id, "valor": valor, "tipo": cfg["tipo"]}
        r = requests.post(cfg["api_url"], json=data)
        print(f"  API: {r.status_code}")

    time.sleep(1)
```

## Passo 6: Instanciar no main.c (MCU)

```c
// main.c
#include "config.h"
#include <DHT.h>

DHT dht(TEMP1_PIN, DHT22);

void setup() {
    Serial.begin(115200);
    dht.begin();
    WiFi.begin(WIFI_SSID, WIFI_PASS);
}

void loop() {
    float temp = dht.readTemperature();
    float hum = dht.readHumidity();

    if (!isnan(temp)) {
        // Enviar para API
        // HTTP POST...
    }

    delay(TEMP1_INTERVAL);
}
```

## Passo 7: Verificar no Dashboard

1. Abrir o dashboard no browser
2. Confirmar que o novo recurso aparece
3. Verificar se o valor é atualizado no intervalo definido
4. Verificar o `log.txt` — deve mostrar entradas periódicas

## Passo 8: Testar com curl

```bash
# Verificar se o recurso existe
curl http://server/api/recursos.php

# Verificar estado
curl http://server/api/state.php?id=temp1

# Verificar histórico
curl http://server/api/log.php?id=temp1
```
