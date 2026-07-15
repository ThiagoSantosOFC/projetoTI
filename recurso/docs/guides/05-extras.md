# Extras — Como Implementar

## Extra 1: Pedidos Assíncronos (setInterval + fetch)

Para o dashboard atualizar automaticamente sem recarregar a página.

```javascript
// dashboard.html
// Atualizar estado dos recursos a cada 2 segundos
setInterval(async () => {
    const response = await fetch('/api/recursos.php');
    const data = await response.json();

    if (data.recursos) {
        data.recursos.forEach(recurso => {
            const el = document.getElementById(`valor-${recurso.id}`);
            if (el) el.innerText = recurso.valor;
        });
    }
}, 2000);

// Atualizar histórico de um sensor específico
setInterval(async () => {
    const r = await fetch('/api/log.php?id=temp1');
    const data = await r.json();
    if (data.log) {
        atualizarGrafico(data.log);
    }
}, 5000);

// Função para atualizar gráfico com Chart.js
function atualizarGrafico(logData) {
    const labels = logData.map(d => d.data.split(' ')[1]);
    const valores = logData.map(d => parseFloat(d.valor));

    if (window.tempChart) {
        window.tempChart.data.labels = labels;
        window.tempChart.data.datasets[0].data = valores;
        window.tempChart.update();
    }
}
```

## Extra 2: Cifra de Dados

### PHP (Servidor) — openssl_encrypt/decrypt

```php
<?php
// Cifrar dados
function cifrar_dados($data, $chave_secreta = "minha_chave_32_carateres!") {
    $iv = openssl_random_pseudo_bytes(16);
    $cifrado = openssl_encrypt($data, 'aes-256-cbc', $chave_secreta, 0, $iv);
    return base64_encode($iv . '::' . $cifrado);
}

// Decifrar dados
function decifrar_dados($data_cifrada, $chave_secreta = "minha_chave_32_carateres!") {
    $data = base64_decode($data_cifrada);
    $parts = explode('::', $data, 2);
    if (count($parts) != 2) return null;
    return openssl_decrypt($parts[1], 'aes-256-cbc', $chave_secreta, 0, $parts[0]);
}

// Exemplo de uso
$valor_cifrado = cifrar_dados("23.5");
echo $valor_cifrado;  // string cifrada em base64

$valor_original = decifrar_dados($valor_cifrado);
echo $valor_original;  // "23.5"
?>
```

### Python (SBC) — cryptography

```python
from cryptography.fernet import Fernet
import base64

# Gerar chave (uma vez, depois guardar)
chave = Fernet.generate_key()
print(chave.decode())  # Guardar esta chave

# Ou usar uma password fixa
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2
# ...

# Exemplo com Fernet
cipher = Fernet(chave)

def cifrar(valor):
    return cipher.encrypt(str(valor).encode()).decode()

def decifrar(token):
    return cipher.decrypt(token.encode()).decode()

# Uso
dados_cifrados = cifrar(23.5)
print(f"Cifrado: {dados_cifrados}")

dados_originais = decifrar(dados_cifrados)
print(f"Original: {dados_originais}")
```

## Extra 3: Auth Tokens

### Gerar Token Aleatório (PHP)

```php
<?php
function gerar_token($bytes = 32) {
    return bin2hex(random_bytes($bytes));
}

$token = gerar_token();  // Ex: "a1b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4"
?>
```

### Guardar em tokens.txt

Formato do ficheiro `tokens.txt`:
```
temp1:a1b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4:sensor
led1:b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4e5:atuador
sbc:c3d4e5f6a1b2c3d4e5f6a1b2c3d4e5f6:sbc
cam1:d4e5f6a1b2c3d4e5f6a1b2c3d4e5f6a1:webcam
```

### Validar no Middleware

```php
<?php
// token_middleware.php
function get_bearer_token() {
    $headers = getallheaders();
    $auth = $headers['Authorization'] ?? '';
    if (strpos($auth, 'Bearer ') === 0) {
        return substr($auth, 7);
    }
    return $auth ?: null;
}

function validar_token_arquivo($token) {
    if (!file_exists('tokens.txt')) return false;

    $linhas = file('tokens.txt', FILE_IGNORE_NEW_LINES);
    foreach ($linhas as $linha) {
        $partes = explode(':', trim($linha));
        if (count($partes) >= 2 && $partes[1] === $token) {
            return $partes[0];  // ID do recurso
        }
    }
    return false;
}
?>
```

