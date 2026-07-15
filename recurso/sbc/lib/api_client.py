import logging

import requests

logger = logging.getLogger(__name__)


class APIClient:
    def __init__(self, base_url="http://localhost:8000/api", token="sbc_token_123"):
        self.base_url = base_url.rstrip("/")
        self.session = requests.Session()
        self.session.headers.update({
            "Authorization": f"Bearer {token}",
            "Content-Type": "application/json",
        })

    def patch_value(self, resource_id, valor):
        url = f"{self.base_url}/resources/{resource_id}"
        try:
            resp = self.session.patch(url, json={"valor": valor}, timeout=10)
            resp.raise_for_status()
            return resp.json()
        except requests.Timeout:
            logger.error("Timeout PATCH %s", resource_id)
        except requests.ConnectionError:
            logger.error("Conexão recusada PATCH %s", resource_id)
        except requests.HTTPError as e:
            logger.error("HTTP %d PATCH %s", e.response.status_code, resource_id)
        except Exception as e:
            logger.error("Erro PATCH %s: %s", resource_id, e)
        return None

    def get_resource(self, resource_id):
        url = f"{self.base_url}/resources/{resource_id}"
        try:
            resp = self.session.get(url, timeout=10)
            resp.raise_for_status()
            return resp.json()
        except requests.Timeout:
            logger.error("Timeout GET %s", resource_id)
        except requests.ConnectionError:
            logger.error("Conexão recusada GET %s", resource_id)
        except requests.HTTPError as e:
            logger.error("HTTP %d GET %s", e.response.status_code, resource_id)
        except Exception as e:
            logger.error("Erro GET %s: %s", resource_id, e)
        return None

    def get_all_resources(self):
        url = f"{self.base_url}/resources"
        try:
            resp = self.session.get(url, timeout=10)
            resp.raise_for_status()
            return resp.json()
        except requests.Timeout:
            logger.error("Timeout GET resources")
        except requests.ConnectionError:
            logger.error("Conexão recusada GET resources")
        except requests.HTTPError as e:
            logger.error("HTTP %d GET resources", e.response.status_code)
        except Exception as e:
            logger.error("Erro GET resources: %s", e)
        return None

    def post_command(self, resource_id, comando):
        url = f"{self.base_url}/resources/{resource_id}/comando"
        try:
            resp = self.session.post(url, json={"comando": comando}, timeout=10)
            resp.raise_for_status()
            return resp.json()
        except requests.Timeout:
            logger.error("Timeout POST comando %s", resource_id)
        except requests.ConnectionError:
            logger.error("Conexão recusada POST comando %s", resource_id)
        except requests.HTTPError as e:
            logger.error("HTTP %d POST comando %s", e.response.status_code, resource_id)
        except Exception as e:
            logger.error("Erro POST comando %s: %s", resource_id, e)
        return None
