# Base de Consulta — Tecnologias de Internet (Recurso)

**ESTG · IPLeiria · 2025/2026**

Monorepo de consulta para o exame de recurso de Tecnologias de Internet.

## Estrutura

```
recurso/
├── web/          # PHP + HTML/CSS/JS — Website (25%)
├── sbc/          # Python — Raspberry Pi (25%)
├── mcu/          # C — ESP32/Arduino (25%)
├── extras/       # Webcam, Bluetooth, Cisco PT (+15%)
├── docs/         # Documentação, guias, esquemas (+5%)
└── scripts/      # Utilitários
```

## Como usar

```bash
# 1. Iniciar servidor PHP local
bash scripts/dev-server.sh

# 2 (opcional). Setup inicial
bash scripts/setup.sh

# 3 (opcional). Gerar PDF compilado da documentação
bash scripts/generate-pdf.sh
```

## Documentação Rápida

| O quê | Onde |
|---|---|
| API endpoints | `docs/api/endpoints.md` |
| Guias de código | `docs/guides/` |
| Esquemas de montagem | `docs/hardware/circuits/` |
| Desenho arquitetura | `docs/architecture/arquitetura.md` |
| Checklist exame | `docs/guides/06-checklist-recurso.md` |

## Licença

Projeto académico — ESTG IPLeiria.
