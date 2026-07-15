# Guia de Breadboard

## O que é uma Breadboard?

Uma breadboard (placa de ensaio) permite montar circuitos eletrónicos sem soldar. Os componentes e fios são inseridos nos orifícios, que estão ligados internamente em linhas.

## Diagrama da Breadboard

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 700 420" font-family="Arial, sans-serif">
  <rect width="700" height="420" fill="#f8fafc" rx="8"/>

  <!-- Breadboard -->
  <rect x="60" y="30" width="580" height="360" rx="10" fill="#fafafa" stroke="#cbd5e1" stroke-width="2"/>

  <!-- Power Rail Superior -->
  <rect x="70" y="40" width="250" height="40" rx="4" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <rect x="330" y="40" width="300" height="40" rx="4" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>

  <!-- Power Rail Inferior -->
  <rect x="70" y="340" width="250" height="40" rx="4" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <rect x="330" y="340" width="300" height="40" rx="4" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>

  <!-- Labels Power Rails -->
  <text x="195" y="65" text-anchor="middle" font-size="10" font-weight="bold" fill="#dc2626">🔴 LINHA POSITIVA (VCC / 3.3V-5V)</text>
  <text x="480" y="65" text-anchor="middle" font-size="10" font-weight="bold" fill="#16a34a">🟢 LINHA NEGATIVA (GND)</text>
  <text x="195" y="365" text-anchor="middle" font-size="10" font-weight="bold" fill="#dc2626">🔴 LINHA POSITIVA (VCC)</text>
  <text x="480" y="365" text-anchor="middle" font-size="10" font-weight="bold" fill="#16a34a">🟢 LINHA NEGATIVA (GND)</text>

  <!-- Canal Central -->
  <rect x="70" y="90" width="560" height="240" rx="2" fill="none" stroke="#e2e8f0" stroke-width="1"/>

  <!-- Calha Central -->
  <rect x="315" y="90" width="8" height="240" fill="#94a3b8" opacity="0.3"/>

  <!-- Linhas de terminais - lado esquerdo -->
  <line x1="85" y1="100" x2="85" y2="120" stroke="#cbd5e1" stroke-width="1"/>
  <line x1="100" y1="100" x2="100" y2="120" stroke="#cbd5e1" stroke-width="1"/>
  <line x1="115" y1="100" x2="115" y2="120" stroke="#cbd5e1" stroke-width="1"/>
  <line x1="130" y1="100" x2="130" y2="120" stroke="#cbd5e1" stroke-width="1"/>
  <line x1="145" y1="100" x2="145" y2="120" stroke="#cbd5e1" stroke-width="1"/>

  <!-- Cones de ligação visual -->
  <ellipse cx="85" cy="110" rx="6" ry="8" fill="#e2e8f0" opacity="0.5"/>
  <ellipse cx="100" cy="110" rx="6" ry="8" fill="#e2e8f0" opacity="0.5"/>
  <ellipse cx="115" cy="110" rx="6" ry="8" fill="#e2e8f0" opacity="0.5"/>
  <ellipse cx="130" cy="110" rx="6" ry="8" fill="#e2e8f0" opacity="0.5"/>
  <ellipse cx="145" cy="110" rx="6" ry="8" fill="#e2e8f0" opacity="0.5"/>

  <!-- Exemplo: linha 5 ligada (conexão horizontal) -->
  <rect x="75" y="195" width="230" height="4" fill="#fbbf24" opacity="0.40"/>
  <text x="190" y="188" text-anchor="middle" font-size="9" fill="#b45309">Linha ligada horizontalmente (A-E ligados entre si)</text>

  <!-- Exemplo: linha 5 lado direito -->
  <rect x="325" y="195" width="230" height="4" fill="#fbbf24" opacity="0.40"/>
  <text x="440" y="188" text-anchor="middle" font-size="9" fill="#b45309">Linha ligada horizontalmente (F-J ligados entre si)</text>

  <!-- Setas indicando -->
  <text x="155" y="110" font-size="9" fill="#64748b">A B C D E</text>
  <text x="350" y="110" font-size="9" fill="#64748b">F G H I J</text>

  <!-- Labels das linhas -->
  <text x="75" y="140" font-size="8" fill="#94a3b8">1</text>
  <text x="75" y="160" font-size="8" fill="#94a3b8">2</text>
  <text x="75" y="180" font-size="8" fill="#94a3b8">3</text>
  <text x="75" y="200" font-size="8" fill="#94a3b8">4</text>
  <text x="75" y="220" font-size="8" fill="#94a3b8">5</text>
  <text x="75" y="240" font-size="8" fill="#94a3b8">...</text>

  <!-- Labels do lado direito -->
  <text x="615" y="140" font-size="8" fill="#94a3b8">1</text>
  <text x="615" y="160" font-size="8" fill="#94a3b8">2</text>
  <text x="615" y="180" font-size="8" fill="#94a3b8">3</text>
  <text x="615" y="200" font-size="8" fill="#94a3b8">4</text>
  <text x="615" y="220" font-size="8" fill="#94a3b8">5</text>
  <text x="615" y="240" font-size="8" fill="#94a3b8">...</text>

  <!-- Legenda -->
  <rect x="70" y="280" width="560" height="90" rx="6" fill="#f1f5f9" stroke="#e2e8f0" stroke-width="1"/>
  <text x="85" y="300" font-weight="bold" font-size="12" fill="#475569">Estrutura Interna:</text>

  <!-- Power Rails interno -->
  <rect x="85" y="310" width="50" height="14" rx="3" fill="#fef2f2" stroke="#fca5a5" stroke-width="1"/>
  <text x="110" y="321" text-anchor="middle" font-size="9" fill="#dc2626">VCC</text>
  <text x="145" y="321" font-size="10" fill="#64748b">← Linha vertical contínua (toda a coluna ligada)</text>

  <rect x="85" y="330" width="50" height="14" rx="3" fill="#f0fdf4" stroke="#86efac" stroke-width="1"/>
  <text x="110" y="341" text-anchor="middle" font-size="9" fill="#16a34a">GND</text>
  <text x="145" y="341" font-size="10" fill="#64748b">← Linha vertical contínua (toda a coluna ligada)</text>

  <!-- Terminais -->
  <rect x="85" y="350" width="50" height="14" rx="3" fill="#fef9c3" stroke="#facc15" stroke-width="1"/>
  <text x="110" y="361" text-anchor="middle" font-size="9" fill="#a16207">A-E</text>
  <text x="145" y="361" font-size="10" fill="#64748b">← Linha horizontal (A-B-C-D-E ligados entre si; F-G-H-I-J ligados entre si)</text>
