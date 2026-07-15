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

SOUND_SPEED = 34300


class HCSR04(Sensor):
    def __init__(self, resource_id, trigger_pin, echo_pin, model="HC-SR04",
                 api_client=None, simulation=None):
        super().__init__(resource_id, trigger_pin, model, api_client)
        self.trigger_pin = trigger_pin
        self.echo_pin = echo_pin
        self.simulation = not GPIO_AVAILABLE if simulation is None else simulation
        if not self.simulation:
            GPIO.setmode(GPIO.BCM)
            GPIO.setup(self.trigger_pin, GPIO.OUT, initial=GPIO.LOW)
            GPIO.setup(self.echo_pin, GPIO.IN)

    def _read_simulation(self):
        return round(random.uniform(10.0, 400.0), 1)

    def _read_real(self):
        GPIO.output(self.trigger_pin, GPIO.HIGH)
        time.sleep(0.00001)
        GPIO.output(self.trigger_pin, GPIO.LOW)

        timeout = time.time() + 0.5
        while GPIO.input(self.echo_pin) == GPIO.LOW:
            if time.time() > timeout:
                raise TimeoutError("echo LOW")
            time.sleep(0.00001)

        start = time.time()
        while GPIO.input(self.echo_pin) == GPIO.HIGH:
            if time.time() > timeout:
                raise TimeoutError("echo HIGH")

        duration = time.time() - start
        return round((duration * SOUND_SPEED) / 2.0, 1)

    def read(self):
        try:
            if self.simulation:
                return self._read_simulation()
            return self._read_real()
        except Exception as e:
            logger.error("Erro HC-SR04: %s", e)
            return None
