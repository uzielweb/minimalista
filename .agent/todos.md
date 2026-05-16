# Projeto Minimalista - Sugestões e TODOs

Este documento contém uma análise técnica da aplicação e sugestões de melhorias para elevar a qualidade do template para um nível "Premium" e garantir compatibilidade total com Joomla 5/6.

## ✅ Concluído na Versão Atual (v2.12.25)
- **Remoção de jQuery**: O template agora é 100% independente de jQuery no seu núcleo (Vanilla JS).
- **Sistema de Animações**: Integração com Animate.css e gatilho de scroll inteligente (`animate__on-scroll`).
- **Lazy Loading Nativo**: Adicionado suporte nativo `loading="lazy"` para todos os logos e imagens estruturais.
- **Dark Mode Nativo**: Sistema integrado com Bootstrap 5, alternador na navbar e persistência via `localStorage`.
- **Custom Font Manager**: Interface nas configurações para integração fácil de fontes externas (Google Fonts, etc).
- **Limpeza de Repositório**: Remoção de workflows obsoletos e limpeza de histórico do GitHub Actions.
- **Release Manual**: Estabelecido protocolo de versionamento via GitHub CLI (`gh`).

---

## 🚀 Próximos Passos (TODOs)

### 1. Performance e Otimização

### 3. SEO e Metadados Avançados
- [ ] **Theme Color**: Suporte à tag meta `theme-color` baseada nos parâmetros do template.

### 4. Experiência do Usuário (UX)
- [ ] **Overrides de Conteúdo**: Criar layouts minimalistas para `com_content` (Artigo e Categoria).
- [ ] **Redesign de Páginas de Sistema**: Novos designs para `error.php` e `offline.php`.

### 5. Acessibilidade (A11y)
- [ ] **ARIA Landmarks**: Revisar os papéis ARIA (roles) nas seções dinâmicas.
- [ ] **Foco Visual**: Garantir que todos os elementos interativos tenham estados de `:focus` visíveis e elegantes.

---

## 🔮 Visão de Futuro (Longo Prazo)

- [ ] **Gerenciador de Layout Visual (Drag & Drop)**: Evoluir o sistema de Subforms para uma interface visual de arrastar e soltar.
- [ ] **Ecossistema de Presets**: Salvar e carregar "esquemas" de layout prontos para diferentes tipos de sites.

---
> [!IMPORTANT]
> Este arquivo deve ser mantido na pasta `.agent` e atualizado conforme as tarefas forem concluídas.
