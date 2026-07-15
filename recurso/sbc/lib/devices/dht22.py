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


class DHT22(Sensor):
    def __init__(self, resource_id, pin, model="DHT22", api_client=None, simulation=None):
        super().__init__(resource_id, pin, model, api_client)
        self.simulation = not GPIO_AVAILABLE if simulation is None else simulation
        if not self.simulation:
            GPIO.setmode(GPIO.BCM)

    def _read_simulation(self):
        temperature = round(random.uniform(18.0, 32.0), 1)
        humidity = round(random.uniform(40.0, 80.0), 1)
        return temperature, humidity

    def _read_real(self):
        GPIO.setup(self.pin, GPIO.OUT, initial=GPIO.HIGH)
        time.sleep(0.05)

        GPIO.output(self.pin, GPIO.LOW)
        time.sleep(0.02)
        GPIO.setup(self.pin, GPIO.IN, pull_up_down=GPIO.PUD_UP)

        def _wait(pin_val, max_loops=10000):
            loops = 0
            while GPIO.input(self.pin) == pin_val:
                loops += 1
                if loops > max_loops:
                    raise TimeoutError()

        _wait(GPIO.HIGH)
        _wait(GPIO.LOW)
        _wait(GPIO.HIGH)

        bits = []
        for _ in range(40):
            _wait(GPIO.LOW, 5000)
            start = time.perf_counter()
            _wait(GPIO.HIGH, 5000)
            duration = (time.perf_counter() - start) * 1_000_000
            bits.append(1 if duration > 50 else 0)

        data = []
        for i in range(5):
            byte = 0
            for j in range(8):
                byte = (byte << 1) | bits[i * 8 + j]
            data.append(byte)

        if (sum(data[:4]) & 0xFF) != data[4]:
            raise ValueError("checksum")

        humidity = (data[0] * 256 + data[1]) / 10.0
        temp_raw = data[2] * 256 + data[3]
        temperature = temp_raw / 10.0

        return temperature, humidity

    def read(self):
        try:
            if self.simulation:
                return self._read_simulation()
            return self._read_real()
        except Exception as e:
            logger.error("Erro DHT22 (pin %d): %s", self.pin, e)
            return None, None
