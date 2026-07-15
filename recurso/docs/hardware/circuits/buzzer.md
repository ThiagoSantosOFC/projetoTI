# Guia: Buzzer

## Montagem na Breadboard

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 380" font-family="Arial, sans-serif">
  <rect width="600" height="380" fill="#f8fafc" rx="8"/>

  <!-- Breadboard -->
  <rect x="80" y="20" width="440" height="320" rx="6" fill="#fafafa" stroke="#cbd5e1" stroke-width="2"/>

  <!-- Power Rails -->
  <rect x="88" y="28" width="200" height="28" rx="3" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <text x="188" y="47" text-anchor="middle" font-size="9" fill="#dc2626">🔴 VCC (5V / 3.3V)</text>
  <rect x="310" y="28" width="200" height="28" rx="3" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="410" y="47" text-anchor="middle" font-size="9" fill="#16a34a">🟢 GND</text>

  <!-- Buzzer -->
  <circle cx="280" cy="120" r="30" fill="#fef9c3" stroke="#ca8a04" stroke-width="2"/>
  <text x="280" y="115" text-anchor="middle" font-size="10" font-weight="bold" fill="#92400e">BUZZER</text>
  <text x="280" y="130" text-anchor="middle" font-size="8" fill="#92400e">5V</text>

  <!-- Buzzer pins -->
  <line x1="265" y1="150" x2="240" y2="200" stroke="#dc2626" stroke-width="2"/>
  <line x1="295" y1="150" x2="320" y2="200" stroke="#1e293b" stroke-width="2"/>
  <text x="230" y="215" text-anchor="end" font-size="8" fill="#dc2626">VCC</text>
  <text x="330" y="215" text-anchor="start" font-size="8" fill="#1e293b">GND</text>

  <!-- GPIO (orange) -->
  <line x1="240" y1="200" x2="170" y2="200" stroke="#f97316" stroke-width="3"/>
  <text x="155" y="205" text-anchor="end" font-size="9" fill="#ea580c">GPIO 22</text>

  <!-- GND (black) -->
  <line x1="320" y1="200" x2="380" y2="260" stroke="#1e293b" stroke-width="3"/>
  <text x="390" y="265" font-size="9" fill="#1e293b">GND</text>

  <!-- Note: transistor needed for buzzer > 3.3V -->
  <rect x="100" y="260" width="400" height="55" rx="4" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="110" y="278" font-size="9" fill="#166534">⚠ Buzzer 5V com transistor 2N2222:</text>
  <text x="110" y="294" font-size="9" fill="#166534">GPIO → Resistor 1kΩ → Base 2N2222 · Coletor → Buzzer(-) · Emissor → GND</text>
  <text x="110" y="310" font-size="9" fill="#166534">Buzzer(+) → VCC 5V. Buzzer 3.3V pode ligar diretamente ao GPIO.</text>
