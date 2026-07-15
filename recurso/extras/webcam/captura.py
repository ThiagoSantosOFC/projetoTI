#!/usr/bin/env python3
"""
captura.py — Captura de imagem da webcam e envio para a API.
Uso: python3 captura.py [--intervalo 30]

Dependências: pip install opencv-python requests
"""

import cv2
import requests
import os
import sys
import time
import argparse

API_URL = "http://localhost:8000/api/camera"
API_TOKEN = "sbc_token_123"
TEMP_FILE = "/tmp/webcam_captura.jpg"


def capturar():
    """Captura imagem da webcam (índice 0) e guarda em ficheiro temporário."""
    cam = cv2.VideoCapture(0)
    if not cam.isOpened():
        print("[ERRO] Webcam não encontrada (índice 0)")
        return None

    ret, frame = cam.read()
    cam.release()

    if not ret:
        print("[ERRO] Falha ao capturar frame")
        return None

    cv2.imwrite(TEMP_FILE, frame, [cv2.IMWRITE_JPEG_QUALITY, 85])
    print(f"[OK] Imagem capturada: {TEMP_FILE}")
    return TEMP_FILE


def enviar(caminho):
    """Envia imagem para a API via POST multipart."""
    with open(caminho, "rb") as f:
        files = {"imagem": ("captura.jpg", f, "image/jpeg")}
        headers = {"Authorization": f"Bearer {API_TOKEN}"}

        try:
            resp = requests.post(API_URL, files=files, headers=headers, timeout=10)
            if resp.status_code == 200:
                print(f"[OK] Imagem enviada: {resp.json()}")
                return True
            else:
                print(f"[ERRO] API retornou {resp.status_code}: {resp.text}")
                return False
        except requests.exceptions.RequestException as e:
            print(f"[ERRO] Falha no envio: {e}")
            return False


def loop(intervalo):
    """Loop contínuo de captura + envio."""
    print(f"[INICIO] Captura a cada {intervalo}s")
    while True:
        caminho = capturar()
        if caminho:
            enviar(caminho)
            os.remove(caminho)
        time.sleep(intervalo)


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Captura e envio de webcam")
    parser.add_argument("--intervalo", type=int, default=30, help="Intervalo entre capturas (s)")
    parser.add_argument("--once", action="store_true", help="Apenas uma captura")
    args = parser.parse_args()

    if args.once:
        caminho = capturar()
        if caminho:
            enviar(caminho)
            os.remove(caminho)
    else:
        loop(args.intervalo)
