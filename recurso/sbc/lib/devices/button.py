import logging
import random
import time

from ..resource import Sensor

logger = logging.getLogger(__name__)

try:
    import RPi.GPIO as GPIO
    GPIO_AVAILABLE = True
except (ImportError, RuntimeError):
    GPIO_AVAILABLE = False


class Button(Sensor):
    def __init__(self, resource_id, pin, pull_up=True, model="Botão",
                 api_client=None, simulation=None):
        super().__init__(resource_id, pin, model, api_client)
        self.pull_up = pull_up
        self.simulation = not GPIO_AVAILABLE if simulation is None else simulation
        if not self.simulation:
            GPIO.setmode(GPIO.BCM)
            gpio_pull = GPIO.PUD_UP if pull_up else GPIO.PUD_DOWN
            GPIO.setup(self.pin, GPIO.IN, pull_up_down=gpio_pull)

    def _read_simulation(self):
        return random.random() < 0.1

    def _read_real(self):
        if self.pull_up:
            return GPIO.input(self.pin) == GPIO.LOW
        return GPIO.input(self.pin) == GPIO.HIGH

    def read(self):
        try:
            if self.simulation:
                return self._read_simulation()
            return self._read_real()
        except Exception as e:
            logger.error("Erro Button (pin %d): %s", self.pin, e)
            return False

    def wait_for_press(self, timeout=None):
        start = time.time()
        while True:
            if self.read():
                return True
            if timeout is not None and (time.time() - start) >= timeout:
                return False
            time.sleep(0.05)
