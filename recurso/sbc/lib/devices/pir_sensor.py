import logging
import random

from ..resource import Sensor

logger = logging.getLogger(__name__)

try:
    import RPi.GPIO as GPIO
    GPIO_AVAILABLE = True
except (ImportError, RuntimeError):
    GPIO_AVAILABLE = False


class PIRSensor(Sensor):
    def __init__(self, resource_id, pin, model="HC-SR501", api_client=None, simulation=None):
        super().__init__(resource_id, pin, model, api_client)
        self.simulation = not GPIO_AVAILABLE if simulation is None else simulation
        if not self.simulation:
            GPIO.setmode(GPIO.BCM)
            GPIO.setup(self.pin, GPIO.IN)

    def _read_simulation(self):
        return random.random() < 0.3

    def _read_real(self):
        return GPIO.input(self.pin) == GPIO.HIGH

    def read(self):
        try:
            if self.simulation:
                return self._read_simulation()
            return self._read_real()
        except Exception as e:
            logger.error("Erro PIR (pin %d): %s", self.pin, e)
            return False
