# Guia: Sensor HC-SR04 (UltraSom)

## Montagem na Breadboard

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 650 450" font-family="Arial, sans-serif">
  <rect width="650" height="450" fill="#f8fafc" rx="8"/>

  <!-- Breadboard -->
  <rect x="100" y="20" width="450" height="390" rx="6" fill="#fafafa" stroke="#cbd5e1" stroke-width="2"/>

  <!-- Power Rails -->
  <rect x="108" y="28" width="200" height="28" rx="3" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <text x="208" y="47" text-anchor="middle" font-size="9" fill="#dc2626">🔴 VCC (5V)</text>

  <rect x="330" y="28" width="210" height="28" rx="3" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="435" y="47" text-anchor="middle" font-size="9" fill="#16a34a">🟢 GND</text>

  <!-- HC-SR04 Sensor -->
  <rect x="220" y="80" width="120" height="60" rx="4" fill="#f1f5f9" stroke="#64748b" stroke-width="2"/>
  <text x="280" y="105" text-anchor="middle" font-size="11" font-weight="bold" fill="#1e293b">HC-SR04</text>
  <text x="280" y="120" text-anchor="middle" font-size="8" fill="#64748b">UltraSom</text>
  <circle cx="240" cy="133" r="6" fill="#e2e8f0" stroke="#94a3b8" stroke-width="1"/>
  <circle cx="280" cy="133" r="6" fill="#e2e8f0" stroke="#94a3b8" stroke-width="1"/>
  <circle cx="320" cy="133" r="6" fill="#e2e8f0" stroke="#94a3b8" stroke-width="1"/>

  <!-- Pins -->
  <rect x="220" y="150" width="22" height="12" rx="2" fill="#fef2f2"/>
  <text x="231" y="159" text-anchor="middle" font-size="8" fill="#dc2626">VCC</text>

  <rect x="248" y="150" width="22" height="12" rx="2" fill="#fef2f2"/>
  <text x="259" y="159" text-anchor="middle" font-size="8" fill="#ca8a04">TRIG</text>

  <rect x="276" y="150" width="22" height="12" rx="2" fill="#fef2f2"/>
  <text x="287" y="159" text-anchor="middle" font-size="8" fill="#f97316">ECHO</text>

  <rect x="304" y="150" width="22" height="12" rx="2" fill="#fef2f2"/>
  <text x="315" y="159" text-anchor="middle" font-size="8" fill="#16a34a">GND</text>

  <!-- VCC to 5V -->
  <line x1="231" y1="175" x2="170" y2="220" stroke="#dc2626" stroke-width="3"/>
  <text x="155" y="225" text-anchor="end" font-size="9" fill="#dc2626">5V</text>

  <!-- TRIG -->
  <line x1="259" y1="175" x2="259" y2="210" stroke="#ca8a04" stroke-width="3"/>
  <line x1="259" y1="210" x2="170" y2="210" stroke="#ca8a04" stroke-width="3"/>
  <text x="155" y="215" text-anchor="end" font-size="9" fill="#ca8a04">GPIO 5</text>

  <!-- GND -->
  <line x1="315" y1="175" x2="380" y2="230" stroke="#1e293b" stroke-width="3"/>
  <text x="390" y="235" font-size="9" fill="#1e293b">GND</text>

  <!-- Voltage divider for ECHO -->
  <rect x="260" y="260" width="70" height="40" rx="4" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="295" y="275" text-anchor="middle" font-size="8" font-weight="bold" fill="#16a34a">Divisor</text>
  <text x="295" y="290" text-anchor="middle" font-size="8" fill="#16a34a">Tensão</text>

  <!-- Echo to voltage divider -->
  <line x1="287" y1="175" x2="287" y2="260" stroke="#f97316" stroke-width="3"/>

  <!-- R1 10k -->
  <rect x="270" y="310" width="20" height="10" rx="2" fill="#fef9c3" stroke="#ca8a04" stroke-width="1"/>
  <text x="280" y="318" text-anchor="middle" font-size="6" fill="#92400e">10k</text>

  <!-- R2 10k -->
  <rect x="270" y="345" width="20" height="10" rx="2" fill="#fef9c3" stroke="#ca8a04" stroke-width="1"/>
  <text x="280" y="353" text-anchor="middle" font-size="6" fill="#92400e">10k</text>

  <!-- Connections through divider -->
  <line x1="287" y1="300" x2="287" y2="310" stroke="#f97316" stroke-width="1.5"/>
  <line x1="280" y1="320" x2="280" y2="345" stroke="#f97316" stroke-width="1.5"/>
  <line x1="280" y1="355" x2="280" y2="380" stroke="#1e293b" stroke-width="1.5"/>

  <!-- Output to GPIO 18 (after divider) -->
  <line x1="290" y1="320" x2="340" y2="320" stroke="#f97316" stroke-width="2"/>
  <text x="350" y="325" font-size="9" fill="#ea580c">GPIO 18</text>

  <!-- Divider explanation -->
  <rect x="100" y="390" width="450" height="40" rx="4" fill="#fff7ed" stroke="#fdba74" stroke-width="1"/>
  <text x="110" y="408" font-size="9" fill="#9a3412">⚠ IMPORTANTE (ESP32 3.3V): ECHO (5V) → R1 10kΩ → GPIO → R2 10kΩ → GND</text>
  <text x="110" y="424" font-size="9" fill="#9a3412">O divisor reduz 5V para ~2.5V (seguro para GPIO 3.3V). No RPi 5V, ligar ECHO diretamente com resistor 1kΩ.</text>