</svg>
```

## Esquema Elétrico (com Transistor 2N2222 para buzzer 5V)

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 250" font-family="Arial, sans-serif">
  <rect width="500" height="250" fill="#f8fafc" rx="8"/>

  <!-- GPIO -->
  <circle cx="60" cy="60" r="14" fill="#fff7ed" stroke="#f97316" stroke-width="2"/>
  <text x="60" y="65" text-anchor="middle" font-size="9" fill="#ea580c">GPIO</text>

  <!-- Resistor 1k -->
  <rect x="120" y="50" width="24" height="14" rx="2" fill="#fef9c3" stroke="#ca8a04" stroke-width="1.5"/>
  <text x="132" y="61" text-anchor="middle" font-size="7" fill="#92400e">1kΩ</text>
  <line x1="74" y1="60" x2="120" y2="57" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="144" y1="57" x2="180" y2="57" stroke="#1e293b" stroke-width="1.5"/>

  <!-- 2N2222 -->
  <circle cx="200" cy="100" r="35" fill="#e0f2fe" stroke="#2563eb" stroke-width="2"/>
  <text x="200" y="95" text-anchor="middle" font-size="10" font-weight="bold" fill="#1e3a5f">2N2222</text>
  <text x="200" y="110" text-anchor="middle" font-size="8" fill="#2563eb">NPN</text>

  <!-- Base -->
  <line x1="200" y1="65" x2="200" y2="57" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="180" y1="57" x2="200" y2="57" stroke="#1e293b" stroke-width="1.5"/>
  <text x="175" y="82" text-anchor="end" font-size="8" fill="#475569">B</text>

  <!-- Emissor -->
  <line x1="200" y1="135" x2="200" y2="180" stroke="#1e293b" stroke-width="1.5"/>
  <text x="195" y="155" text-anchor="end" font-size="8" fill="#475569">E</text>

  <!-- GND -->
  <line x1="170" y1="180" x2="230" y2="180" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="180" y1="188" x2="220" y2="188" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="190" y1="196" x2="210" y2="196" stroke="#1e293b" stroke-width="1.5"/>
  <text x="200" y="210" text-anchor="middle" font-size="9" fill="#1e293b">GND</text>

  <!-- Coletor -->
  <line x1="225" y1="85" x2="270" y2="85" stroke="#1e293b" stroke-width="1.5"/>
  <text x="235" y="80" font-size="8" fill="#475569">C</text>

  <!-- Buzzer -->
  <rect x="270" y="60" width="50" height="50" rx="4" fill="#fef9c3" stroke="#ca8a04" stroke-width="2"/>
  <text x="295" y="85" text-anchor="middle" font-size="9" fill="#92400e">Buzzer</text>

  <!-- Buzzer to 5V -->
  <line x1="320" y1="70" x2="380" y2="40" stroke="#dc2626" stroke-width="1.5"/>
  <text x="380" y="36" text-anchor="end" font-size="9" fill="#dc2626">5V</text>

  <!-- Buzzer to collector -->
  <line x1="295" y1="110" x2="295" y2="130" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="270" y1="85" x2="295" y2="85" stroke="#1e293b" stroke-width="1.5"/>
</svg>
```

## Código Python

```python
# main.py - SBC (Raspberry Pi)
from lib.devices.buzzer import Buzzer
import time

buzzer = Buzzer(pin=22)  # GPIO 22

# Beep simples
buzzer.beep(0.5)

# Alarme
for _ in range(5):
    buzzer.beep(0.2)
    time.sleep(0.1)

buzzer.off()
```

**Ficheiro `lib/devices/buzzer.py`:**
```python
import RPi.GPIO as GPIO
import time

class Buzzer:
    def __init__(self, pin):
        self.pin = pin
        GPIO.setmode(GPIO.BCM)
        GPIO.setup(pin, GPIO.OUT)
        GPIO.output(pin, GPIO.LOW)

    def beep(self, duration=0.5):
        GPIO.output(self.pin, GPIO.HIGH)
        time.sleep(duration)
        GPIO.output(self.pin, GPIO.LOW)

    def on(self):
        GPIO.output(self.pin, GPIO.HIGH)

    def off(self):
        GPIO.output(self.pin, GPIO.LOW)
```

## Código C (ESP32)

```c
// main.c - ESP32
#define BUZZER_PIN 32

void setup() {
    pinMode(BUZZER_PIN, OUTPUT);
    digitalWrite(BUZZER_PIN, LOW);
}

void loop() {
    digitalWrite(BUZZER_PIN, HIGH);  // Beep ON
    delay(500);
    digitalWrite(BUZZER_PIN, LOW);   // Beep OFF
    delay(500);
}
```

## Troubleshooting

| Problema | Causa | Solução |
|----------|-------|---------|
| Buzzer não toca | Tensão insuficiente | Buzzer 5V precisa de transistor; 3.3V liga direto |
| Som muito fraco | GPIO não dá corrente suficiente | Usar transistor 2N2222 |
| Buzzer toca sempre | GPIO em HIGH permanente | Verificar se código faz `off()` após `beep()` |
| Sem som (ativo) | Polaridade trocada | Buzzer ativo tem polaridade (+ e -) |
