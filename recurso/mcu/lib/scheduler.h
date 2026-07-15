#ifndef SCHEDULER_H
#define SCHEDULER_H

#include <Arduino.h>

#define MAX_TASKS 10

typedef struct {
    unsigned long interval;
    unsigned long last_run;
    void (*callback)();
    bool active;
} SchedTask;

void scheduler_init();
int scheduler_every(unsigned long interval_ms, void (*callback)());
void scheduler_run();

#endif
