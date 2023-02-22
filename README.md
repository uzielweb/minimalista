# Minimalista Joomla 4 Template
## Português (Brasil)
<!-- definição  e descrição do template -->
Este é um template {Joomla!} 4, com o objetivo de ser o mais simples possível, sem quaisquer recursos adicionais, apenas o básico para um template funcional.
Nele você encontra os itens básicos para um bom template, como:
- [x] 1. Override de arquivos de layout para o menu
- [x] 2. Override de arquivos de layout para os estilos de módulos (chromes)
- [x] 3. Um arquivo com toda a lógica necessária para o tema dentro do arquivo logic.php
- [x] 4. arquivos básicos para a página de erros, offline e o layout de component (chamado pela ?view=component)

As seguintes configurações são possíveis em seu administrador:
- [x] 1. Largura padrão das colunas laterais (esquerda e direita) em bootstrap
- [x] 2. Definição de qual é o dispositivo padrão para a largura das colunas em geral (desktop, tablet ou mobile => xxl, xl, lg, md, sm, xs)
- [x] 3. Escolher a largura padrão do container em bootstrap (container, container-fluid)
- [x] 4. Permite criar templates filhos

Por padrão, o template vem com Bootstrap e Font Awesome do Joomla 4, mas você pode escolher usar o Bootstrap e o Font Awesome do template, ou nenhum deles.
Para isso basta adicionar o seguinte código no seu arquivo logic.php (lembrando de desativar o Bootstrap e o Font Awesome do Joomla 4 nas linhas do logic.php)
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

// Use Bootstrap do template
$wa->registerAndUseStyle('template.minimalista.bootstrap', 'templates/' . $this->template . '/css/bootstrap.min.css', [], ['version' => 'auto']);
// Use Font Awesome do Template
$wa->registerAndUseStyle('template.minimalista.fontawesome', 'templates/' . $this->template . '/css/fontawesome.min.css', [], ['version' => 'auto']);
// bootstrap.bundle.min.js do template
$wa->registerAndUseScript('template.minimalista.bootstrap.bundle', 'templates/' . $this->template . '/js/bootstrap.bundle.min.js', [], ['version' => 'auto']);
// JQuery do template
$wa->registerAndUseScript('template.minimalista.jquery', 'templates/' . $this->template . '/js/jquery-3.6.0.min.js', [], ['version' => 'auto']);
```


## English (United States)
<!-- definition and description of the template -->
This is a {Joomla!} 4 template, with the aim of being as simple as possible, without any additional features, just the basics for a functional template.
In it you find the basic items for a good template, such as:
- [x] 1. Override of layout files for the menu
- [x] 2. Override of layout files for module styles (chromes)
- [x] 3. A file with all the necessary logic for the theme within the logic.php file
- [x] 4. basic files for the error, offline and component page layouts (called by ?view=component)

The following settings are possible in your administrator:
- [x] 1. Default width of the side columns (left and right) in bootstrap
- [x] 2. Definition of which is the default device for the width of the columns in general (desktop, tablet or mobile => xxl, xl, lg, md, sm, xs)
- [x] 3. Choose the default width of the container in bootstrap (container, container-fluid)
- [x] 4. It's possible to create child templates

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


