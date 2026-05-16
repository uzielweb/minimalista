# Projeto Minimalista - Sugestões e TODOs

Este documento contém uma análise técnica da aplicação e sugestões de melhorias para elevar a qualidade do template para um nível "Premium" e garantir compatibilidade total com Joomla 5/6.

## ✅ Concluído na Versão Atual (v2.12.24)
- **Remoção de jQuery**: O template agora é 100% independente de jQuery no seu núcleo (Vanilla JS).
- **Sistema de Animações**: Integração com Animate.css e gatilho de scroll inteligente (`animate__on-scroll`).
- **Limpeza de Repositório**: Remoção de workflows obsoletos e limpeza de histórico do GitHub Actions.
- **Release Manual**: Estabelecido protocolo de versionamento via GitHub CLI (`gh`).

---

## 🚀 Próximos Passos (TODOs)

### 1. Performance e Otimização
- [ ] **Lazy Loading Nativo**: Garantir que o logo e todas as imagens carregadas pelo template usem o atributo `loading="lazy"`.
- [ ] **Suporte PWA**: Criar um arquivo `manifest.json` básico e ícones para que o site possa ser "instalado" como um App mobile.

### 2. Novas Funcionalidades Premium
- [ ] **Dark Mode Nativo**: Adicionar um switch de modo escuro/claro que salve a preferência do usuário via localstorage.
- [ ] **Custom Font Manager**: Permitir a escolha de Google Fonts diretamente nos parâmetros do template, integrando com o Web Asset Manager para pré-carregamento.
- [ ] **Parallax Backgrounds**: Adicionar opção nos parâmetros de seção para definir uma imagem de fundo com efeito parallax (fixo ou movimento suave).

### 3. SEO e Metadados Avançados
- [ ] **Integração com API Schema.org (Joomla 5/6)**: Auditar tags meta legadas e integrar com a nova API de Schema do Joomla para evitar duplicação de JSON-LD.
- [ ] **Theme Color**: Suporte à tag meta `theme-color` baseada nos parâmetros do template.

### 4. Experiência do Usuário (UX)
- [ ] **Overrides de Conteúdo**: Criar layouts minimalistas para `com_content` (Artigo e Categoria).
- [ ] **Redesign de Páginas de Sistema**: Novos designs para `error.php` e `offline.php`.

---

## 🔮 Visão de Futuro (Longo Prazo)

- [ ] **Gerenciador de Layout Visual (Drag & Drop)**: Evoluir o sistema de Subforms para uma interface visual de arrastar e soltar.
- [ ] **Ecossistema de Presets**: Salvar e carregar "esquemas" de layout prontos.

---
> [!IMPORTANT]
> Este arquivo deve ser mantido na pasta `.agent` e atualizado conforme as tarefas forem concluídas.