## Extra 4: Gráficos com Chart.js

Adicionar Chart.js via CDN no dashboard:

```html
<!DOCTYPE html>
<html>
<head>
    <!-- Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; margin: 20px; }
        .card { border: 1px solid #ccc; padding: 15px; margin: 10px 0; border-radius: 8px; }
        canvas { max-width: 600px; max-height: 300px; }
    </style>
</head>
<body>
    <h1>Dashboard IoT</h1>
    <div class="card">
        <h2>Temperatura (temp1)</h2>
        <p>Valor atual: <span id="valor-temp1">--</span>°C</p>
        <canvas id="graficoTemp"></canvas>
    </div>

    <script>
        // Inicializar gráfico
        const ctx = document.getElementById('graficoTemp').getContext('2d');
        const tempChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Temperatura (°C)',
                    data: [],
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: false }
                }
            }
        });

        // Atualizar a cada 5 segundos
        setInterval(async () => {
            const r = await fetch('/api/log.php?id=temp1');
            const data = await r.json();

            if (data.log) {
                const labels = data.log.map(d => d.data.split(' ')[1]);
                const valores = data.log.map(d => parseFloat(d.valor));

                tempChart.data.labels = labels;
                tempChart.data.datasets[0].data = valores;
                tempChart.update();

                // Atualizar valor atual
                const ultimo = valores[valores.length - 1];
                document.getElementById('valor-temp1').innerText = ultimo;
            }
        }, 5000);
    </script>
</body>
</html>
```

## Extra 5: Bluetooth (PyBluez no SBC)

```bash
# Instalar PyBluez
sudo apt-get install bluez bluez-tools python3-bluez
pip install pybluez
```

```python
# SBC - Bluetooth Scanner
import bluetooth

def descobrir_dispositivos():
    print("A procurar dispositivos Bluetooth...")
    devices = bluetooth.discover_devices(duration=8, lookup_names=True)

    for addr, name in devices:
        print(f"Encontrado: {name} - {addr}")

    return devices

# Comunicação Bluetooth RFCOMM
def enviar_bluetooth(endereco, mensagem):
    sock = bluetooth.BluetoothSocket(bluetooth.RFCOMM)
    sock.connect((endereco, 1))  # Canal 1
    sock.send(mensagem)
    sock.close()

def receber_bluetooth(endereco):
    sock = bluetooth.BluetoothSocket(bluetooth.RFCOMM)
    sock.connect((endereco, 1))
    data = sock.recv(1024)
    sock.close()
    return data.decode()

# Uso
dispositivos = descobrir_dispositivos()
if dispositivos:
    enviar_bluetooth(dispositivos[0][0], "LED:ON")
```

## Extra 6: Cisco Packet Tracer — Sensores Virtuais

No Cisco Packet Tracer, é possível simular dispositivos IoT:

1. **Adicionar dispositivo:** Arrastar "IoT Device" para a área de trabalho
2. **Configurar sensor:** Em "Config" → "Attributes", definir:
   - `sensor_type`: temperature, humidity, motion, etc.
   - `unit`: celsius, percentage, etc.
   - `threshold`: valor de alerta
3. **Ligar ao servidor:** Em "Config" → "Connectivity":
   - HTTP Server IP
   - Porta: 80
   - Path: `/api/update.php`
4. **Simular leituras:** Clicar no sensor → "Attributes" → editar valor manualmente
5. **Usar "Programming"** para simular código Python/C:
   - Escrever scripts Python virtuais que interagem com o sensor simulado
6. **Dashboard:** Criar página web no servidor interno do Packet Tracer

**Exemplo de script Python no Packet Tracer:**
```python
from iot import Device
sensor = Device.get_device("temp1")
valor = sensor.get_value()
print(f"Temperatura: {valor}°C")

# Enviar para servidor HTTP
from requests import post
post("http://192.168.1.100/api/update.php",
     json={"recurso": "temp1", "valor": valor})
```
