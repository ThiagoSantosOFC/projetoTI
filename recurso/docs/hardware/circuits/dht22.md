# Guia: Sensor DHT22 (Temperatura + Humidade)

## Montagem na Breadboard

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 400" font-family="Arial, sans-serif">
  <rect width="600" height="400" fill="#f8fafc" rx="8"/>

  <!-- Breadboard -->
  <rect x="80" y="20" width="440" height="340" rx="6" fill="#fafafa" stroke="#cbd5e1" stroke-width="2"/>

  <!-- Power Rails -->
  <rect x="88" y="28" width="200" height="28" rx="3" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <text x="188" y="47" text-anchor="middle" font-size="9" fill="#dc2626">🔴 VCC (3.3V)</text>

  <rect x="310" y="28" width="200" height="28" rx="3" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="410" y="47" text-anchor="middle" font-size="9" fill="#16a34a">🟢 GND</text>

  <!-- DHT22 Sensor -->
  <rect x="230" y="80" width="100" height="50" rx="4" fill="#e0f2fe" stroke="#2563eb" stroke-width="2"/>
  <text x="280" y="103" text-anchor="middle" font-size="11" font-weight="bold" fill="#1e3a5f">DHT22</text>
  <text x="280" y="118" text-anchor="middle" font-size="8" fill="#2563eb">Temperatura/Humidade</text>

  <!-- DHT22 Pins -->
  <rect x="230" y="140" width="20" height="12" rx="2" fill="#fef2f2"/>
  <text x="240" y="149" text-anchor="middle" font-size="8" fill="#dc2626">1</text>

  <rect x="258" y="140" width="20" height="12" rx="2" fill="#fef2f2"/>
  <text x="268" y="149" text-anchor="middle" font-size="8" fill="#dc2626">2</text>

  <rect x="286" y="140" width="20" height="12" rx="2" fill="#fef2f2"/>
  <text x="296" y="149" text-anchor="middle" font-size="8" fill="#dc2626">3</text>

  <rect x="314" y="140" width="20" height="12" rx="2" fill="#fef2f2"/>
  <text x="324" y="149" text-anchor="middle" font-size="8" fill="#dc2626">4</text>

  <!-- Pin labels -->
  <text x="240" y="172" text-anchor="middle" font-size="8" fill="#dc2626">VCC</text>
  <text x="268" y="172" text-anchor="middle" font-size="8" fill="#ea580c">DATA</text>
  <text x="296" y="172" text-anchor="middle" font-size="8" fill="#94a3b8">NC</text>
  <text x="324" y="172" text-anchor="middle" font-size="8" fill="#16a34a">GND</text>

  <!-- Jumpers -->
  <!-- VCC (red) -->
  <line x1="240" y1="185" x2="160" y2="250" stroke="#dc2626" stroke-width="3"/>
  <text x="140" y="245" text-anchor="end" font-size="9" fill="#dc2626">3.3V</text>

  <!-- DATA (orange) -->
  <line x1="268" y1="185" x2="268" y2="220" stroke="#f97316" stroke-width="3"/>
  <line x1="268" y1="220" x2="160" y2="220" stroke="#f97316" stroke-width="3"/>
  <text x="140" y="225" text-anchor="end" font-size="9" fill="#ea580c">GPIO 4</text>

  <!-- GND (black) -->
  <line x1="324" y1="185" x2="380" y2="260" stroke="#1e293b" stroke-width="3"/>
  <text x="390" y="265" font-size="9" fill="#1e293b">GND</text>

  <!-- Pull-up resistor 10k -->
  <rect x="210" y="190" width="24" height="10" rx="2" fill="#fef9c3" stroke="#ca8a04" stroke-width="1"/>
  <text x="222" y="198" text-anchor="middle" font-size="7" fill="#92400e">10kΩ</text>

  <!-- Pull-up connections -->
  <line x1="240" y1="185" x2="222" y2="195" stroke="#dc2626" stroke-width="1.5"/>
  <line x1="268" y1="185" x2="268" y2="210" stroke="#f97316" stroke-width="1.5"/>
  <line x1="222" y1="195" x2="222" y2="210" stroke="#f97316" stroke-width="1.5"/>
  <line x1="222" y1="210" x2="268" y2="210" stroke="#f97316" stroke-width="1.5"/>

  <text x="215" y="235" text-anchor="middle" font-size="8" fill="#b45309">10kΩ pull-up</text>

  <!-- Annotations -->
  <rect x="100" y="290" width="400" height="55" rx="4" fill="#fff7ed" stroke="#fdba74" stroke-width="1"/>
  <text x="110" y="308" font-size="9" fill="#9a3412">Conexões DHT22:</text>
  <text x="110" y="324" font-size="9" fill="#9a3412">Pin 1 (VCC) → 3.3V · Pin 2 (DATA) → GPIO 4 · Pin 4 (GND) → GND</text>
  <text x="110" y="340" font-size="9" fill="#9a3412">Resistor 10kΩ entre VCC e DATA (pull-up obrigatório!)</text>
