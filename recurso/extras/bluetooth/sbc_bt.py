#!/usr/bin/env python3
"""
sbc_bt.py — Comunicação Bluetooth no SBC (Raspberry Pi).
Extra opcional para envio de dados BT enquadrado no tema.

Dependências: pip install pybluez
Uso: python3 sbc_bt.py [--server | --send "mensagem"]
"""

import argparse
import json
import time

try:
    import bluetooth
except ImportError:
    print("[AVISO] PyBluez não instalado. Usa: pip install pybluez")
    print("[AVISO] A executar em modo simulação (sem BT real)")
    bluetooth = None


def server_mode():
    """Modo servidor: aguarda conexões BT e exibe dados recebidos."""
    if not bluetooth:
        print("[SIMULAÇÃO] Servidor BT (sem hardware)")
        return

    uuid = "00001101-0000-1000-8000-00805F9B34FB"
    service_name = "TI_Recurso_BT"

    print(f"[BT] A aguardar conexões em {service_name}...")
    # Implementação real com bluetoothSocket


def send_message(mensagem):
    """Modo cliente: envia mensagem via BT."""
    if not bluetooth:
        print(f"[SIMULAÇÃO] Mensagem enviada: {mensagem}")
        return

    # Implementação real: descobrir dispositivo, conectar, enviar
    print(f"[BT] A enviar: {mensagem}")


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Comunicação Bluetooth SBC")
    group = parser.add_mutually_exclusive_group(required=True)
    group.add_argument("--server", action="store_true", help="Modo servidor")
    group.add_argument("--send", type=str, help="Enviar mensagem")
    args = parser.parse_args()

    if args.server:
        server_mode()
    elif args.send:
        send_message(args.send)
