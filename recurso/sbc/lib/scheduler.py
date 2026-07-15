import logging
import time

logger = logging.getLogger(__name__)


class Scheduler:
    def __init__(self):
        self._tasks = []
        self._running = False

    def every(self, interval, callback, *args, **kwargs):
        self._tasks.append({
            "interval": interval,
            "callback": callback,
            "args": args,
            "kwargs": kwargs,
            "last_run": 0.0,
        })

    def run(self):
        self._running = True
        logger.info("Scheduler iniciado com %d tarefas", len(self._tasks))
        while self._running:
            now = time.time()
            for task in self._tasks:
                if now - task["last_run"] >= task["interval"]:
                    try:
                        task["callback"](*task["args"], **task["kwargs"])
                    except Exception as e:
                        logger.exception("Erro na tarefa agendada: %s", e)
                    task["last_run"] = time.time()
            time.sleep(0.5)

    def stop(self):
        self._running = False
        logger.info("Scheduler parado")
