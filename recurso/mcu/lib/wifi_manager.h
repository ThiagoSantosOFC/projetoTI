#ifndef WIFI_MANAGER_H
#define WIFI_MANAGER_H

#include <Arduino.h>

void wifi_init(const char* ssid, const char* pass);
void wifi_ensure_connected();
int wifi_status();

#endif
