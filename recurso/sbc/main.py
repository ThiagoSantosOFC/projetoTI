#!/usr/bin/env python3
import logging
import signal
import sys

from config import (
    API_BASE_URL,
    API_TOKEN,
    ATUADORES,
    INTERVALO_ATUADORES,
    INTERVALO_LEITURA,
    SENSORES,
)
from lib.api_client import APIClient
from lib.devices import (
    DHT22,
    HCSR04,
    LED,
    Buzzer,
    Button,
    LDRSensor,
    PIRSensor,
    Relay,
    Servo,
)
from lib.scheduler import Scheduler

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(message)s",
    datefmt="%H:%M:%S",
)
logger = logging.getLogger(__name__)

try:
    import RPi.GPIO as GPIO  # noqa: F401
    SIMULATION = False
except (ImportError, RuntimeError):
    SIMULATION = True
    logger.info("GPIO não disponível — modo SIMULAÇÃO ativado")

api_client = APIClient(API_BASE_URL, API_TOKEN)
scheduler = Scheduler()

SENSOR_CLASSES = {
    "dht22": DHT22,
    "pir": PIRSensor,
    "hcsr04": HCSR04,
    "ldr": LDRSensor,
    "button": Button,
}

ACTUATOR_CLASSES = {
    "led": LED,
    "buzzer": Buzzer,
    "servo": Servo,
    "relay": Relay,
}


def instanciar_sensores():
    instances = []
    for cfg in SENSORES:
        cls = SENSOR_CLASSES.get(cfg["tipo"])
        if cls is None:
            logger.warning("Tipo de sensor desconhecido: %s", cfg["tipo"])
            continue
        try:
            if cls is HCSR04:
                sensor = cls(
                    cfg["id"], cfg["pin"], cfg["pin"] + 1,
                    model=cfg.get("modelo"), api_client=api_client,
                )
            else:
                sensor = cls(
                    cfg["id"], cfg["pin"],
                    model=cfg.get("modelo"), api_client=api_client,
                )
            instances.append(sensor)
            logger.info(
                "Sensor %s (tipo=%s, pin=%s) instanciado",
                sensor.id, cfg["tipo"], cfg["pin"],
            )
        except Exception as e:
            logger.error("Erro ao instanciar sensor %s: %s", cfg["id"], e)
    return instances


def instanciar_atuadores():
    instances = []
    for cfg in ATUADORES:
        cls = ACTUATOR_CLASSES.get(cfg["tipo"])
        if cls is None:
            logger.warning("Tipo de atuador desconhecido: %s", cfg["tipo"])
            continue
        try:
            atuador = cls(cfg["id"], cfg["pin"], api_client=api_client)
            instances.append(atuador)
            logger.info(
                "Atuador %s (tipo=%s, pin=%s) instanciado",
                atuador.id, cfg["tipo"], cfg["pin"],
            )
        except Exception as e:
            logger.error("Erro ao instanciar atuador %s: %s", cfg["id"], e)
    return instances


def ler_sensores():
    for sensor in sensores:
        try:
            valor = sensor.read()
            if isinstance(valor, tuple):
                temp, hum = valor
                logger.info(
                    "Sensor %s: temp=%.1f°C hum=%.1f%%", sensor.id, temp, hum,
                )
                sensor.push_to_api({"temperature": temp, "humidity": hum})
            elif valor is not None:
                logger.info("Sensor %s: %s", sensor.id, valor)
                sensor.push_to_api(valor)
            else:
                logger.warning("Sensor %s: leitura nula", sensor.id)
        except Exception as e:
            logger.error("Erro ao ler sensor %s: %s", sensor.id, e)


def verificar_atuadores():
    for atuador in atuadores:
        try:
            estado_api = atuador.pull_from_api()
            if estado_api and "valor" in estado_api:
                atuador.write(estado_api["valor"])
                logger.info("Atuador %s: estado=%s", atuador.id, estado_api["valor"])
        except Exception as e:
            logger.error("Erro ao verificar atuador %s: %s", atuador.id, e)


def cleanup(signum=None, frame=None):
    logger.info("A encerrar...")
    scheduler.stop()
    if not SIMULATION:
        for atuador in atuadores:
            pwm = getattr(atuador, "_pwm", None)
            if pwm:
                pwm.stop()
        GPIO.cleanup()
    logger.info("Sistema encerrado.")
    sys.exit(0)


signal.signal(signal.SIGINT, cleanup)
signal.signal(signal.SIGTERM, cleanup)

sensores = instanciar_sensores()
atuadores = instanciar_atuadores()

scheduler.every(INTERVALO_LEITURA, ler_sensores)
scheduler.every(INTERVALO_ATUADORES, verificar_atuadores)

if __name__ == "__main__":
    logger.info("=" * 50)
    logger.info("SBC System iniciado")
    logger.info("Modo: %s", "SIMULAÇÃO" if SIMULATION else "HARDWARE REAL")
    logger.info("API: %s", API_BASE_URL)
    logger.info("Sensores: %d  |  Atuadores: %d", len(sensores), len(atuadores))
    logger.info("Leitura: %ds  |  Atuadores: %ds", INTERVALO_LEITURA, INTERVALO_ATUADORES)
    logger.info("=" * 50)
    try:
        scheduler.run()
    except KeyboardInterrupt:
        cleanup()
