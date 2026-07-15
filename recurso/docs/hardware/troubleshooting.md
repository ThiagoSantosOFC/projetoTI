# Guia de Troubleshooting

## LED não acende

| Causa | Verificação | Solução |
|-------|-------------|---------|
| Polaridade invertida | Perna longa = ânodo (+) | Inverter o LED |
| Resistor errado | Valor do resistor | Usar 220Ω–330Ω. Acima de 1kΩ o LED fica muito fraco |
| GPIO errado | Código vs breadboard | `print(f"GPIO {pin}")` e confirmar pino |
| Sem tensão | Multímetro no GPIO | Verificar alimentação do SBC/MCU |
| GPIO em baixo | Código não executou | Debug com `print()` antes do `led.on()` |
| LED queimado | Testar com pilha 3V | Substituir LED |

## Sensor não comunica

| Causa | Verificação | Solução |
|-------|-------------|---------|
| Wiring errado | Esquema do circuito | Rever pinos VCC/GND/DATA |
| Tensão errada | 3.3V vs 5V | DHT22: 3.3V. HC-SR04: 5V. Verificar datasheet |
| Pull-up em falta (DHT22) | Resistor 10kΩ entre VCC e DATA | Adicionar resistor de pull-up |
| Divisor de tensão (HC-SR04) | ECHO 5V → GPIO 3.3V | Usar divisor 10kΩ+10kΩ no ESP32 |
| Fio partido | Continuidade com multímetro | Substituir jumper |
| Sensor avariado | Testar com exemplo mínimo | Substituir sensor |

## Leituras erradas do sensor

| Problema | Causa | Solução |
|----------|-------|---------|
| DHT22: sempre NaN | Pull-up ou pinos trocados | Verificar ligações |
| DHT22: temperatura fixa | Sensor danificado ou intervalo <2s | Esperar 2s entre leituras |
| HC-SR04: 0 sempre | ECHO não conectado | Verificar pino ECHO |
| HC-SR04: medições erradas | Objeto <2cm ou >4m | Zona morta: 2cm. Máximo: 4m |
| LDR: valor fixo | Divisor de tensão errado | LDR + resistor 10kΩ em série |
| PIR: falsos positivos | Fonte de calor próxima | Afastar de aquecedores |

## WiFi não conecta

| Problema | Causa | Solução |
|----------|-------|---------|
| SSID errado | Nome da rede | Verificar maiúsculas/minúsculas |
| Password errada | Caracteres especiais | Testar com rede móvel (hotspot) |
| Alcance | Sinal fraco | Aproximar do router |
| Firewall | Porta bloqueada | Abrir porta 80/443 no router |
| ESP32: WiFi desliga | Power saving | `WiFi.setSleep(false)` |

## API não responde

| Problema | Causa | Solução |
|----------|-------|---------|
| URL errado | Caminho do endpoint | `curl http://server/api/recursos.php` |
| Servidor parado | Apache não está a correr | `sudo systemctl status apache2` |
| Porta errada | 80 vs 8080 | Verificar configuração do Apache |
| Permissões | PHP não escreve ficheiros | `chmod 777 data/ -R` |
| JSON inválido | Formato do body | Usar `json.dumps()` no Python |
| CORS | Browser bloqueia | Adicionar `header("Access-Control-Allow-Origin: *");` no PHP |
| Sessão expirada | Login necessário | Fazer login primeiro |

## Códigos de Erro Comuns

| Código | Significado | Causa |
|--------|-------------|-------|
| `200` | OK | Tudo funciona |
| `400` | Bad Request | JSON mal formatado, campos em falta |
| `401` | Unauthorized | Token ou sessão inválida |
| `403` | Forbidden | Sem permissões |
| `404` | Not Found | Endpoint ou recurso não existe |
| `500` | Server Error | Erro interno do PHP |
| `ECONNREFUSED` | Conexão recusada | Servidor não está a correr |
| `ETIMEOUT` | Tempo excedido | Rede lenta ou IP errado |
| `EHOSTUNREACH` | Host inacessível | IP errado ou router bloqueia |
| Código de erro Python | `ImportError` | Biblioteca não instalada: `pip install requests` |
| Código de erro C | `Serial not available` | Porta USB errada: verificar `/dev/ttyUSB0` |

## Comandos de Diagnóstico Rápidos

```bash
# Testar se servidor responde
curl http://localhost/api/recursos.php

# Testar se Python consegue fazer pedido HTTP
python3 -c "import requests; r=requests.get('http://localhost/api/recursos.php'); print(r.status_code)"

# Verificar GPIO
gpio readall

# Verificar portas seriais
ls -la /dev/tty*

# Verificar WiFi (ESP32 no Serial Monitor)
# Monitor Serial: 115200 baud

# Verificar logs do Apache
sudo tail -f /var/log/apache2/error.log

# Testar sensor DHT22 (Python direto)
python3 -c "import Adafruit_DHT; print(Adafruit_DHT.read_retry(Adafruit_DHT.DHT22, 4))"
```

## Checklist Rápido de Hardware

1. **Alimentação**: O SBC/MCU está ligado? LED de power aceso?
2. **Breadboard**: Os componentes estão na linha correta?
3. **Jumpers**: As cores correspondem ao esperado?
4. **Polaridade**: LEDs, condensadores e sensores têm polaridade?
5. **Pull-up/down**: Sensores como DHT22 precisam de pull-up? Botões precisam de pull-down?
6. **Tensão**: O sensor funciona a 3.3V ou 5V? O GPIO do ESP32 é 3.3V!
7. **Código**: O pino GPIO no código corresponde ao pino físico?
8. **API**: O servidor está a correr? O URL está correto?
