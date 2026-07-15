# Arquitetura do Sistema IoT

## Diagrama de Arquitetura

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 550" font-family="Arial, sans-serif">
  <defs>
    <marker id="arrowHttp" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto">
      <path d="M 0 0 L 10 5 L 0 10 z" fill="#2563eb"/>
    </marker>
    <marker id="arrowPush" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto">
      <path d="M 0 0 L 10 5 L 0 10 z" fill="#dc2626"/>
    </marker>
    <marker id="arrowPoll" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto">
      <path d="M 0 0 L 10 5 L 0 10 z" fill="#ca8a04"/>
    </marker>
    <marker id="arrowSerial" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="6" markerHeight="6" orient="auto">
      <path d="M 0 0 L 10 5 L 0 10 z" fill="#059669"/>
    </marker>
    <linearGradient id="boxGrad" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1"/>
      <stop offset="100%" style="stop-color:#f0f0f0;stop-opacity:1"/>
    </linearGradient>
  </defs>

  <!-- Fundo -->
  <rect width="800" height="550" fill="#f8fafc" rx="8"/>

  <!-- Router (topo centro) -->
  <rect x="300" y="20" width="200" height="70" rx="10" fill="#e0e7ff" stroke="#4f46e5" stroke-width="2"/>
  <text x="400" y="50" text-anchor="middle" font-weight="bold" font-size="14" fill="#1e1b4b">Router / Internet</text>
  <text x="400" y="70" text-anchor="middle" font-size="11" fill="#4338ca">WiFi 2.4GHz · HTTP · MQTT</text>

  <!-- Servidor Web (topo esquerda) -->
  <rect x="30" y="130" width="200" height="90" rx="10" fill="#dbeafe" stroke="#2563eb" stroke-width="2"/>
  <text x="130" y="158" text-anchor="middle" font-weight="bold" font-size="14" fill="#1e3a5f">Servidor Web</text>
  <text x="130" y="176" text-anchor="middle" font-size="11" fill="#2563eb">Apache + PHP 8.x</text>
  <text x="130" y="194" text-anchor="middle" font-size="11" fill="#2563eb">API REST · Dashboard</text>
  <text x="130" y="212" text-anchor="middle" font-size="11" fill="#475569">Ficheiros TXT</text>

  <!-- SBC (meio) -->
  <rect x="300" y="180" width="200" height="90" rx="10" fill="#fef3c7" stroke="#ca8a04" stroke-width="2"/>
  <text x="400" y="208" text-anchor="middle" font-weight="bold" font-size="14" fill="#713f12">SBC</text>
  <text x="400" y="226" text-anchor="middle" font-size="11" fill="#a16207">Raspberry Pi / Orange Pi</text>
  <text x="400" y="244" text-anchor="middle" font-size="11" fill="#ca8a04">Python · polling HTTP</text>
  <text x="400" y="262" text-anchor="middle" font-size="11" fill="#475569">GPIO · UART Serial</text>

  <!-- MCU (meio baixo) -->
  <rect x="300" y="340" width="200" height="90" rx="10" fill="#fce4ec" stroke="#dc2626" stroke-width="2"/>
  <text x="400" y="368" text-anchor="middle" font-weight="bold" font-size="14" fill="#7f1d1d">MCU</text>
  <text x="400" y="386" text-anchor="middle" font-size="11" fill="#dc2626">ESP32 / Arduino MKR1000</text>
  <text x="400" y="404" text-anchor="middle" font-size="11" fill="#dc2626">C/C++ · GPIO · ADC · PWM</text>
  <text x="400" y="422" text-anchor="middle" font-size="11" fill="#475569">WiFi · HTTP Client</text>

  <!-- Webcam (direita) -->
  <rect x="580" y="250" width="180" height="80" rx="10" fill="#f3e8ff" stroke="#9333ea" stroke-width="2"/>
  <text x="670" y="278" text-anchor="middle" font-weight="bold" font-size="14" fill="#4c1d95">Webcam IP</text>
  <text x="670" y="296" text-anchor="middle" font-size="11" fill="#9333ea">Captura de imagem</text>
  <text x="670" y="314" text-anchor="middle" font-size="11" fill="#475569">Envio via HTTP multipart</text>

  <!-- Setas: Router ↔ Servidor Web -->
  <line x1="300" y1="55" x2="230" y2="130" stroke="#2563eb" stroke-width="2" marker-end="url(#arrowHttp)"/>
  <line x1="230" y1="130" x2="300" y2="55" stroke="#2563eb" stroke-width="2" marker-end="url(#arrowHttp)"/>
  <text x="240" y="100" text-anchor="middle" font-size="10" fill="#2563eb" transform="rotate(-45,240,100)">HTTP (pedidos)</text>

  <!-- Setas: Router ↔ SBC -->
  <line x1="400" y1="90" x2="400" y2="180" stroke="#ca8a04" stroke-width="2" stroke-dasharray="5,3" marker-end="url(#arrowPoll)"/>
  <text x="415" y="140" font-size="10" fill="#ca8a04">Polling HTTP (GET log/state)</text>

  <!-- Setas: SBC → Servidor Web (HTTP POST) -->
  <line x1="300" y1="210" x2="230" y2="190" stroke="#dc2626" stroke-width="2" marker-end="url(#arrowPush)"/>
  <text x="250" y="205" text-anchor="end" font-size="10" fill="#dc2626">POST /api/update</text>

  <!-- Setas: Router ↔ MCU -->
  <line x1="400" y1="340" x2="400" y2="430" stroke="none"/>
  <line x1="400" y1="340" x2="550" y2="340" stroke="#2563eb" stroke-width="1.5" stroke-dasharray="4,3"/>
  <line x1="550" y1="340" x2="550" y2="400" stroke="#2563eb" stroke-width="1.5" stroke-dasharray="4,3"/>
  <line x1="550" y1="400" x2="580" y2="400" stroke="#2563eb" stroke-width="1.5" stroke-dasharray="4,3"/>

  <!-- Setas: Webcam → Servidor -->
  <line x1="580" y1="290" x2="230" y2="200" stroke="#9333ea" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="400" y="285" text-anchor="middle" font-size="10" fill="#9333ea">Imagem (POST multipart)</text>

  <!-- Serial SBC → MCU -->
  <rect x="290" y="275" width="220" height="55" rx="8" fill="#ecfdf5" stroke="#059669" stroke-width="1.5"/>
  <text x="400" y="295" text-anchor="middle" font-weight="bold" font-size="11" fill="#065f46">Comunicação Serial (UART/USB)</text>
  <text x="400" y="312" text-anchor="middle" font-size="10" fill="#047857">Comandos: SBC → MCU</text>
  <text x="400" y="326" text-anchor="middle" font-size="10" fill="#047857">Leituras: MCU → SBC</text>

  <!-- Legenda -->
  <rect x="30" y="480" width="740" height="60" rx="8" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="1"/>
  <text x="50" y="498" font-weight="bold" font-size="12" fill="#475569">Legenda:</text>
  <line x1="50" y1="515" x2="90" y2="515" stroke="#2563eb" stroke-width="2"/>
  <text x="95" y="520" font-size="10" fill="#475569">HTTP</text>
  <line x1="140" y1="515" x2="180" y2="515" stroke="#dc2626" stroke-width="2"/>
  <text x="185" y="520" font-size="10" fill="#475569">Push (POST)</text>
  <line x1="260" y1="515" x2="300" y2="515" stroke="#ca8a04" stroke-width="2" stroke-dasharray="5,3"/>
  <text x="305" y="520" font-size="10" fill="#475569">Polling (GET)</text>
  <line x1="390" y1="515" x2="430" y2="515" stroke="#059669" stroke-width="2"/>
  <text x="435" y="520" font-size="10" fill="#475569">UART Serial</text>
  <line x1="520" y1="515" x2="560" y2="515" stroke="#9333ea" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="565" y="520" font-size="10" fill="#475569">Multipart/Imagem</text>
