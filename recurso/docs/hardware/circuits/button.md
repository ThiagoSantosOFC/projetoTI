# Guia: Botão (com Pull-Down)

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

  <!-- Button -->
  <rect x="245" y="90" width="50" height="50" rx="6" fill="#fca5a5" stroke="#ef4444" stroke-width="2"/>
  <rect x="253" y="98" width="34" height="34" rx="3" fill="white"/>
  <text x="270" y="120" text-anchor="middle" font-size="14" font-weight="bold" fill="#ef4444">↓</text>

  <!-- Button pins (internally: top-left & bottom-left connected, top-right & bottom-right connected) -->
  <line x1="255" y1="140" x2="255" y2="180" stroke="#cbd5e1" stroke-width="2"/>
  <line x1="285" y1="140" x2="285" y2="180" stroke="#cbd5e1" stroke-width="2"/>

  <!-- Resistor 10k pull-down -->
  <rect x="210" y="200" width="24" height="10" rx="2" fill="#fef9c3" stroke="#ca8a04" stroke-width="1"/>
  <text x="222" y="208" text-anchor="middle" font-size="7" fill="#92400e">10k</text>

  <!-- GPIO - from button pin -->
  <line x1="255" y1="180" x2="255" y2="240" stroke="#f97316" stroke-width="3"/>
  <line x1="255" y1="240" x2="160" y2="240" stroke="#f97316" stroke-width="3"/>
  <text x="145" y="245" text-anchor="end" font-size="9" fill="#ea580c">GPIO 17</text>

  <!-- Resistor to GND -->
  <line x1="210" y1="205" x2="180" y2="205" stroke="#ca8a04" stroke-width="2"/>
  <line x1="180" y1="205" x2="180" y2="280" stroke="#1e293b" stroke-width="2"/>
  <line x1="180" y1="280" x2="380" y2="280" stroke="#1e293b" stroke-width="2"/>
  <text x="390" y="285" font-size="9" fill="#1e293b">GND</text>

  <!-- VCC to other button pin -->
  <line x1="285" y1="180" x2="285" y2="260" stroke="#dc2626" stroke-width="3"/>
  <line x1="285" y1="260" x2="160" y2="260" stroke="#dc2626" stroke-width="3"/>
  <text x="145" y="265" text-anchor="end" font-size="9" fill="#dc2626">3.3V</text>

  <!-- Circuit explanation -->
  <rect x="100" y="295" width="400" height="55" rx="4" fill="#fff7ed" stroke="#fdba74" stroke-width="1"/>
  <text x="110" y="313" font-size="9" fill="#9a3412">Funcionamento (pull-down):</text>
  <text x="110" y="329" font-size="9" fill="#9a3412">Botão solto: GPIO → Resistor 10kΩ → GND (LOW / 0)</text>
  <text x="110" y="345" font-size="9" fill="#9a3412">Botão pressionado: GPIO → VCC 3.3V (HIGH / 1) — com debounce no software!</text>
</svg>
```

## Código Python (com Debounce)

```python
# main.py - SBC (Raspberry Pi)
from lib.devices.button import Button
import time

botao = Button(pin=17)

while True:
    if botao.pressed():
        print("Botão pressionado!")
    time.sleep(0.05)
```

**Ficheiro `lib/devices/button.py`:**
```python
import RPi.GPIO as GPIO
import time

class Button:
    def __init__(self, pin, debounce_ms=200):
        self.pin = pin
        self.debounce_ms = debounce_ms
        self.last_time = 0
        GPIO.setmode(GPIO.BCM)
        GPIO.setup(pin, GPIO.IN)  # External pull-down

    def pressed(self):
        # Debounce por software
        if GPIO.input(self.pin) == GPIO.HIGH:
            now = time.time() * 1000
            if now - self.last_time > self.debounce_ms:
                self.last_time = now
                return True
        return False

    def wait_for_press(self):
        GPIO.wait_for_edge(self.pin, GPIO.RISING)
        time.sleep(self.debounce_ms / 1000)
        return True
```

## Código C (com Debounce)

```c
// main.c - ESP32/Arduino
#define BUTTON_PIN 17

unsigned long lastDebounceTime = 0;
const unsigned long debounceDelay = 200;

void setup() {
    Serial.begin(115200);
    pinMode(BUTTON_PIN, INPUT_PULLDOWN);  // Pull-down interno (ESP32)
}

void loop() {
    static int lastState = LOW;
    int reading = digitalRead(BUTTON_PIN);

    if (reading == HIGH && lastState == LOW &&
        millis() - lastDebounceTime > debounceDelay) {
        Serial.println("Botão pressionado!");
        lastDebounceTime = millis();
    }
    lastState = reading;
}
```

## Alternativa: Pull-Down vs Pull-Up

| Configuração | Botão solto | Botão pressionado | Resistor |
|-------------|-------------|-------------------|----------|
| **Pull-Down** (exemplo acima) | LOW (0) | HIGH (1) | GPIO → GND via 10kΩ |
| **Pull-Up** (interno RPi) | HIGH (1) | LOW (0) | Interno (GPIO.setup(pin, GPIO.IN, pull_up_down=GPIO.PUD_UP)) |

Para usar pull-up interno no RPi (sem resistor externo):
```python
GPIO.setup(pin, GPIO.IN, pull_up_down=GPIO.PUD_UP)
# Botão entre GPIO e GND
# Código: if GPIO.input(pin) == GPIO.LOW → pressionado
```

## Troubleshooting

| Problema | Causa | Solução |
|----------|-------|---------|
| Leituras aleatórias (bouncing) | Sem debounce | Adicionar debounce de 200ms no código |
| Botão não responde | Pull-down errado | Verificar resistor 10kΩ entre GPIO e GND |
| Estado trocado | Pull-up vs pull-down | Usar `PUD_UP` interno com botão entre GPIO e GND |
| Dispara múltiplas vezes | Debounce insuficiente | Aumentar debounce para 300ms |
| Sem reação | Pino GPIO errado | Verificar se é o mesmo no código e na breadboard |
