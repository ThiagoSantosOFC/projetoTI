# Guia de Validação HTML e CSS

## Validação HTML

### 1. Validador W3C

**URL:** [https://validator.w3.org/](https://validator.w3.org/)

**Métodos de validação:**

1. **By URI** — Se o dashboard estiver online, inserir o URL
2. **By File Upload** — Enviar o ficheiro HTML
3. **By Direct Input** — Colar o código HTML

### 2. Como Validar

**Passo 1:** Abrir [validator.w3.org](https://validator.w3.org/)
**Passo 2:** Selecionar "Validate by File Upload"
**Passo 3:** Escolher o ficheiro `dashboard/index.html`
**Passo 4:** Clicar "Check"
**Passo 5:** Verificar o resultado

### 3. Interpretar Resultados

- **✅ Passou:** "Document checking completed. No errors or warnings to show."
- **⚠ Avisos:** "This document was successfully checked as XHTML/X" — avisos são aceitáveis
- **❌ Erros:** Corrigir os erros indicados e revalidar

### 4. Erros Comuns e Correções

| Erro | Causa | Correção |
|------|-------|----------|
| `No DOCTYPE found` | Falta `<!DOCTYPE html>` | Adicionar no topo do ficheiro |
| `Element X not allowed` | Tag errada | Verificar se a tag é válida em HTML5 |
| `Duplicate ID X` | IDs repetidos | Cada ID deve ser único |
| `Bad value X for attribute` | Atributo inválido | Usar valores válidos |
| `The X attribute is not supported` | Atributo obsoleto | Remover ou substituir |
| `Stray end tag X` | Tag fechada sem abrir | Verificar estrutura de tags |
| `Element X is missing required attribute` | Atributo obrigatório em falta | Adicionar conforme especificação |

### 5. Checklist de Validação HTML

- [ ] DOCTYPE html presente
- [ ] `<html lang="pt">` definido
- [ ] `<meta charset="utf-8">` presente
- [ ] Todos os IDs únicos
- [ ] Tags fechadas corretamente
- [ ] Atributos alt em todas as imagens
- [ ] Sem tags obsoletas (&lt;center&gt;, &lt;font&gt;)
- [ ] Atributo `type` correto nos scripts (`text/javascript` é default)
- [ ] Links funcionais (sem href="#")

### 6. Gerar PDF da Validação

1. Após validar, o W3C mostra uma página com o resultado
2. **Ctrl+P** (ou Cmd+P) para imprimir
3. Selecionar "Guardar como PDF"
4. Escolher nome: `validacao-html.pdf`
5. Guardar para entregar com o projeto

---

## Validação CSS

### 1. Validador W3C CSS

**URL:** [https://jigsaw.w3.org/css-validator/](https://jigsaw.w3.org/css-validator/)

**Métodos de validação:**

1. **By URI** — Se online
2. **By File Upload** — Enviar ficheiro CSS
3. **By Direct Input** — Colar o código CSS

### 2. Como Validar

**Passo 1:** Abrir [jigsaw.w3.org/css-validator/](https://jigsaw.w3.org/css-validator/)
**Passo 2:** Selecionar "By File Upload"
**Passo 3:** Escolher o ficheiro CSS
**Passo 4:** Clicar "Check"
**Passo 5:** Rever resultados

### 3. Interpretar Resultados

- **✅ Passou:** "Parabéns! Não foram encontrados erros."
- **⚠ Avisos:** Avisos de propriedades específicas de browser (aceitável)
- **❌ Erros:** Corrigir as propriedades inválidas

### 4. Erros Comuns CSS

| Erro | Causa | Correção |
|------|-------|----------|
| `Property X doesn't exist` | Propriedade inventada | Verificar nome correto |
| `Value Error: X` | Valor inválido para propriedade | Usar valores permitidos |
| `Parse Error` | Erro de sintaxe | Verificar ponto-e-vírgula e chavetas |
| `Property X is a vendor extension` | Prefixo `-webkit-`, `-moz-` | Aviso aceitável |

### 5. Checklist de Validação CSS

- [ ] Seletores corretos (sem erros de sintaxe)
- [ ] Propriedades com valores válidos
- [ ] Cores em hex ou nome válido
- [ ] Unidades corretas (px, em, %, etc.)
- [ ] Sem duplicação desnecessária
- [ ] Media queries com sintaxe correta
- [ ] Sem `!important` a menos que necessário
- [ ] Vendor prefixes com fallback

### 6. CSS Inline vs Externo

| Método | Vantagem | Desvantagem |
|--------|----------|-------------|
| CSS Externo (`.css`) | Fácil de validar, reutilizável | Requer HTTP request extra |
| CSS Interno (`<style>`) | Tudo num ficheiro | Válido apenas na página |
| CSS Inline (`style=""`) | Específico | Difícil de manter, repetitivo |

**Recomendação:** Usar CSS interno no `<head>` para o dashboard (tudo num ficheiro).

### 7. Gerar PDF da Validação CSS

1. Após validar, o validador mostra a página de resultado
2. **Ctrl+P** → "Guardar como PDF"
3. Nome: `validacao-css.pdf`

---

## Exemplo de HTML Válido (Mínimo)

```html
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard IoT</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f8fafc; }
        h1 { color: #1e293b; }
        .card { background: white; border-radius: 8px; padding: 15px; margin: 10px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .valor { font-size: 24px; font-weight: bold; color: #2563eb; }
        button { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <h1>📊 Dashboard IoT</h1>
    <div class="card">
        <h2>Temperatura</h2>
        <p class="valor" id="temp-valor">--</p>
    </div>
    <script>
        // JavaScript aqui
    </script>
</body>
</html>
```

## Resumo do Processo

1. ✅ Desenvolver HTML e CSS
2. ✅ Validar HTML em [validator.w3.org](https://validator.w3.org/)
3. ✅ Validar CSS em [jigsaw.w3.org/css-validator/](https://jigsaw.w3.org/css-validator/)
4. ✅ Corrigir erros encontrados
5. ✅ Revalidar até passar sem erros
6. ✅ Gerar PDF de cada validação
7. ✅ Incluir PDFs na entrega do projeto
