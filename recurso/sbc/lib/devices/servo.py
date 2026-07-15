import logging
import time

from ..resource import Actuator

logger = logging.getLogger(__name__)

try:
    import RPi.GPIO as GPIO
    GPIO_AVAILABLE = True
except (ImportError, RuntimeError):
    GPIO_AVAILABLE = False


class Servo(Actuator):
    def __init__(self, resource_id, pin, api_client=None, simulation=None):
        super().__init__(resource_id, pin, api_client)
        self.simulation = not GPIO_AVAILABLE if simulation is None else simulation
        self._pwm = None
        self._angle = 0
        if not self.simulation:
            GPIO.setmode(GPIO.BCM)
            GPIO.setup(self.pin, GPIO.OUT)
            self._pwm = GPIO.PWM(self.pin, 50)
            self._pwm.start(0)

    def angle(self, graus):
        graus = max(0, min(180, graus))
        self._angle = graus
        logger.info("%sServo %s: %d°", "[SIM] " if self.simulation else "", self.id, graus)
        if not self.simulation:
            duty = 2 + (graus / 18.0)
            self._pwm.ChangeDutyCycle(duty)
            time.sleep(0.3)
            self._pwm.ChangeDutyCycle(0)

    def write(self, estado):
        try:
            self.angle(int(estado))
        except (ValueError, TypeError):
            logger.warning("Servo valor inválido: %s (use 0-180)", estado)

    def read(self):
        return self._angle
