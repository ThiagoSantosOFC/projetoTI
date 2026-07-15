# Guia: LDR (Sensor de Luminosidade)

## Montagem na Breadboard

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 380" font-family="Arial, sans-serif">
  <rect width="600" height="380" fill="#f8fafc" rx="8"/>

  <!-- Breadboard -->
  <rect x="80" y="20" width="440" height="320" rx="6" fill="#fafafa" stroke="#cbd5e1" stroke-width="2"/>

  <!-- Power Rails -->
  <rect x="88" y="28" width="200" height="28" rx="3" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <text x="188" y="47" text-anchor="middle" font-size="9" fill="#dc2626">🔴 VCC (3.3V)</text>
  <rect x="310" y="28" width="200" height="28" rx="3" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="410" y="47" text-anchor="middle" font-size="9" fill="#16a34a">🟢 GND</text>

  <!-- LDR -->
  <circle cx="260" cy="120" r="25" fill="#1e293b" stroke="#475569" stroke-width="2"/>
  <circle cx="260" cy="120" r="12" fill="none" stroke="#64748b" stroke-width="1"/>
  <circle cx="260" cy="120" r="4" fill="#94a3b8"/>
  <!-- Light arrows -->
  <line x1="235" y1="95" x2="245" y2="105" stroke="#f59e0b" stroke-width="1.5"/>
  <line x1="260" y1="90" x2="260" y2="100" stroke="#f59e0b" stroke-width="1.5"/>
  <line x1="285" y1="95" x2="275" y2="105" stroke="#f59e0b" stroke-width="1.5"/>
  <text x="260" y="160" text-anchor="middle" font-size="10" font-weight="bold" fill="#475569">LDR</text>
  <text x="260" y="172" text-anchor="middle" font-size="8" fill="#64748b">Fotoresistor</text>

  <!-- LDR legs -->
  <line x1="240" y1="140" x2="220" y2="200" stroke="#cbd5e1" stroke-width="2"/>
  <line x1="280" y1="140" x2="300" y2="200" stroke="#cbd5e1" stroke-width="2"/>

  <!-- Resistor 10k -->
  <rect x="200" y="220" width="24" height="10" rx="2" fill="#fef9c3" stroke="#ca8a04" stroke-width="1"/>
  <text x="212" y="228" text-anchor="middle" font-size="7" fill="#92400e">10k</text>
  <line x1="220" y1="225" x2="200" y2="225" stroke="#ca8a04" stroke-width="2"/>
  <line x1="220" y1="231" x2="200" y2="231" stroke="#ca8a04" stroke-width="2"/>
  <line x1="224" y1="225" x2="240" y2="225" stroke="#ca8a04" stroke-width="2"/>
  <line x1="224" y1="231" x2="240" y2="231" stroke="#ca8a04" stroke-width="2"/>

  <!-- VCC -->
  <line x1="240" y1="200" x2="240" y2="260" stroke="#dc2626" stroke-width="2"/>
  <line x1="240" y1="260" x2="160" y2="260" stroke="#dc2626" stroke-width="2"/>
  <text x="145" y="265" text-anchor="end" font-size="9" fill="#dc2626">3.3V</text>

  <!-- ADC between LDR and R -->
  <circle cx="260" cy="225" r="4" fill="#f97316"/>
  <line x1="300" y1="200" x2="300" y2="240" stroke="#f97316" stroke-width="3"/>
  <line x1="300" y1="240" x2="360" y2="240" stroke="#f97316" stroke-width="3"/>
  <text x="370" y="245" font-size="9" fill="#ea580c">GPIO (ADC)</text>

  <!-- GND -->
  <line x1="212" y1="235" x2="212" y2="280" stroke="#1e293b" stroke-width="2"/>
  <line x1="212" y1="280" x2="380" y2="280" stroke="#1e293b" stroke-width="2"/>
  <text x="390" y="285" font-size="9" fill="#1e293b">GND</text>

  <!-- Circuit explanation -->
  <rect x="100" y="295" width="400" height="55" rx="4" fill="#fff7ed" stroke="#fdba74" stroke-width="1"/>
  <text x="110" y="313" font-size="9" fill="#9a3412">Divisor de Tensão: VCC (3.3V) → LDR → ADC ← Resistor 10kΩ → GND</text>
  <text x="110" y="329" font-size="9" fill="#9a3412">Mais luz = menor resistência LDR = maior tensão no ADC</text>
  <text x="110" y="345" font-size="9" fill="#9a3412">Valor ADC (0-1023): 0=escuro total, 1023=luz máxima</text>
</svg>
```

## Código Python

```python
# main.py - SBC (Raspberry Pi)
from lib.devices.ldr import LDR
import time

ldr = LDR(pin=25)  # GPIO 25 (ADC via chip externo)

while True:
    valor = ldr.read()
    print(f"Luminosidade (ADC): {valor}")
    time.sleep(1)
```

**Ficheiro `lib/devices/ldr.py`:**
```python
import RPi.GPIO as GPIO
import time

class LDR:
    def __init__(self, pin, capacitor_pin=None):
        self.pin = pin
        GPIO.setmode(GPIO.BCM)

    def read(self):
        # Método simples: medir tempo de carga do condensador
        GPIO.setup(self.pin, GPIO.OUT)
        GPIO.output(self.pin, GPIO.LOW)
        time.sleep(0.1)
        GPIO.setup(self.pin, GPIO.IN)
        count = 0
        while GPIO.input(self.pin) == GPIO.LOW:
            count += 1
        return count
```

## Código C (ESP32 — com ADC)

```c
// main.c - ESP32
#define LDR_PIN 33  // GPIO 33 (ADC1_CH5)

void setup() {
    Serial.begin(115200);
    analogReadResolution(12);  // 0-4095
}

void loop() {
    int valor = analogRead(LDR_PIN);
    float percent = (valor / 4095.0) * 100.0;
    Serial.printf("Luminosidade: %d (%d bits) - %.1f%%\n", valor, 4095, percent);
    delay(1000);
}
```

## Troubleshooting

| Problema | Causa | Solução |
|----------|-------|---------|
| Valor fixo (sempre 0 ou max) | Divisor de tensão mal montado | Verificar LDR + resistor 10kΩ em série |
| Leituras instáveis | Ruído no ADC | Adicionar condensador 100nF entre ADC e GND |
| Sem reação à luz | LDR avariado | Testar com multímetro (resistência varia com luz) |
| RPi sem ADC nativo | GPIO digital não lê analógico | Usar MCP3008 (ADC externo) ou condensador |
| No ESP32: valor sempre 4095 | Pino não suporta ADC | Usar GPIO 32-39 (ADC1) |