</svg>
```

## Esquema Elétrico com Divisor de Tensão

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 300" font-family="Arial, sans-serif">
  <rect width="600" height="300" fill="#f8fafc" rx="8"/>

  <!-- HC-SR04 -->
  <rect x="30" y="30" width="120" height="80" rx="4" fill="#f1f5f9" stroke="#64748b" stroke-width="2"/>
  <text x="90" y="65" text-anchor="middle" font-size="12" font-weight="bold" fill="#1e293b">HC-SR04</text>

  <!-- Pins -->
  <circle cx="50" cy="100" r="4" fill="#dc2626"/><text x="58" y="104" font-size="9" fill="#1e293b">VCC</text>
  <circle cx="80" cy="100" r="4" fill="#ca8a04"/><text x="88" y="104" font-size="9" fill="#1e293b">TRIG</text>
  <circle cx="110" cy="100" r="4" fill="#f97316"/><text x="118" y="104" font-size="9" fill="#1e293b">ECHO</text>
  <circle cx="140" cy="100" r="4" fill="#16a34a"/><text x="148" y="104" font-size="9" fill="#1e293b">GND</text>

  <!-- 5V to VCC -->
  <line x1="30" y1="50" x2="10" y2="50" stroke="#dc2626" stroke-width="1.5"/>
  <line x1="10" y1="50" x2="10" y2="20" stroke="#dc2626" stroke-width="1.5"/>
  <text x="10" y="16" text-anchor="middle" font-size="9" fill="#dc2626">5V</text>

  <!-- TRIG to GPIO 5 -->
  <line x1="80" y1="100" x2="80" y2="150" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="80" y1="150" x2="200" y2="150" stroke="#ca8a04" stroke-width="1.5"/>
  <circle cx="200" cy="150" r="10" fill="#fef9c3" stroke="#ca8a04" stroke-width="2"/>
  <text x="200" y="154" text-anchor="middle" font-size="8" fill="#92400e">GPIO5</text>

  <!-- GND -->
  <line x1="140" y1="100" x2="140" y2="250" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="140" y1="250" x2="240" y2="250" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="210" y1="250" x2="270" y2="250" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="220" y1="258" x2="260" y2="258" stroke="#1e293b" stroke-width="1.5"/>
  <line x1="230" y1="266" x2="250" y2="266" stroke="#1e293b" stroke-width="1.5"/>
  <text x="240" y="280" text-anchor="middle" font-size="9" fill="#1e293b">GND</text>

  <!-- ECHO to voltage divider -->
  <line x1="110" y1="100" x2="340" y2="100" stroke="#f97316" stroke-width="1.5"/>

  <!-- R1 (10k) -->
  <rect x="310" y="110" width="24" height="12" rx="2" fill="#fef9c3" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="305" y1="116" x2="310" y2="116" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="305" y1="122" x2="310" y2="122" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="334" y1="116" x2="340" y2="116" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="334" y1="122" x2="340" y2="122" stroke="#ca8a04" stroke-width="1.5"/>
  <text x="322" y="145" text-anchor="middle" font-size="9" fill="#92400e">R1 10kΩ</text>

  <!-- Junction to GPIO -->
  <circle cx="340" cy="160" r="4" fill="#f97316"/>
  <line x1="340" y1="112" x2="340" y2="160" stroke="#f97316" stroke-width="1.5"/>
  <line x1="340" y1="160" x2="420" y2="160" stroke="#f97316" stroke-width="1.5"/>
  <circle cx="420" cy="160" r="10" fill="#fff7ed" stroke="#f97316" stroke-width="2"/>
  <text x="420" y="164" text-anchor="middle" font-size="8" fill="#ea580c">GPIO18</text>

  <!-- R2 (10k) to GND -->
  <rect x="328" y="175" width="24" height="12" rx="2" fill="#fef9c3" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="323" y1="181" x2="328" y2="181" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="323" y1="187" x2="328" y2="187" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="352" y1="181" x2="358" y2="181" stroke="#ca8a04" stroke-width="1.5"/>
  <line x1="352" y1="187" x2="358" y2="187" stroke="#ca8a04" stroke-width="1.5"/>
  <text x="340" y="208" text-anchor="middle" font-size="9" fill="#92400e">R2 10kΩ</text>

  <line x1="340" y1="187" x2="340" y2="250" stroke="#1e293b" stroke-width="1.5"/>

  <!-- Formula -->
  <rect x="410" y="80" width="170" height="60" rx="4" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="1"/>
  <text x="420" y="98" font-size="9" fill="#475569">Divisor de Tensão:</text>
  <text x="420" y="114" font-size="9" fill="#475569">Vout = Vin × R2/(R1+R2)</text>
  <text x="420" y="130" font-size="9" fill="#475569">Vout = 5 × 10/(10+10) = 2.5V</text>
</svg>
```

