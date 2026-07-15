# Cenário de Exemplo: Estufa Agrícola

## Descrição

Sistema IoT para monitorização e controlo de uma estufa agrícola. O sistema lê temperatura e humidade do solo, controla luzes LED de crescimento e um ventilador para circulação de ar.

## Componentes

| Componente | Tipo | GPIO (RPi) | GPIO (ESP32) | Função |
|------------|------|-------------|---------------|--------|
| DHT22 | Sensor | GPIO 4 | GPIO 4 | Temperatura e humidade do ar |
| Sensor de humidade solo | Sensor | GPIO 17 (ADC) | GPIO 33 | Humidade do solo (analógico) |
| LED RGB (grow light) | Atuador | GPIO 18 (R), 19 (G), 13 (B) | GPIO 25, 26, 27 | Iluminação para crescimento |
| Ventilador (relé) | Atuador | GPIO 22 | GPIO 32 | Controlo de ventilação |

## Esquema Geral

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 650 250" font-family="Arial, sans-serif">
  <rect width="650" height="250" fill="#f8fafc" rx="8"/>

  <!-- Estufa -->
  <rect x="200" y="30" width="250" height="180" rx="8" fill="#f0fdf4" stroke="#86efac" stroke-width="2"/>
  <text x="325" y="55" text-anchor="middle" font-weight="bold" font-size="14" fill="#166534">🌱 ESTUFA AGRÍCOLA</text>

  <!-- DHT22 -->
  <rect x="220" y="70" width="80" height="40" rx="4" fill="#e0f2fe" stroke="#2563eb" stroke-width="1.5"/>
  <text x="260" y="88" text-anchor="middle" font-size="10" fill="#1e3a5f">DHT22</text>
  <text x="260" y="102" text-anchor="middle" font-size="8" fill="#2563eb">Temp + Hum</text>

  <!-- Solo sensor -->
  <rect x="340" y="170" width="80" height="30" rx="4" fill="#fef3c7" stroke="#ca8a04" stroke-width="1.5"/>
  <text x="380" y="190" text-anchor="middle" font-size="9" fill="#92400e">Humidade Solo</text>

  <!-- LED Grow -->
  <rect x="220" y="140" width="80" height="30" rx="4" fill="#fce7f3" stroke="#ec4899" stroke-width="1.5"/>
  <text x="260" y="160" text-anchor="middle" font-size="9" fill="#9d174d">LED Grow</text>
  <text x="260" y="168" text-anchor="middle" font-size="7" fill="#ec4899">RGB</text>

  <!-- Fan -->
  <rect x="340" y="70" width="80" height="40" rx="4" fill="#f1f5f9" stroke="#64748b" stroke-width="1.5"/>
  <text x="380" y="88" text-anchor="middle" font-size="10" fill="#1e293b">Ventilador</text>
  <text x="380" y="102" text-anchor="middle" font-size="8" fill="#64748b">Relé</text>

  <!-- Conexões -->
  <line x1="300" y1="90" x2="340" y2="90" stroke="#f97316" stroke-width="2"/>
  <line x1="260" y1="110" x2="260" y2="140" stroke="#f97316" stroke-width="2"/>
  <line x1="300" y1="160" x2="340" y2="160" stroke="#f97316" stroke-width="2"/>
  <line x1="380" y1="170" x2="380" y2="140" stroke="#f97316" stroke-width="2"/>
</svg>
```

## Código Python (main.py)

```python
# main.py — SBC da estufa
import time
import requests
from lib.devices.dht22 import DHT22
from lib.devices.servo import Servo
from lib.devices.relay import Relay
from lib.devices.led import LED

# Configuração
API = "http://server/api/update.php"
TOKEN = "token_estufa"

# Inicializar sensores
dht = DHT22(pin=4)
led_red = LED(pin=18)
led_green = LED(pin=19)
led_blue = LED(pin=13)
ventilador = Relay(pin=22)

# Limites
TEMP_MAX = 30.0  # °C
SOLO_SECO = 30   # % humidade solo

def enviar(recurso, valor, tipo):
    data = {"recurso": recurso, "valor": valor, "tipo": tipo}
    try:
        r = requests.post(API, json=data)
        print(f"{recurso}: {r.status_code}")
    except:
        print(f"Erro ao enviar {recurso}")

while True:
    # Ler sensores
    temp, hum = dht.read()
    solo = ler_humidade_solo()  # Implementar conforme ADC

    print(f"Temp: {temp:.1f}°C, Hum ar: {hum:.1f}%, Solo: {solo}%")

    # Controlo de temperatura
    if temp and temp > TEMP_MAX:
        ventilador.on()
        print("Ventilador LIGADO — temperatura alta")
    else:
        ventilador.off()
        print("Ventilador DESLIGADO")

    # Controlo de luz (simples: ligar durante o dia)
    hora = time.localtime().tm_hour
    if 6 <= hora < 20:
        led_red.on()
        led_blue.on()
    else:
        led_red.off()
        led_blue.off()

    # Enviar para API
    if temp:
        enviar("estufa_temp", f"{temp:.1f}", "temperatura")
    if hum:
        enviar("estufa_hum", f"{hum:.1f}", "humidade")
    enviar("estufa_solo", solo, "humidade_solo")
    enviar("estufa_vent", "ON" if ventilador else "OFF", "estado")

    time.sleep(5)
```

## Código C (ESP32 para sensores)

```c
// mcu_estufa.ino — Sensor node da estufa
#include <DHT.h>
#include <WiFi.h>
#include <HTTPClient.h>

#define DHTPIN 4
#define DHTTYPE DHT22
#define SOLO_PIN 33  // ADC
#define LED_GROW 25
#define FAN_RELAY 32

DHT dht(DHTPIN, DHTTYPE);

void setup() {
    Serial.begin(115200);
    dht.begin();
    pinMode(LED_GROW, OUTPUT);
    pinMode(FAN_RELAY, OUTPUT);

    WiFi.begin("SSID_ESTUFA", "password");
    while (WiFi.status() != WL_CONNECTED) delay(500);
}

void loop() {
    float temp = dht.readTemperature();
    float hum = dht.readHumidity();
    int solo = analogRead(SOLO_PIN);

    if (!isnan(temp)) {
        HTTPClient http;
        http.begin("http://server/api/update.php");
        http.addHeader("Content-Type", "application/json");
        String body = "{\"recurso\":\"estufa_temp\",\"valor\":" + String(temp) + ",\"tipo\":\"temperatura\"}";
        http.POST(body);
        http.end();
    }

    delay(5000);
}
```

## Recursos na API

| ID | Tipo | Descrição |
|----|------|-----------|
| `estufa_temp` | Sensor | Temperatura da estufa |
| `estufa_hum` | Sensor | Humidade do ar |
| `estufa_solo` | Sensor | Humidade do solo |
| `estufa_led` | Atuador | LED grow (ON/OFF) |
| `estufa_vent` | Atuador | Ventilador (ON/OFF) |
