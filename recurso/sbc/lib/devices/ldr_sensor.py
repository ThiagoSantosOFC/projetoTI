import logging
import random

from ..resource import Sensor

logger = logging.getLogger(__name__)

try:
    import RPi.GPIO as GPIO
    GPIO_AVAILABLE = True
except (ImportError, RuntimeError):
    GPIO_AVAILABLE = False

try:
    import spidev
    SPI_AVAILABLE = True
except ImportError:
    SPI_AVAILABLE = False


class LDRSensor(Sensor):
    def __init__(self, resource_id, pin, model="LDR+MCP3008", api_client=None, simulation=None):
        super().__init__(resource_id, pin, model, api_client)
        self.channel = pin
        self.simulation = not GPIO_AVAILABLE if simulation is None else simulation
        self._spi = None
        if not self.simulation and SPI_AVAILABLE:
            self._spi = spidev.SpiDev()
            self._spi.open(0, 0)
            self._spi.max_speed_hz = 1000000

    def _read_simulation(self):
        return round(random.uniform(0, 100), 1)

    def _read_real(self):
        if not self._spi:
            return 0
        raw = self._spi.xfer2([1, (8 + self.channel) << 4, 0])
        value = ((raw[1] & 3) << 8) + raw[2]
        return round((value / 1023.0) * 100, 1)

    def read(self):
        try:
            if self.simulation:
                return self._read_simulation()
            return self._read_real()
        except Exception as e:
            logger.error("Erro LDR (channel %d): %s", self.channel, e)
            return None