</svg>
```

## Comunicações Obrigatórias (Secção 06)

| # | Origem | Destino | Protocolo | Descrição |
|---|--------|---------|-----------|-----------|
| 1 | Código Python (SBC) | Atuadores no MCU | UART Serial | SBC envia comando via serial → MCU atua (e.g., ligar LED) |
| 2 | Código Python (SBC) | Dashboard | HTTP POST | SBC envia leituras para API PHP que atualiza dashboard |
| 3 | Dashboard (botão) | Atuadores no MCU | HTTP + Serial | PHP escreve comando → SBC lê via polling → SBC envia serial para MCU |
| 4 | Dashboard (botão) | Código Python (SBC) | HTTP POST + Polling | PHP escreve ficheiro → SBC faz GET e deteta mudança |
| 5 | Código C (MCU) | Dashboard | HTTP POST | MCU envia leituras diretamente para API PHP |
| 6 | Código C (MCU) | Código Python (SBC) | UART Serial | MCU envia dados via serial → SBC processa |

### 1. Código Python (SBC) → Ações nos atuadores do MCU

O SBC envia comandos via UART Serial para o MCU:

```
SBC (Python)                    MCU (C)
     │                            │
     │─── "LED:ON\n" ──Serial──→  │  Liga LED
     │─── "LED:OFF\n" ──Serial──→ │  Desliga LED
     │─── "SERVO:90\n" ─Serial──→ │  Posiciona servo
     │                            │
