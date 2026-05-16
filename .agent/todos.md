# Projeto Minimalista - Sugestões e TODOs

Este documento contém uma análise técnica da aplicação e sugestões de melhorias para elevar a qualidade do template para um nível "Premium" e garantir compatibilidade total com Joomla 5/6.

## 🚀 Melhorias Técnicas (TODOs)

### 1. SEO e Metadados Avançados
- [ ] **Integração com API Schema.org (Joomla 5/6)**: Em vez de criar um JSON-LD manual (que causaria duplicação com o core do Joomla), auditar o uso das tags meta `schema:` legadas no `logic.php` e garantir que o template forneça as informações necessárias para a API nativa do Joomla.
- [ ] **Theme Color**: Adicionar suporte à tag meta `theme-color` baseada nos parâmetros do template.

### 2. Experiência do Usuário (UX) e Design
- [ ] **Overrides de Conteúdo**: Atualmente existem poucos overrides na pasta `html/`. Sugere-se criar layouts customizados para:
    - `com_content/article`: Design minimalista e tipografia refinada.
    - `com_content/category`: Grid de artigos mais moderno e limpo.
- [ ] **Redesign de Páginas de Sistema**:
    - **Error Page (`error.php`)**: Criar um design visualmente impactante que não pareça uma página de erro padrão.
    - **Offline Page (`offline.php`)**: Adicionar contagem regressiva ou formulário de contato minimalista.

### 3. Performance e Otimização
- [ ] **Remover Dependência de jQuery**: Refatorar todo o JavaScript do template para Vanilla JS (JS puro), eliminando a necessidade de carregar o jQuery por padrão.
- [ ] **Lazy Loading Nativo**: Garantir que o logo e todas as imagens carregadas pelo template usem o atributo `loading="lazy"`.
- [ ] **Suporte PWA**: Criar um arquivo `manifest.json` básico e ícones para que o site possa ser "instalado" como um App mobile.

### 4. Acessibilidade (A11y)
- [ ] **ARIA Landmarks**: Revisar os papéis ARIA (roles) nas seções dinâmicas.
- [ ] **Foco Visual**: Garantir que todos os elementos interativos tenham estados de `:focus` visíveis e elegantes.

---

## 💡 Sugestões de Novas Funcionalidades

- **Parallax Backgrounds**: Adicionar opção nos parâmetros de seção para definir uma imagem de fundo com efeito parallax (fixo ou movimento suave).
- **Animações (Animate.css)**: Integrar a biblioteca Animate.css e permitir escolher animações de entrada (fade, slide, zoom) para seções e posições de módulos.
- **Dark Mode Nativo**: Adicionar um switch de modo escuro/claro que salve a preferência do usuário via localstorage.
- **Custom Font Manager**: Permitir a escolha de Google Fonts diretamente nos parâmetros do template, integrando com o Web Asset Manager para pré-carregamento.
- **Micro-interações**: Adicionar animações sutis de fade-in ao rolar a página (usando Intersection Observer API nativa).

---
> [!IMPORTANT]
> Este arquivo deve ser mantido na pasta `.agent` e atualizado conforme as tarefas forem concluídas.
