from .api_client import APIClient


class Resource:
    def __init__(self, resource_id, api_client=None):
        self.id = resource_id
        self._api_client = api_client or APIClient()

    def push_to_api(self, valor):
        return self._api_client.patch_value(self.id, valor)

    def pull_from_api(self):
        return self._api_client.get_resource(self.id)


class Sensor(Resource):
    def __init__(self, resource_id, pin, model=None, api_client=None):
        super().__init__(resource_id, api_client)
        self.pin = pin
        self.model = model
        self._on_read = None

    @property
    def on_read(self):
        return self._on_read

    @on_read.setter
    def on_read(self, callback):
        self._on_read = callback

    def read(self):
        raise NotImplementedError


class Actuator(Resource):
    def __init__(self, resource_id, pin, api_client=None):
        super().__init__(resource_id, api_client)
        self.pin = pin
        self._state = None

    def write(self, state):
        raise NotImplementedError

    def read_state(self):
        return self._state
