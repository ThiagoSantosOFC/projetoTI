import logging

from ..resource import Actuator

logger = logging.getLogger(__name__)

try:
    import RPi.GPIO as GPIO
    GPIO_AVAILABLE = True
except (ImportError, RuntimeError):
    GPIO_AVAILABLE = False


class LED(Actuator):
    def __init__(self, resource_id, pin, api_client=None, simulation=None):
        super().__init__(resource_id, pin, api_client)
        self.simulation = not GPIO_AVAILABLE if simulation is None else simulation
        if not self.simulation:
            GPIO.setmode(GPIO.BCM)
            GPIO.setup(self.pin, GPIO.OUT, initial=GPIO.LOW)

    def on(self):
        self._state = True
        if not self.simulation:
            GPIO.output(self.pin, GPIO.HIGH)
        logger.info("%sLED %s: ON", "[SIM] " if self.simulation else "", self.id)

    def off(self):
        self._state = False
        if not self.simulation:
            GPIO.output(self.pin, GPIO.LOW)
        logger.info("%sLED %s: OFF", "[SIM] " if self.simulation else "", self.id)

    def toggle(self):
        if self._state:
            self.off()
        else:
            self.on()
        return self._state

    def write(self, estado):
        cmd = estado.upper() if isinstance(estado, str) else estado
        if cmd == "ON":
            self.on()
        elif cmd == "OFF":
            self.off()
        elif cmd == "TOGGLE":
            self.toggle()
        else:
            logger.warning("LED estado inválido: %s", estado)

    def read(self):
        return self._state
