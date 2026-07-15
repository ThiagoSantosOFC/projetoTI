# Guia: Servo Motor SG90

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

  <!-- Servo -->
  <rect x="220" y="90" width="120" height="70" rx="6" fill="#fce7f3" stroke="#ec4899" stroke-width="2"/>
  <text x="280" y="120" text-anchor="middle" font-size="12" font-weight="bold" fill="#9d174d">SG90</text>
  <text x="280" y="138" text-anchor="middle" font-size="9" fill="#ec4899">Servo Motor</text>

  <!-- Servo pins -->
  <rect x="230" y="170" width="22" height="12" rx="2" fill="#1e293b"/>
  <text x="241" y="179" text-anchor="middle" font-size="7" fill="white">GND</text>
  <rect x="262" y="170" width="22" height="12" rx="2" fill="#dc2626"/>
  <text x="273" y="179" text-anchor="middle" font-size="7" fill="white">VCC</text>
  <rect x="294" y="170" width="22" height="12" rx="2" fill="#f97316"/>
  <text x="305" y="179" text-anchor="middle" font-size="7" fill="white">SIG</text>

  <!-- Pin labels -->
  <text x="241" y="200" text-anchor="middle" font-size="8" fill="#1e293b">GND</text>
  <text x="273" y="200" text-anchor="middle" font-size="8" fill="#dc2626">5V</text>
  <text x="305" y="200" text-anchor="middle" font-size="8" fill="#ea580c">Sinal</text>

  <!-- GND -->
  <line x1="241" y1="215" x2="380" y2="280" stroke="#1e293b" stroke-width="3"/>
  <text x="390" y="285" font-size="9" fill="#1e293b">GND</text>

  <!-- VCC 5V -->
  <line x1="273" y1="215" x2="160" y2="250" stroke="#dc2626" stroke-width="3"/>
  <text x="145" y="255" text-anchor="end" font-size="9" fill="#dc2626">5V</text>

  <!-- Signal -->
  <line x1="305" y1="215" x2="305" y2="240" stroke="#f97316" stroke-width="3"/>
  <line x1="305" y1="240" x2="160" y2="240" stroke="#f97316" stroke-width="3"/>
  <text x="145" y="245" text-anchor="end" font-size="9" fill="#ea580c">GPIO PWM</text>

  <!-- Note -->
  <rect x="100" y="300" width="400" height="35" rx="4" fill="#fff7ed" stroke="#fdba74" stroke-width="1"/>
  <text x="110" y="318" font-size="9" fill="#9a3412">⚠ Usar GPIO com PWM! Ex: GPIO 18, 13, 12. O servo SG90 requer 5V e sinal PWM (50Hz).</text>
  <text x="110" y="332" font-size="9" fill="#9a3412">Não alimentar o servo diretamente do GPIO do RPi — usar alimentação externa 5V.</text>
</svg>
```

## Código Python

```python
# main.py - SBC (Raspberry Pi)
from lib.devices.servo import Servo
import time

servo = Servo(pin=18)  # GPIO 18 (PWM)

servo.angle(0)      # 0 graus
time.sleep(1)
servo.angle(90)     # 90 graus
time.sleep(1)
servo.angle(180)    # 180 graus
time.sleep(1)

servo.cleanup()
```

**Ficheiro `lib/devices/servo.py`:**
```python
import RPi.GPIO as GPIO

class Servo:
    def __init__(self, pin):
        self.pin = pin
        GPIO.setmode(GPIO.BCM)
        GPIO.setup(pin, GPIO.OUT)
        self.pwm = GPIO.PWM(pin, 50)  # 50 Hz
        self.pwm.start(0)

    def angle(self, degrees):
        # 0° = 2.5% duty, 90° = 7.5%, 180° = 12.5%
        duty = 2.5 + (degrees / 180.0) * 10
        self.pwm.ChangeDutyCycle(duty)
        time.sleep(0.3)

    def cleanup(self):
        self.pwm.stop()
        GPIO.cleanup()
```

## Código C (ESP32)

```c
// main.c - ESP32
#include <ESP32Servo.h>

Servo servo;
#define SERVO_PIN 18

void setup() {
    servo.attach(SERVO_PIN);
}

void loop() {
    servo.write(0);     // 0 graus
    delay(1000);
    servo.write(90);    // 90 graus
    delay(1000);
    servo.write(180);   // 180 graus
    delay(1000);
}
```

## Troubleshooting

| Problema | Causa | Solução |
|----------|-------|---------|
| Servo não mexe | Alimentação insuficiente | Usar 5V externo (não do GPIO do RPi) |
| Servo treme | PWM errado | Verificar frequência 50Hz, duty cycle correto |
| Servo não vai a 0° | Limite mecânico | Ajustar duty cycle para 2.5% |
| Servo não vai a 180° | Limite mecânico | Ajustar duty cycle para 12.5% |
| RPi reinicia | Pico de corrente | Condensador 100µF entre VCC e GND do servo |