</svg>
```

## Esquema Elétrico

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 250" font-family="Arial, sans-serif">
  <rect width="500" height="250" fill="#f8fafc" rx="8"/>

  <!-- 3.3V source -->
  <circle cx="60" cy="40" r="16" fill="#fef2f2" stroke="#dc2626" stroke-width="2"/>
  <text x="60" y="45" text-anchor="middle" font-size="10" fill="#dc2626">3.3V</text>

  <!-- DHT22 box -->
  <rect x="200" y="60" width="100" height="80" rx="4" fill="#e0f2fe" stroke="#2563eb" stroke-width="2"/>
  <text x="250" y="95" text-anchor="middle" font-size="12" font-weight="bold" fill="#1e3a5f">DHT22</text>
  <text x="250" y="115" text-anchor="middle" font-size="10" fill="#2563eb">Sensor</text>

  <!-- DHT22 pins -->
  <circle cx="210" cy="130" r="4" fill="#dc2626"/>
  <text x="170" y="134" text-anchor="end" font-size="10" fill="#1e293b">1 VCC</text>

  <circle cx="240" cy="130" r="4" fill="#ea580c"/>
  <text x="170" y="154" text-anchor="end" font-size="10" fill="#1e293b">2 DATA</text>

  <circle cx="290" cy="130" r="4" fill="#16a34a"/>
  <text x="310" y="134" font-size="10" fill="#1e293b">4 GND</text>

  <!-- VCC to DHT22 pin 1 -->
  <line x1="60" y1="56" x2="60" y2="80" stroke="#dc2626" stroke-width="1.5"/>
  <line x1="60" y1="80" x2="210" y2="80" stroke="#dc2626" stroke-width="1.5"/>
  <line x1="210" y1="80" x2="210" y2="126" stroke="#dc2626" stroke-width="1.5"/>

  <!-- DATA to GPIO -->
  <line x1="240" y1="130" x2="240" y2="180" stroke="#f97316" stroke-width="1.5"/>
  <line x1="240" y1="180" x2="380" y2="180" stroke="#f97316" stroke-width="1.5"/>
  <circle cx="380" cy="180" r="12" fill="#fff7ed" stroke="#f97316" stroke-width="2"/>
  <text x="380" y="184" text-anchor="middle" font-size="8" fill="#ea580c">GPIO</text>

  <!-- GND -->
  <line x1="290" y1="130" x2="290" y2="210" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="290" y1="210" x2="200" y2="210" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="170" y1="210" x2="230" y2="210" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="180" y1="218" x2="220" y2="218" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="190" y1="226" x2="210" y2="226" stroke="#1e293b" stroke-width="1.5"/>
  <text x="200" y="240" text-anchor="middle" font-size="9" fill="#1e293b">GND</text>

  <!-- Pull-up resistor -->
  <rect x="95" y="65" width="24" height="12" rx="2" fill="#fef9c3" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="85" y1="71" x2="95" y2="71" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="85" y1="77" x2="95" y2="77" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="119" y1="71" x2="129" y2="71" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="119" y1="77" x2="129" y2="77" stroke="#ca8a04" stroke-width="1.5"/>
  <text x="110" y="100" text-anchor="middle" font-size="9" fill="#92400e">10kΩ</text>

  <!-- Pull-up connections -->
  <line x1="60" y1="65" x2="80" y2="71" stroke="#dc2626" stroke-width="1.5"/>
  <line x1="240" y1="130" x2="240" y2="100" stroke="#f97316" stroke-width="1.5"/>
  <line x1="240" y1="100" x2="129" y2="74" stroke="#f97316" stroke-width="1.5"/>
</svg>
```

## Código Python

```python
# main.py - SBC (Raspberry Pi)
from lib.devices.dht22 import DHT22
import time

sensor = DHT22(pin=4)  # GPIO 4

while True:
    temp, hum = sensor.read()
    print(f"Temperatura: {temp:.1f}°C  Humidade: {hum:.1f}%")
    time.sleep(2)
```

**Ficheiro `lib/devices/dht22.py`:**
```python
import Adafruit_DHT  # ou usar RPi.GPIO com bitbang

class DHT22:
    def __init__(self, pin):
        self.pin = pin

    def read(self):
        hum, temp = Adafruit_DHT.read_retry(Adafruit_DHT.DHT22, self.pin)
        return temp, hum
```

## Código C (ESP32)

```c
// main.c - ESP32
#include <DHT.h>
#include <WiFi.h>

#define DHTPIN 4
#define DHTTYPE DHT22

DHT dht(DHTPIN, DHTTYPE);

void setup() {
    Serial.begin(115200);
    dht.begin();
    WiFi.begin("SSID", "password");
}

void loop() {
    float temp = dht.readTemperature();
    float hum = dht.readHumidity();

    if (isnan(temp) || isnan(hum)) {
        Serial.println("Erro a ler DHT22!");
        return;
    }

    Serial.printf("Temp: %.1f°C  Hum: %.1f%%\n", temp, hum);

    // Enviar para API
    // HTTP POST /api/update.php

    delay(5000);
}
```

## Troubleshooting

| Problema | Causa | Solução |
|----------|-------|---------|
| Leituras = NaN | Pull-up em falta | Adicionar resistor 10kΩ entre VCC e DATA |
| Leituras = NaN | Pinos trocados | Verificar VCC→3.3V, DATA→GPIO, GND→GND |
| Leituras erradas (temp fixa) | Sensor avariado | Substituir DHT22 (componente sensível) |
| Leituras lentas | Intervalo <2s | DHT22 precisa de 2s entre leituras |
| Valor negativo | Humidade do ambiente baixa | Normal em ambientes secos (>0% é válido) |
| WiFi não conecta (ESP32) | SSID/pass errados | Verificar credenciais no código |
| Sem dados no dashboard | API offline | Verificar servidor PHP / endpoint |
