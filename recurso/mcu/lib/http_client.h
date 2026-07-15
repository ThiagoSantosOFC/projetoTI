#ifndef HTTP_CLIENT_H
#define HTTP_CLIENT_H

#include <Arduino.h>

int http_patch(const char* url, const char* token, const char* body);
int http_get(const char* url, const char* token);
int http_post(const char* url, const char* token, const char* body);

#endif
