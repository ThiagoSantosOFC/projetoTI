# Guia: LED + Resistor

## Montagem na Breadboard

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 400" font-family="Arial, sans-serif">
  <rect width="600" height="400" fill="#f8fafc" rx="8"/>

  <!-- Breadboard -->
  <rect x="100" y="20" width="400" height="340" rx="6" fill="#fafafa" stroke="#cbd5e1" stroke-width="2"/>

  <!-- Power Rails -->
  <rect x="108" y="28" width="180" height="30" rx="3" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <text x="198" y="48" text-anchor="middle" font-size="9" fill="#dc2626">🔴 VCC (3.3V)</text>

  <rect x="295" y="28" width="195" height="30" rx="3" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="392" y="48" text-anchor="middle" font-size="9" fill="#16a34a">🟢 GND</text>

  <!-- LED -->
  <circle cx="280" cy="150" r="16" fill="#fff9c4" stroke="#f59e0b" stroke-width="2"/>
  <!-- LED arrow -->
  <polygon points="275,140 285,140 280,130" fill="#f59e0b"/>
  <polygon points="273,148 287,148 280,156" fill="#f59e0b"/>
  <text x="280" y="120" text-anchor="middle" font-size="9" fill="#b45309">LED</text>

  <!-- LED legs -->
  <line x1="274" y1="166" x2="250" y2="230" stroke="#cbd5e1" stroke-width="2"/>  <!-- Anodo -->
  <line x1="286" y1="166" x2="310" y2="230" stroke="#cbd5e1" stroke-width="2"/>  <!-- Catodo -->

  <text x="240" y="225" text-anchor="end" font-size="8" fill="#dc2626">Ânodo (+)</text>
  <text x="320" y="225" text-anchor="start" font-size="8" fill="#16a34a">Cátodo (-)</text>

  <!-- Resistor -->
  <rect x="240" y="245" width="30" height="12" rx="2" fill="#fef9c3" stroke="#ca8a04" stroke-width="1"/>
  <text x="255" y="254" text-anchor="middle" font-size="7" fill="#92400e">220Ω</text>
  <line x1="250" y1="251" x2="240" y2="251" stroke="#ca8a04" stroke-width="2"/>
  <line x1="250" y1="257" x2="240" y2="257" stroke="#ca8a04" stroke-width="2"/>
  <line x1="280" y1="251" x2="290" y2="251" stroke="#ca8a04" stroke-width="2"/>
  <line x1="280" y1="257" x2="290" y2="257" stroke="#ca8a04" stroke-width="2"/>

  <!-- Jumpers -->
  <!-- GPIO 18 (orange) to resistor -->
  <line x1="180" y1="260" x2="240" y2="260" stroke="#f97316" stroke-width="3"/>
  <text x="165" y="265" text-anchor="end" font-size="9" fill="#ea580c">GPIO 18</text>

  <!-- GND (black) from LED cathode -->
  <line x1="310" y1="240" x2="380" y2="310" stroke="#1e293b" stroke-width="3"/>
  <text x="390" y="315" font-size="9" fill="#1e293b">GND</text>

  <!-- Annotations -->
  <text x="198" y="80" text-anchor="middle" font-size="11" font-weight="bold" fill="#1e293b">Circuito LED + Resistor (220Ω)</text>

  <!-- Arrow labels -->
  <text x="300" y="180" font-size="9" fill="#64748b">← Corrente</text>
  <path d="M 290 170 Q 310 180 300 190" fill="none" stroke="#64748b" stroke-width="1" marker-end="url(#arrowSmall)"/>

  <!-- GPIO pinout note -->
  <rect x="110" y="300" width="380" height="50" rx="4" fill="#fff7ed" stroke="#fdba74" stroke-width="1"/>
  <text x="120" y="318" font-size="9" fill="#9a3412">Ligação:</text>
  <text x="120" y="334" font-size="9" fill="#9a3412">GPIO 18 → Resistor 220Ω → Ânodo LED → Cátodo LED → GND</text>
  <text x="120" y="348" font-size="9" fill="#9a3412">OU: GPIO 18 → Ânodo LED → Cátodo → Resistor 220Ω → GND</text>