</svg>
```

## Como funciona

### Power Rails (Linhas de Alimentação)

As duas colunas de cada lado (marcadas com linhas vermelhas/verdes) são **verticais contínuas**:

- **VCC (vermelho):** Todos os orifícios nesta coluna estão ligados entre si verticalmente. Usa-se para distribuir a tensão positiva (3.3V ou 5V).
- **GND (preto/verde):** Todos os orifícios nesta coluna estão ligados entre si verticalmente. Usa-se para o negativo/terra.

### Terminais (Área Central)

A área central está dividida em duas metades pelo canal central. **Cada linha de 5 orifícios (A-E ou F-J) está ligada horizontalmente.**

- **A-E** estão ligados entre si (mas não a F-J).
- **F-J** estão ligados entre si (mas não a A-E).
- O **canal central** separa as duas metades — útil para colocar CI (circuitos integrados) como o ESP32 ou sensores.

## Como Inserir Componentes

### LED
- **Ânodo (perna + longa)** → linha com GPIO ou VCC via resistor
- **Cátodo (perna - curta)** → linha com GND
- Inserir pernas em linhas diferentes (ex: ânodo na linha 5E, cátodo na linha 5F)

### Resistor
- As pernas do resistor podem ser inseridas em quaisquer duas linhas — não tem polaridade
- Para LED: uma perna na linha do GPIO, outra na linha do ânodo do LED

### Jumpers (Fios)
- Usar fios macho-macho para ligar breadboard ao GPIO
- Inserir nas linhas corretas conforme o circuito

## Cores de Jumpers (Convenção)

| Cor | Função | Exemplo |
|-----|--------|---------|
| 🔴 **Vermelho** | VCC / Alimentação positiva | 3.3V, 5V |
| ⚫ **Preto** | GND / Terra | 0V |
| 🟠 **Laranja** | GPIO / Sinal | Dados, PWM |
| 🟡 **Amarelo** | GPIO / Sinal alternativo | Clock, Trigger |
| 🔵 **Azul** | Sinal analógico | ADC |
| 🟢 **Verde** | Sinal serial | TX, RX |
| ⚪ **Branco** | Pull-up / Pull-down | Resistores externos |

## Dicas Importantes

1. **Verificar sempre as ligações** antes de ligar a alimentação
2. **Não inserir componentes à força** — se não entrar, verificar se está direito
3. **LEDs têm polaridade** — o ânodo (perna longa) para o positivo
4. **Resistores não têm polaridade** — podem ser inseridos em qualquer direção
5. **Utilizar jumpers de cores diferentes** para facilitar a leitura do circuito
6. **Manter os fios organizados** — circuitos limpos são mais fáceis de debug
