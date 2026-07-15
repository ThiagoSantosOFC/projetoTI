# Cenário de Exemplo: Sala de Servidores

## Descrição

Sistema IoT para monitorização de uma sala de servidores. Monitoriza temperatura e humidade, ativa alarme sonoro se temperatura exceder limite, e acende LED de alerta.

## Componentes

| Componente | Tipo | GPIO (RPi) | Função |
|------------|------|-------------|--------|
| DHT22 | Sensor | GPIO 4 | Temperatura e humidade |
| Buzzer | Atuador | GPIO 22 | Alarme sonoro |
| LED vermelho | Atuador | GPIO 18 | Alerta visual |
| LED verde | Atuador | GPIO 23 | Status OK |

## Esquema Geral

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 650 220" font-family="Arial, sans-serif">
  <rect width="650" height="220" fill="#f8fafc" rx="8"/>

  <!-- Sala Servidores -->
  <rect x="180" y="20" width="290" height="170" rx="8" fill="#f1f5f9" stroke="#64748b" stroke-width="2"/>
  <text x="325" y="45" text-anchor="middle" font-weight="bold" font-size="14" fill="#1e293b">🖥 SALA DE SERVIDORES</text>

  <!-- DHT22 -->
  <rect x="200" y="65" width="90" height="40" rx="4" fill="#e0f2fe" stroke="#2563eb" stroke-width="1.5"/>
  <text x="245" y="83" text-anchor="middle" font-size="10" fill="#1e3a5f">DHT22</text>
  <text x="245" y="97" text-anchor="middle" font-size="8" fill="#2563eb">Temp + Hum</text>

  <!-- Buzzer -->
  <rect x="340" y="65" width="90" height="40" rx="4" fill="#fef9c3" stroke="#ca8a04" stroke-width="1.5"/>
  <text x="385" y="83" text-anchor="middle" font-size="10" fill="#92400e">🔔 Buzzer</text>
  <text x="385" y="97" text-anchor="middle" font-size="8" fill="#92400e">Alarme</text>

  <!-- LED Red -->
  <circle cx="230" cy="150" r="18" fill="#fef2f2" stroke="#ef4444" stroke-width="2"/>
  <circle cx="230" cy="150" r="10" fill="#fca5a5"/>
  <circle cx="230" cy="150" r="5" fill="#ef4444"/>
  <text x="230" y="175" text-anchor="middle" font-size="9" fill="#dc2626">LED 🔴</text>

  <!-- LED Green -->
  <circle cx="330" cy="150" r="18" fill="#f0fdf4" stroke="#22c55e" stroke-width="2"/>
  <circle cx="330" cy="150" r="10" fill="#86efac"/>
  <circle cx="330" cy="150" r="5" fill="#22c55e"/>
  <text x="330" y="175" text-anchor="middle" font-size="9" fill="#16a34a">LED 🟢</text>

  <!-- Rack -->
  <rect x="400" y="120" width="50" height="60" rx="3" fill="#1e293b"/>
  <rect x="405" y="125" width="40" height="8" rx="1" fill="#334155"/>
  <rect x="405" y="137" width="40" height="8" rx="1" fill="#334155"/>
  <rect x="405" y="149" width="40" height="8" rx="1" fill="#334155"/>
  <rect x="405" y="161" width="40" height="8" rx="1" fill="#334155"/>
  <text x="425" y="187" text-anchor="middle" font-size="8" fill="#1e293b">Rack</text>
</svg>
```

## Código Python (main.py)

```python
# main.py — SBC da sala de servidores
import time
import requests
from lib.devices.dht22 import DHT22
from lib.devices.buzzer import Buzzer
from lib.devices.led import LED

# Config
API = "http://server/api/update.php"
TEMP_LIMITE = 28.0  # °C — temperatura máxima permitida

# Inicializar
dht = DHT22(pin=4)
buzzer = Buzzer(pin=22)
led_alerta = LED(pin=18)
led_ok = LED(pin=23)

alarme_ativo = False

def enviar(recurso, valor, tipo):
    data = {"recurso": recurso, "valor": valor, "tipo": tipo}
    try:
        requests.post(API, json=data)
    except:
        pass

