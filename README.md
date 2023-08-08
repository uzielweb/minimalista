# Minimalista Joomla 4 Template
## Português (Brasil)
<!-- definição  e descrição do template -->
Este é um template {Joomla!} 4, com o objetivo de ser o mais simples possível, sem quaisquer recursos adicionais, apenas o básico para um template funcional.
Nele você encontra os itens básicos para um bom template, como:
- [x] 1. Override de arquivos de layout para o menu
- [x] 2. Override de arquivos de layout para os estilos de módulos (chromes)
- [x] 3. Um arquivo com toda a lógica necessária para o tema dentro do arquivo logic.php
- [x] 4. arquivos básicos para a página de erros, offline e o layout de component (chamado pela ?view=component)
- [x] 5. Possui um menu offcanvas básico

As seguintes configurações são possíveis em seu administrador:
- [x] 1. Largura padrão das colunas laterais (esquerda e direita) em bootstrap
- [x] 2. Definição de qual é o dispositivo padrão para a largura das colunas em geral (desktop, tablet ou mobile => xxl, xl, lg, md, sm, xs)
- [x] 3. Escolher a largura padrão do container em bootstrap (container, container-fluid)
- [x] 4. Permite criar templates filhos
- [x] 5. Permite escolher o modo de carregamento do offcanvas (esquerda, direita, acima, abaixo)
- [x] 6. Permite escolher a posição do botão de fechamento e de abertura do offcanvas

Por padrão, o template vem com Bootstrap e Font Awesome do Joomla 4, mas você pode escolher usar o Bootstrap e o Font Awesome do template, ou nenhum deles.



## English (United States)
<!-- definition and description of the template -->
This is a Joomla! 4 template, aiming to be as simple as possible, without any additional features, providing only the essentials for a functional template. Within it, you'll find the fundamental components for a quality template, such as:

- [x] Override of layout files for the menu
- [x] Override of layout files for module styles (chromes)
- [x] A file containing all the necessary logic for the theme within the logic.php file
- [x] Basic files for the error page, offline page, and component layout (called by ?view=component)
- [x] It features a basic offcanvas menu

The following configurations are possible within your administrator:

- [x] Default width of side columns (left and right) in bootstrap
- [x] Definition of the default device for column width in general (desktop, tablet, or mobile => xxl, xl, lg, md, sm, xs)
- [x] Choice of default width for the bootstrap container (container, container-fluid)
- [x] Allows the creation of child templates
- [x] Permits choosing the loading mode of the offcanvas (left, right, above, below)
- [x] Lets you choose the position of the offcanvas close and open buttons

By default, the template comes with Bootstrap and Font Awesome from Joomla 4, but you can choose to use the Bootstrap and Font Awesome from the template, or none of them.

To do this, just add the following code to your logic.php file (remembering to disable Bootstrap and Font Awesome from Joomla 4 in the lines of logic.php)
```php
// load jquery
# HTMLHelper::_('jquery.framework', true, true);
// load bootstrap
# HTMLHelper::_('bootstrap.framework');
// load bootstrap css
# HTMLHelper::_('bootstrap.loadCss', true, $this->direction);
// load joomla 4 fontawesome
# $wa->registerAndUseStyle('fontawesome', 'media/vendor/fontawesome-free/css/fontawesome.min.css', array('version' => 'auto'));
// load Joomla 4 system icons
$wa->registerAndUseStyle('icons', 'media/system/css/joomla-fontawesome.min.css', array('version' => 'auto'));
$wa->registerAndUseStyle('template-css', Uri::root(true) . 'media/templates/site/' . $this->template . '/css/template.css', array('version' => 'auto'));

// Use Bootstrap from template
$wa->registerAndUseStyle('template.minimalista.bootstrap', 'templates/' . $this->template . '/css/bootstrap.min.css', [], ['version' => 'auto']);
// Use Font Awesome from Template
$wa->registerAndUseStyle('template.minimalista.fontawesome', 'templates/' . $this->template . '/css/fontawesome.min.css', [], ['version' => 'auto']);
// bootstrap.bundle.min.js from template
$wa->registerAndUseScript('template.minimalista.bootstrap.bundle', 'templates/' . $this->template . '/js/bootstrap.bundle.min.js', [], ['version' => 'auto']);
// JQuery from template
$wa->registerAndUseScript('template.minimalista.jquery', 'templates/' . $this->template . '/js/jquery-3.6.0.min.js', [], ['version' => 'auto']);
```

