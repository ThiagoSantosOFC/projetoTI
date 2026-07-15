import sys
import os
import unittest
from unittest.mock import MagicMock

sys.path.insert(0, os.path.join(os.path.dirname(__file__), ".."))

from lib.resource import Resource, Sensor, Actuator  # noqa: E402


class TestResource(unittest.TestCase):
    def setUp(self):
        self.mock_api = MagicMock()
        self.resource = Resource("test_id", api_client=self.mock_api)

    def test_push_to_api_delega_patch_value(self):
        self.mock_api.patch_value.return_value = {"id": "test_id", "valor": 42}
        resultado = self.resource.push_to_api(42)
        self.mock_api.patch_value.assert_called_once_with("test_id", 42)
        self.assertEqual(resultado, {"id": "test_id", "valor": 42})

    def test_pull_from_api_delega_get_resource(self):
        self.mock_api.get_resource.return_value = {"id": "test_id", "valor": 10}
        resultado = self.resource.pull_from_api()
        self.mock_api.get_resource.assert_called_once_with("test_id")
        self.assertEqual(resultado, {"id": "test_id", "valor": 10})


class TestSensor(unittest.TestCase):
    def setUp(self):
        self.mock_api = MagicMock()
        self.sensor = Sensor("sensor_test", 4, model="DHT22", api_client=self.mock_api)

    def test_read_lanca_not_implemented(self):
        with self.assertRaises(NotImplementedError):
            self.sensor.read()

    def test_on_read_callback(self):
        callback = MagicMock()
        self.sensor.on_read = callback
        self.assertIs(self.sensor.on_read, callback)


class TestActuator(unittest.TestCase):
    def setUp(self):
        self.mock_api = MagicMock()
        self.actuator = Actuator("atuador_test", 18, api_client=self.mock_api)

    def test_write_lanca_not_implemented(self):
        with self.assertRaises(NotImplementedError):
            self.actuator.write("ON")

    def test_read_state_retorna_none_inicialmente(self):
        self.assertIsNone(self.actuator.read_state())


if __name__ == "__main__":
    unittest.main()