while True:
    temp, hum = dht.read()

    if temp and hum:
        print(f"Sala: {temp:.1f}°C, {hum:.1f}%")

        if temp > TEMP_LIMITE:
            # ATIVA ALARME
            if not alarme_ativo:
                print("⚠ TEMPERATURA EXCEDIDA!")
                alarme_ativo = True

            led_alerta.on()
            led_ok.off()
            buzzer.on()

            # Buzzer intermitente
            time.sleep(0.5)
            buzzer.off()
            time.sleep(0.5)
            buzzer.on()
        else:
            # TUDO NORMAL
            if alarme_ativo:
                print("✓ Temperatura normalizada")
                alarme_ativo = False

            led_ok.on()
            led_alerta.off()
            buzzer.off()

        # Enviar para API
        enviar("servidores_temp", f"{temp:.1f}", "temperatura")
        enviar("servidores_hum", f"{hum:.1f}", "humidade")
        enviar("servidores_alerta", "ATIVO" if alarme_ativo else "NORMAL", "estado")

    time.sleep(2)
```

## Código C (ESP32)

```c
// mcu_servidores.ino
#include <DHT.h>
#include <WiFi.h>
#include <HTTPClient.h>

#define DHTPIN 4
#define DHTTYPE DHT22
#define BUZZER_PIN 22
#define LED_ALERTA 18
#define LED_OK 23

DHT dht(DHTPIN, DHTTYPE);
const float TEMP_MAX = 28.0;

void setup() {
    Serial.begin(115200);
    dht.begin();
    pinMode(BUZZER_PIN, OUTPUT);
    pinMode(LED_ALERTA, OUTPUT);
    pinMode(LED_OK, OUTPUT);
    WiFi.begin("SSID", "password");
    while (WiFi.status() != WL_CONNECTED) delay(500);
}

void loop() {
    float temp = dht.readTemperature();
    float hum = dht.readHumidity();

    if (isnan(temp) || isnan(hum)) {
        delay(2000);
        return;
    }

    if (temp > TEMP_MAX) {
        digitalWrite(LED_ALERTA, HIGH);
        digitalWrite(LED_OK, LOW);
        digitalWrite(BUZZER_PIN, HIGH);
        delay(500);
        digitalWrite(BUZZER_PIN, LOW);
        delay(500);
    } else {
        digitalWrite(LED_ALERTA, LOW);
        digitalWrite(LED_OK, HIGH);
        digitalWrite(BUZZER_PIN, LOW);
    }

    // Enviar para API
    HTTPClient http;
    http.begin("http://server/api/update.php");
    http.addHeader("Content-Type", "application/json");

    String body = "{\"recurso\":\"servidores_temp\",\"valor\":" + String(temp) + ",\"tipo\":\"temperatura\"}";
    http.POST(body);
    http.end();

    delay(2000);
}
```

## Dashboard HTML (exemplo)

```html
<div class="card-servidores">
    <h2>🖥 Sala de Servidores</h2>
    <p>Temperatura: <span id="s-temp">--</span>°C</p>
    <p>Humidade: <span id="s-hum">--</span>%</p>
    <p>Estado: <span id="s-estado">---</span></p>
    <div id="s-alerta" style="display:none; background:#fee2e2; padding:10px; border-radius:5px;">
        ⚠ TEMPERATURA EXCEDIDA!
    </div>
</div>

<script>
setInterval(async () => {
    const r = await fetch('/api/recursos.php');
    const data = await r.json();
    if (data.recursos) {
        data.recursos.forEach(r => {
            if (r.id === 'servidores_temp')
                document.getElementById('s-temp').innerText = r.valor;
            if (r.id === 'servidores_hum')
                document.getElementById('s-hum').innerText = r.valor;
            if (r.id === 'servidores_alerta') {
                document.getElementById('s-estado').innerText = r.valor;
                document.getElementById('s-alerta').style.display =
                    r.valor === 'ATIVO' ? 'block' : 'none';
            }
        });
    }
}, 2000);
</script>
```

## Recursos na API

| ID | Tipo | Descrição |
|----|------|-----------|
| `servidores_temp` | Sensor | Temperatura da sala |
| `servidores_hum` | Sensor | Humidade da sala |
| `servidores_alerta` | Sensor | Estado do alarme (NORMAL/ATIVO) |
