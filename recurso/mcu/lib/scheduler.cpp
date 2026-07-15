#include "scheduler.h"

static SchedTask tasks[MAX_TASKS];
static int task_count = 0;

void scheduler_init() {
    task_count = 0;
    for (int i = 0; i < MAX_TASKS; i++) {
        tasks[i].active = false;
    }
}

int scheduler_every(unsigned long interval_ms, void (*callback)()) {
    if (task_count >= MAX_TASKS) return -1;
    tasks[task_count].interval = interval_ms;
    tasks[task_count].last_run = 0;
    tasks[task_count].callback = callback;
    tasks[task_count].active = true;
    return task_count++;
}

void scheduler_run() {
    unsigned long now = millis();
    for (int i = 0; i < task_count; i++) {
        if (!tasks[i].active) continue;
        if (now - tasks[i].last_run >= tasks[i].interval) {
            tasks[i].last_run = now;
            tasks[i].callback();
        }
    }
}
