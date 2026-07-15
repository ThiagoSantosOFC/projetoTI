# Guia: Módulo Relé

## Montagem na Breadboard

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 650 380" font-family="Arial, sans-serif">
  <rect width="650" height="380" fill="#f8fafc" rx="8"/>

  <!-- Breadboard -->
  <rect x="80" y="20" width="490" height="320" rx="6" fill="#fafafa" stroke="#cbd5e1" stroke-width="2"/>

  <!-- Power Rails -->
  <rect x="88" y="28" width="220" height="28" rx="3" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <text x="198" y="47" text-anchor="middle" font-size="9" fill="#dc2626">🔴 VCC (5V)</text>
  <rect x="340" y="28" width="220" height="28" rx="3" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="450" y="47" text-anchor="middle" font-size="9" fill="#16a34a">🟢 GND</text>

  <!-- Relay Module -->
  <rect x="210" y="90" width="140" height="90" rx="6" fill="#e0f2fe" stroke="#3b82f6" stroke-width="2"/>
  <text x="280" y="120" text-anchor="middle" font-size="13" font-weight="bold" fill="#1e3a5f">RELÉ</text>
  <text x="280" y="140" text-anchor="middle" font-size="9" fill="#3b82f6">Módulo 1 canal</text>

  <!-- LED indicator -->
  <circle cx="240" cy="100" r="6" fill="#ef4444"/>

  <!-- Screw terminals -->
  <rect x="220" y="155" width="18" height="14" rx="2" fill="#94a3b8"/>
  <text x="229" y="166" text-anchor="middle" font-size="7" fill="white">NC</text>
  <rect x="246" y="155" width="18" height="14" rx="2" fill="#94a3b8"/>
  <text x="255" y="166" text-anchor="middle" font-size="7" fill="white">COM</text>
  <rect x="272" y="155" width="18" height="14" rx="2" fill="#94a3b8"/>
  <text x="281" y="166" text-anchor="middle" font-size="7" fill="white">NO</text>

  <!-- Relay pins (3-pin header) -->
  <rect x="310" y="195" width="22" height="12" rx="2" fill="#1e293b"/>
  <text x="321" y="204" text-anchor="middle" font-size="7" fill="white">GND</text>
  <rect x="340" y="195" width="22" height="12" rx="2" fill="#f97316"/>
  <text x="351" y="204" text-anchor="middle" font-size="7" fill="white">IN</text>
  <rect x="370" y="195" width="22" height="12" rx="2" fill="#dc2626"/>
  <text x="381" y="204" text-anchor="middle" font-size="7" fill="white">VCC</text>

  <!-- GND -->
  <line x1="321" y1="220" x2="400" y2="280" stroke="#1e293b" stroke-width="3"/>
  <text x="410" y="285" font-size="9" fill="#1e293b">GND</text>

  <!-- VCC 5V -->
  <line x1="381" y1="220" x2="160" y2="250" stroke="#dc2626" stroke-width="3"/>
  <text x="145" y="255" text-anchor="end" font-size="9" fill="#dc2626">5V</text>

  <!-- IN (Signal) -->
  <line x1="351" y1="220" x2="351" y2="240" stroke="#f97316" stroke-width="3"/>
  <line x1="351" y1="240" x2="160" y2="240" stroke="#f97316" stroke-width="3"/>
  <text x="145" y="245" text-anchor="end" font-size="9" fill="#ea580c">GPIO 22</text>

  <!-- Carga externa -->
  <rect x="120" y="280" width="160" height="30" rx="4" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="1.5" stroke-dasharray="4,2"/>
  <text x="200" y="300" text-anchor="middle" font-size="9" fill="#475569">Carga externa (lâmpada 230V)</text>

  <!-- Note -->
  <rect x="100" y="315" width="460" height="35" rx="4" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <text x="110" y="332" font-size="9" fill="#991b1b">⚠ CUIDADO: Carga externa usa tensão da rede! Manter isolamento. Usar relé para separar circuitos.</text>
  <text x="110" y="346" font-size="9" fill="#991b1b">Ligar NC/COM/NO aos terminais do aparelho. IN=HIGH liga relé. Estado inverso conforme módulo (HIGH ou LOW).</text>
</svg>
```

## Código Python

```python
# main.py - SBC (Raspberry Pi)
from lib.devices.relay import Relay
import time

relay = Relay(pin=22)  # GPIO 22

relay.on()    # Liga relé (fecha circuito)
time.sleep(3)
relay.off()   # Desliga relé

relay.cleanup()
```

**Ficheiro `lib/devices/relay.py`:**
```python
import RPi.GPIO as GPIO

class Relay:
    def __init__(self, pin):
        self.pin = pin
        GPIO.setmode(GPIO.BCM)
        GPIO.setup(pin, GPIO.OUT)
        GPIO.output(pin, GPIO.LOW)  # Relé desligado

    def on(self):
        GPIO.output(self.pin, GPIO.HIGH)

    def off(self):
        GPIO.output(self.pin, GPIO.LOW)

    def cleanup(self):
        GPIO.cleanup()
```

## Código C (ESP32)

```c
// main.c - ESP32
#define RELAY_PIN 22

void setup() {
    pinMode(RELAY_PIN, OUTPUT);
    digitalWrite(RELAY_PIN, LOW);
}

void loop() {
    digitalWrite(RELAY_PIN, HIGH);  // Liga relé
    delay(3000);
    digitalWrite(RELAY_PIN, LOW);   // Desliga relé
    delay(3000);
}
```

## Troubleshooting

| Problema | Causa | Solução |
|----------|-------|---------|
| Relé não ativa | Sinal GPIO fraco | Verificar VCC/GND do módulo |
| Relé não desativa | Sinal invertido | Alguns módulos usam LOW para ativar — testar |
| LED no módulo não acende | Alimentação | Verificar 5V ao módulo |
| Carga não liga | Cabos NO/COM trocados | Ligar carga entre COM e NO (Normalmente Aberto) |
| Carga não desliga | Usar NC em vez de NO | Ligar entre COM e NO para controlo normal |