```

**SBC (Python):**
```python
import serial
ser = serial.Serial('/dev/ttyUSB0', 9600)
ser.write(b"LED:ON\n")
```

**MCU (C):**
```c
void loop() {
    if (Serial.available()) {
        String cmd = Serial.readStringUntil('\n');
        if (cmd == "LED:ON") digitalWrite(LED_PIN, HIGH);
        if (cmd == "LED:OFF") digitalWrite(LED_PIN, LOW);
    }
}
```

### 2. Código Python (SBC) → Alterações no dashboard

O SBC envia leituras para a API PHP via HTTP POST:

```
SBC ── POST /api/update.php ──→ Servidor Web ──→ dashboard atualiza
     Body: {"recurso": "sensor1", "valor": 23.5}
```

```python
import requests
data = {"recurso": "temperatura", "valor": 23.5, "tipo": "temperatura"}
r = requests.post("http://server/api/update.php", json=data)
```

### 3. Dashboard → Ações nos atuadores do MCU

O utilizador clica no dashboard → PHP escreve comando → SBC faz polling → envia serial:

```
Dashboard ─→ PHP (commands.txt) ─→ SBC (GET polling) ─→ Serial ─→ MCU
```

**PHP (comando escrito):**
```php
file_put_contents("data/resources/atuador1/commands.txt", "LED:ON");
```

**SBC (Python) — polling:**
```python
while True:
    r = requests.get("http://server/api/commands.php?id=atuador1")
    cmd = r.text.strip()
    if cmd:
        ser.write((cmd + "\n").encode())
        requests.post("http://server/api/clear_command.php", json={"id": "atuador1"})
    time.sleep(1)
```

### 4. Dashboard → Código Python (SBC)

O dashboard envia um evento que o SBC lê via polling:

```
Dashboard → PHP (state.txt) → SBC (GET /api/state.php) → Python executa ação
```

### 5. Código C (MCU) → Alterações no dashboard

O MCU (ESP32/Arduino com WiFi) envia diretamente para a API:

```c
#include <WiFi.h>
#include <HTTPClient.h>

void loop() {
    float temp = readTemperature();
    HTTPClient http;
    http.begin("http://server/api/update.php");
    http.addHeader("Content-Type", "application/json");
    String body = "{\"recurso\":\"temp1\",\"valor\":" + String(temp) + "}";
    http.POST(body);
    http.end();
    delay(5000);
}
```

### 6. Código C (MCU) → Código Python (SBC)

MCU envia dados via Serial para o SBC:

```
MCU ── Serial ──→ SBC
     "TEMP:23.5\n"
```

**MCU (C):**
```c
void loop() {
    Serial.print("TEMP:");
    Serial.println(temperature);
    delay(2000);
}
```

**SBC (Python):**
```python
import serial
ser = serial.Serial('/dev/ttyUSB0', 9600)
while True:
    linha = ser.readline().decode().strip()
    if linha.startswith("TEMP:"):
        valor = float(linha.split(":")[1])
        requests.post("http://server/api/update.php",
                      json={"recurso": "temp1", "valor": valor})
