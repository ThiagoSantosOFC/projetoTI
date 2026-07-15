API_BASE_URL = "http://localhost:8000/api"
API_TOKEN = "sbc_token_123"
INTERVALO_LEITURA = 10  # segundos
INTERVALO_ATUADORES = 5  # segundos

# Mapeamento de sensores: id, pin, tipo, modelo
SENSORES = [
    {"id": "temp_hum_sala", "pin": 4, "tipo": "dht22", "modelo": "DHT22"},
    {"id": "presenca_entrada", "pin": 17, "tipo": "pir", "modelo": "HC-SR501"},
]

# Mapeamento de atuadores: id, pin, tipo
ATUADORES = [
    {"id": "led_verde", "pin": 18, "tipo": "led"},
    {"id": "buzzer_alerta", "pin": 19, "tipo": "buzzer"},
]
