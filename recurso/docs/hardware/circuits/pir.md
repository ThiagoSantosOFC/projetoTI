# Guia: Sensor PIR (Presença / Movimento)

## Montagem na Breadboard

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 380" font-family="Arial, sans-serif">
  <rect width="600" height="380" fill="#f8fafc" rx="8"/>

  <!-- Breadboard -->
  <rect x="80" y="20" width="440" height="320" rx="6" fill="#fafafa" stroke="#cbd5e1" stroke-width="2"/>

  <!-- Power Rails -->
  <rect x="88" y="28" width="200" height="28" rx="3" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <text x="188" y="47" text-anchor="middle" font-size="9" fill="#dc2626">🔴 VCC (5V)</text>
  <rect x="310" y="28" width="200" height="28" rx="3" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="410" y="47" text-anchor="middle" font-size="9" fill="#16a34a">🟢 GND</text>

  <!-- PIR Sensor -->
  <rect x="200" y="80" width="160" height="100" rx="8" fill="#f1f5f9" stroke="#64748b" stroke-width="2"/>
  <!-- Dome -->
  <rect x="220" y="90" width="120" height="50" rx="25" fill="#e2e8f0" stroke="#94a3b8" stroke-width="1"/>
  <text x="280" y="118" text-anchor="middle" font-size="11" font-weight="bold" fill="#475569">PIR</text>
  <text x="280" y="160" text-anchor="middle" font-size="9" fill="#64748b">Sensor de Presença</text>

  <!-- Pins -->
  <rect x="220" y="190" width="22" height="12" rx="2" fill="#1e293b"/>
  <text x="231" y="199" text-anchor="middle" font-size="7" fill="white">GND</text>
  <rect x="252" y="190" width="22" height="12" rx="2" fill="#f97316"/>
  <text x="263" y="199" text-anchor="middle" font-size="7" fill="white">OUT</text>
  <rect x="284" y="190" width="22" height="12" rx="2" fill="#dc2626"/>
  <text x="295" y="199" text-anchor="middle" font-size="7" fill="white">VCC</text>

  <!-- GND -->
  <line x1="231" y1="220" x2="380" y2="280" stroke="#1e293b" stroke-width="3"/>
  <text x="390" y="285" font-size="9" fill="#1e293b">GND</text>

  <!-- VCC -->
  <line x1="295" y1="220" x2="160" y2="260" stroke="#dc2626" stroke-width="3"/>
  <text x="145" y="265" text-anchor="end" font-size="9" fill="#dc2626">5V</text>

  <!-- OUT -->
  <line x1="263" y1="220" x2="263" y2="240" stroke="#f97316" stroke-width="3"/>
  <line x1="263" y1="240" x2="160" y2="240" stroke="#f97316" stroke-width="3"/>
  <text x="145" y="245" text-anchor="end" font-size="9" fill="#ea580c">GPIO 17</text>

  <!-- Sensitivity adjustment -->
  <rect x="100" y="295" width="400" height="55" rx="4" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="110" y="313" font-size="9" fill="#166534">Ajustes no módulo PIR:</text>
  <text x="110" y="329" font-size="9" fill="#166534">Potenciómetro SENS — sensibilidade (sentido horário = maior alcance, até ~7m)</text>
  <text x="110" y="345" font-size="9" fill="#166534">Potenciómetro TIME — tempo de disparo (sentido horário = mais tempo HIGH, até ~5min)</text>
</svg>
```

## Código Python

```python
# main.py - SBC (Raspberry Pi)
from lib.devices.pir import PIR
import time

pir = PIR(pin=17)  # GPIO 17

def on_motion():
    print("Presença detetada!")

pir.on_detection(on_motion)

while True:
    time.sleep(0.1)
```

**Ficheiro `lib/devices/pir.py`:**
```python
import RPi.GPIO as GPIO

class PIR:
    def __init__(self, pin):
        self.pin = pin
        GPIO.setmode(GPIO.BCM)
        GPIO.setup(pin, GPIO.IN)

    def read(self):
        return GPIO.input(self.pin)

    def on_detection(self, callback):
        GPIO.add_event_detect(self.pin, GPIO.RISING, callback=callback)
```

## Código C (ESP32)

```c
// main.c - ESP32
#define PIR_PIN 34

void setup() {
    Serial.begin(115200);
    pinMode(PIR_PIN, INPUT);
}

void loop() {
    int presenca = digitalRead(PIR_PIN);
    if (presenca == HIGH) {
        Serial.println("Presença detetada!");
    } else {
        Serial.println("Sem movimento");
    }
    delay(500);
}
```

## Ajuste de Sensibilidade

O módulo PIR HC-SR501 tem dois potenciómetros:

1. **SENS (Sensibilidade):** Ajusta a distância de deteção
   - Máximo (sentido horário): ~7 metros
   - Mínimo (sentido anti-horário): ~3 metros
2. **TIME (Tempo):** Ajusta quanto tempo o sinal OUT fica HIGH após deteção
   - Máximo: ~5 minutos
   - Mínimo: ~5 segundos
3. **Trigger mode (jumper):**
   - **H (Repeatable):** Dispara novamente se movimento contínuo
   - **L (Non-repeatable):** Apenas um disparo, depois bloqueia

## Troubleshooting

| Problema | Causa | Solução |
|----------|-------|---------|
| Falso positivo | Sensor perto de fonte de calor | Afastar de aquecedores, sol |
| Sem deteção | Sensibilidade baixa | Rodar SENS no sentido horário |
| Fica sempre HIGH | TIME demasiado alto | Rodar TIME no sentido anti-horário |
| Dispara só uma vez | Trigger mode = L | Mudar jumper para H (repeatable) |
| Alcance curto | Lente obstruída | Limpar lente do PIR |
| Leitura instável | Alimentação insuficiente | PIR precisa de 5V estável |