```

---

## Estrutura de Dados (Ficheiros TXT)

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 700 350" font-family="Consolas, monospace">
  <rect width="700" height="350" fill="#f8fafc" rx="8"/>

  <!-- data/ -->
  <rect x="40" y="20" width="60" height="24" rx="4" fill="#fef3c7" stroke="#ca8a04" stroke-width="1.5"/>
  <text x="70" y="36" text-anchor="middle" font-size="12" fill="#92400e" font-weight="bold">📁 data</text>

  <!-- data/resources/ -->
  <rect x="130" y="20" width="90" height="24" rx="4" fill="#fef3c7" stroke="#ca8a04" stroke-width="1.5"/>
  <text x="175" y="36" text-anchor="middle" font-size="12" fill="#92400e" font-weight="bold">📁 resources</text>

  <!-- data/resources/{id}/ (x5) -->
  <rect x="250" y="20" width="100" height="24" rx="4" fill="#fef3c7" stroke="#ca8a04" stroke-width="1.5"/>
  <text x="300" y="36" text-anchor="middle" font-size="12" fill="#92400e" font-weight="bold">📁 {id}/</text>

  <!-- Ficheiros -->
  <rect x="250" y="65" width="100" height="20" rx="3" fill="#e0f2fe" stroke="#2563eb" stroke-width="1"/>
  <text x="300" y="80" text-anchor="middle" font-size="10" fill="#1e3a5f">📄 config.txt</text>

  <rect x="250" y="95" width="100" height="20" rx="3" fill="#e0f2fe" stroke="#2563eb" stroke-width="1"/>
  <text x="300" y="110" text-anchor="middle" font-size="10" fill="#1e3a5f">📄 log.txt</text>

  <rect x="250" y="125" width="100" height="20" rx="3" fill="#e0f2fe" stroke="#2563eb" stroke-width="1"/>
  <text x="300" y="140" text-anchor="middle" font-size="10" fill="#1e3a5f">📄 state.txt</text>

  <rect x="250" y="155" width="100" height="20" rx="3" fill="#e0f2fe" stroke="#2563eb" stroke-width="1"/>
  <text x="300" y="170" text-anchor="middle" font-size="10" fill="#1e3a5f">📄 commands.txt</text>

  <rect x="250" y="185" width="100" height="20" rx="3" fill="#e0f2fe" stroke="#2563eb" stroke-width="1"/>
  <text x="300" y="200" text-anchor="middle" font-size="10" fill="#1e3a5f">📄 imagem.txt</text>

  <!-- Detalhes de cada ficheiro -->
  <rect x="400" y="20" width="270" height="210" rx="6" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="1"/>
  <text x="415" y="40" font-weight="bold" font-size="12" fill="#475569">Conteúdo de cada ficheiro:</text>

  <text x="415" y="62" font-size="10" fill="#2563eb">config.txt</text>
  <text x="415" y="76" font-size="10" fill="#475569">  tipo=sensor</text>
  <text x="415" y="90" font-size="10" fill="#475569">  pino_gpio=4</text>
  <text x="415" y="104" font-size="10" fill="#475569">  intervalo=5</text>

  <text x="415" y="124" font-size="10" fill="#059669">log.txt</text>
  <text x="415" y="138" font-size="10" fill="#475569">  2026-07-15 10:00:00|23.5</text>
  <text x="415" y="152" font-size="10" fill="#475569">  2026-07-15 10:00:05|23.6</text>

  <text x="415" y="172" font-size="10" fill="#ca8a04">state.txt</text>
  <text x="415" y="186" font-size="10" fill="#475569">  23.5 (último valor conhecido)</text>

  <text x="415" y="206" font-size="10" fill="#dc2626">commands.txt</text>
  <text x="415" y="220" font-size="10" fill="#475569">  LED:ON (comando pendente)</text>

  <!-- Tabela de equipamentos -->
  <text x="40" y="270" font-weight="bold" font-size="14" fill="#1e293b">Tabela de Equipamentos e Portas</text>

  <rect x="40" y="280" width="620" height="18" fill="#e2e8f0"/>
  <text x="50" y="294" font-weight="bold" font-size="11" fill="#475569">Equipamento</text>
  <text x="180" y="294" font-weight="bold" font-size="11" fill="#475569">Protocolo</text>
  <text x="320" y="294" font-weight="bold" font-size="11" fill="#475569">Porta</text>
  <text x="440" y="294" font-weight="bold" font-size="11" fill="#475569">Função</text>

  <rect x="40" y="298" width="620" height="16" fill="white"/>
  <text x="50" y="310" font-size="11" fill="#1e293b">Servidor Web</text>
  <text x="180" y="310" font-size="11" fill="#1e293b">HTTP</text>
  <text x="320" y="310" font-size="11" fill="#1e293b">80/443</text>
  <text x="440" y="310" font-size="11" fill="#1e293b">API REST, Dashboard</text>

  <rect x="40" y="314" width="620" height="16" fill="#f8fafc"/>
  <text x="50" y="326" font-size="11" fill="#1e293b">SBC (RPi)</text>
  <text x="180" y="326" font-size="11" fill="#1e293b">HTTP, UART</text>
  <text x="320" y="326" font-size="11" fill="#1e293b">GPIO, USB</text>
  <text x="440" y="326" font-size="11" fill="#1e293b">Polling, Serial, GPIO</text>

  <rect x="40" y="330" width="620" height="16" fill="white"/>
  <text x="50" y="342" font-size="11" fill="#1e293b">MCU (ESP32)</text>
  <text x="180" y="342" font-size="11" fill="#1e293b">HTTP, UART</text>
  <text x="320" y="342" font-size="11" fill="#1e293b">WiFi, GPIO</text>
  <text x="440" y="342" font-size="11" fill="#1e293b">Sensores, Atuadores</text>
</svg>
```

## Resumo da Arquitetura

1. **Servidor Web** corre Apache + PHP. Aloja a API REST, o dashboard web, e os ficheiros TXT com dados.
2. **SBC** (Single Board Computer — Raspberry Pi, Orange Pi) corre Python. Lê sensores locais, comunica com MCU via Serial, e faz polling à API.
3. **MCU** (Microcontrolador — ESP32, Arduino MKR1000) corre C/C++. Controla atuadores e lê sensores em tempo real.
4. **Webcam IP** captura imagens e envia para o servidor via HTTP multipart.
5. **Router** liga todos os dispositivos em rede WiFi 2.4 GHz.

Os dados são armazenados em ficheiros de texto simples (`config.txt`, `log.txt`, `state.txt`, `commands.txt`, `imagem.txt`) dentro de `data/resources/{id}/`.