## Código Python

```python
# main.py - SBC (Raspberry Pi)
from lib.devices.hcsr04 import HCSR04
import time

sensor = HCSR04(trigger_pin=5, echo_pin=18)

while True:
    distancia = sensor.read()
    print(f"Distância: {distancia:.1f} cm")
    time.sleep(1)
```

**Ficheiro `lib/devices/hcsr04.py`:**
```python
import RPi.GPIO as GPIO
import time

class HCSR04:
    def __init__(self, trigger_pin, echo_pin):
        self.trigger = trigger_pin
        self.echo = echo_pin
        GPIO.setmode(GPIO.BCM)
        GPIO.setup(trigger, GPIO.OUT)
        GPIO.setup(echo, GPIO.IN)
        GPIO.output(trigger, False)
        time.sleep(0.5)

    def read(self):
        GPIO.output(self.trigger, True)
        time.sleep(0.00001)
        GPIO.output(self.trigger, False)

        while GPIO.input(self.echo) == 0:
            start = time.time()
        while GPIO.input(self.echo) == 1:
            end = time.time()

        duration = end - start
        distance = duration * 17150  # 34300/2
        return round(distance, 2)
```

## Código C (ESP32)

```c
// main.c - ESP32
#include <Arduino.h>

#define TRIG_PIN 5
#define ECHO_PIN 18

void setup() {
    Serial.begin(115200);
    pinMode(TRIG_PIN, OUTPUT);
    pinMode(ECHO_PIN, INPUT);
    digitalWrite(TRIG_PIN, LOW);
}

void loop() {
    digitalWrite(TRIG_PIN, HIGH);
    delayMicroseconds(10);
    digitalWrite(TRIG_PIN, LOW);

    long duration = pulseIn(ECHO_PIN, HIGH);
    float distance = duration * 0.034 / 2;

    Serial.printf("Distância: %.1f cm\n", distance);
    delay(1000);
}
```

## Troubleshooting

| Problema | Causa | Solução |
|----------|-------|---------|
| Distância = 0 sempre | Echo ligado a 5V no ESP32 | **Usar divisor de tensão!** 5V queima GPIO 3.3V |
| Distância errada | Trigger curto demais | Garantir pulse de 10µs no TRIG |
| Leituras inconsistentes | Objeto demasiado perto (<2cm) | Zona morta do sensor é ~2cm |
| Sem leitura | Echo nunca fica HIGH | Verificar ligações do sensor |
| Distância máxima | Sem obstáculo | Sensor deteta até 4m (espelho) |
| Ruído nas leituras | Múltiplas reflexões | Fazer média de 3 leituras |