</svg>
```

## Esquema Elétrico

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" font-family="Arial, sans-serif">
  <rect width="400" height="300" fill="#f8fafc" rx="8"/>

  <!-- GPIO 18 source -->
  <circle cx="100" cy="50" r="20" fill="#fff7ed" stroke="#f97316" stroke-width="2"/>
  <text x="100" y="55" text-anchor="middle" font-size="10" fill="#ea580c">GPIO</text>

  <!-- Wire to resistor -->
  <line x1="100" y1="70" x2="100" y2="100" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="100" y1="100" x2="150" y2="100" stroke="#1e293b" stroke-width="1.5"/>

  <!-- Resistor symbol -->
  <rect x="150" y="90" width="40" height="20" rx="2" fill="white" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="155" y1="95" x2="155" y2="105" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="160" y1="93" x2="160" y2="107" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="165" y1="95" x2="165" y2="105" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="170" y1="93" x2="170" y2="107" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="175" y1="95" x2="175" y2="105" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="180" y1="93" x2="180" y2="107" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="185" y1="95" x2="185" y2="105" stroke="#1e293b" stroke-width="1.5"/>
  <text x="170" y="130" text-anchor="middle" font-size="9" fill="#64748b">220Ω</text>

  <!-- Wire from resistor to LED anode -->
  <line x1="190" y1="100" x2="250" y2="100" stroke="#1e293b" stroke-width="1.5"/>

  <!-- LED symbol -->
  <polygon points="250,85 270,100 250,115" fill="#fff9c4" stroke="#f59e0b" stroke-width="1.5"/>
  <line x1="270" y1="85" x2="270" y2="115" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="265" y1="87" x2="265" y2="89" stroke="#1e293b" stroke-width="1"/>
  <line x1="265" y1="111" x2="265" y2="113" stroke="#1e293b" stroke-width="1"/>
  <!-- LED arrows -->
  <polygon points="255,92 265,92 260,88" fill="#f59e0b"/>
  <polygon points="253,100 263,100 258,96" fill="#f59e0b"/>
  <polygon points="255,108 265,108 260,104" fill="#f59e0b"/>

  <!-- Wire to GND -->
  <line x1="270" y1="115" x2="270" y2="170" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="270" y1="170" x2="200" y2="170" stroke="#1e293b" stroke-width="1.5"/>

  <!-- GND symbol -->
  <line x1="170" y1="170" x2="230" y2="170" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="180" y1="178" x2="220" y2="178" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="190" y1="186" x2="210" y2="186" stroke="#1e293b" stroke-width="1.5"/>
  <text x="200" y="200" text-anchor="middle" font-size="9" fill="#1e293b">GND</text>
</svg>
```

## Tabela de Ligações

| GPIO / Fio | Componente | Descrição |
|------------|-----------|-----------|
| GPIO 18 (laranja) | Resistor 220Ω (perna 1) | Corrente limitada do GPIO |
| Resistor 220Ω (perna 2) | LED — Ânodo (+) | Perna mais longa do LED |
| LED — Cátodo (-) | GND (preto) | Perna mais curta do LED |
| *(alternativa)* | | Resistor pode estar antes ou depois do LED |

## Código Python (lib.devices.led)

```python
# main.py - SBC (Raspberry Pi)
from lib.devices.led import LED
import time

led = LED(pin=18)  # GPIO 18

while True:
    led.on()
    print("LED ligado")
    time.sleep(1)
    led.off()
    print("LED desligado")
    time.sleep(1)
```

**Ficheiro `lib/devices/led.py`:**
```python
import RPi.GPIO as GPIO

class LED:
    def __init__(self, pin):
        self.pin = pin
        GPIO.setmode(GPIO.BCM)
        GPIO.setup(pin, GPIO.OUT)
        GPIO.output(pin, GPIO.LOW)

    def on(self):
        GPIO.output(self.pin, GPIO.HIGH)

    def off(self):
        GPIO.output(self.pin, GPIO.LOW)

    def toggle(self):
        GPIO.output(self.pin, not GPIO.input(self.pin))

    def cleanup(self):
        GPIO.cleanup()
```

## Código C (led.h / main.c)

```c
// main.c - MCU (ESP32/Arduino)
#include "led.h"

void setup() {
    led_init(18);  // GPIO 18
}

void loop() {
    led_on();
    delay(1000);
    led_off();
    delay(1000);
}
```

**Ficheiro `led.h`:**
```c
#ifndef LED_H
#define LED_H
#include <Arduino.h>

int _led_pin;

void led_init(int pin) {
    _led_pin = pin;
    pinMode(pin, OUTPUT);
    digitalWrite(pin, LOW);
}

void led_on() {
    digitalWrite(_led_pin, HIGH);
}

void led_off() {
    digitalWrite(_led_pin, LOW);
}

void led_toggle() {
    digitalWrite(_led_pin, !digitalRead(_led_pin));
}
#endif
```

## Troubleshooting

| Problema | Causa Provável | Solução |
|----------|---------------|---------|
| LED não acende | Polaridade invertida | Ânodo (+) é a perna **mais longa**. Inverter o LED |
| LED não acende | Resistor demasiado alto | Usar resistor de **220Ω a 330Ω** |
| LED não acende | GPIO errado | Verificar se o pino GPIO está correto no código |
| LED não acende | Sem alimentação | Verificar se o SBC/MCU está ligado |
| LED muito fraco | Resistor demasiado alto | Testar com resistor menor (220Ω) |
| LED queimou | Sem resistor | **Nunca** ligar LED sem resistor em série |
| LED pisca sozinho | GPIO em floating | Usar `GPIO.setup(pin, GPIO.OUT, initial=GPIO.LOW)` |
| Código dá erro de permissão | GPIO não acessível | Correr com `sudo` ou configurar `/dev/gpiomem` |
