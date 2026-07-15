# Como Adicionar um Novo Atuador — Tutorial Passo-a-Passo

## Passo 1: Montar o Circuito na Breadboard

1. Consultar o guia do circuito em `docs/hardware/circuits/`
2. Ligar o atuador ao GPIO (com transistor se necessário para cargas > 3.3V)
3. Verificar a polaridade (LED, buzzer ativo)

**Exemplo (LED):**
- GPIO 18 → Resistor 220Ω → Ânodo LED → Cátodo LED → GND

## Passo 2: Criar Pasta do Recurso na API

```bash
mkdir -p /var/www/html/data/resources/led1/
```

Criar `config.txt`:
```
tipo=atuador
pino_gpio=18
comandos=LED:ON,LED:OFF
```

Criar `state.txt`:
```
OFF
```

Criar `commands.txt` (vazio — usado para comandos pendentes):
```

```

## Passo 3: Adicionar ao config.py

```python
# config.py
ATUADORES = {
    "led1": {
        "id": "led1",
        "tipo": "led",
        "pino": 18,
        "api_url": "http://server/api/commands.php",
        "intervalo": 1,  # polling de 1s
        "token": "meu_token"
    },
    "buzzer1": {
        "id": "buzzer1",
        "tipo": "buzzer",
        "pino": 22,
        "api_url": "http://server/api/commands.php",
        "intervalo": 1,
        "token": "meu_token"
    },
}
```

## Passo 4: Instanciar e Processar Comandos no main.py

```python
# main.py
import time
import requests
from config import ATUADORES
from lib.devices.led import LED
from lib.devices.buzzer import Buzzer

# Instanciar atuadores
atuadores = {}
for id, cfg in ATUADORES.items():
    if cfg["tipo"] == "led":
        atuadores[id] = LED(pin=cfg["pino"])
    elif cfg["tipo"] == "buzzer":
        atuadores[id] = Buzzer(pin=cfg["pino"])

# Loop de polling de comandos
while True:
    for id, atuador in atuadores.items():
        cfg = ATUADORES[id]

        # Ler comando pendente da API
        r = requests.get(f"{cfg['api_url']}?id={id}")
        if r.status_code == 200 and r.text.strip():
            comando = r.text.strip()
            print(f"Comando recebido para {id}: {comando}")

            # Executar comando
            if comando == "LED:ON":
                atuador.on()
            elif comando == "LED:OFF":
                atuador.off()
            elif comando == "BEEP":
                atuador.beep()

            # Limpar comando
            requests.post("http://server/api/clear_command.php",
                          json={"id": id})

    time.sleep(1)
```

## Passo 5: Enviar Comando pelo Dashboard

No dashboard (JavaScript):

```javascript
// dashboard.html
async function enviarComando(id, comando) {
    const response = await fetch('/api/command.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: id, comando: comando})
    });
    const data = await response.json();
    console.log(data);
}

// Botão no HTML
document.getElementById('btnLigarLED').onclick = () => {
    enviarComando('led1', 'LED:ON');
};
document.getElementById('btnDesligarLED').onclick = () => {
    enviarComando('led1', 'LED:OFF');
};
```

**HTML do botão:**
```html
<button onclick="enviarComando('led1', 'LED:ON')">🔴 Ligar LED</button>
<button onclick="enviarComando('led1', 'LED:OFF')">⚫ Desligar LED</button>
<div id="estado-led">Estado: OFF</div>

<script>
    // Atualizar estado a cada 2s
    setInterval(async () => {
        const r = await fetch('/api/state.php?id=led1');
        const estado = await r.text();
        document.getElementById('estado-led').innerText = `Estado: ${estado}`;
    }, 2000);
</script>
```

## Passo 6: Processar no MCU (se o atuador estiver no MCU)

O SBC reencaminha o comando via Serial:

```python
# No SBC - reencaminhar comando para MCU
import serial
ser = serial.Serial('/dev/ttyUSB0', 9600)

# Quando comando é recebido da API:
ser.write((comando + "\n").encode())
```

**Código C no MCU:**
```c
void loop() {
    if (Serial.available()) {
        String cmd = Serial.readStringUntil('\n');
        if (cmd == "LED:ON") digitalWrite(LED_PIN, HIGH);
        else if (cmd == "LED:OFF") digitalWrite(LED_PIN, LOW);
    }
}
```

## Passo 7: Verificar

1. Abrir dashboard
2. Clicar no botão do atuador
3. Verificar se o atuador (LED, buzzer, etc.) responde
4. Verificar que o `commands.txt` foi limpo após execução

## Passo 8: Testar com curl

```bash
# Enviar comando
curl -X POST http://server/api/command.php \
  -H "Content-Type: application/json" \
  -d '{"id":"led1","comando":"LED:ON"}'

# Verificar comando pendente
curl http://server/api/commands.php?id=led1

# Verificar estado
curl http://server/api/state.php?id=led1
```
