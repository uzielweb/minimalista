<?php
// Include common libraries and dependencies
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Filesystem\Folder;

// Database connection setup
$db = Factory::getContainer()->get('DatabaseDriver');
$query = $db->getQuery(true);
$query->select($db->quoteName('parent'))
    ->from($db->quoteName('#__template_styles'))
    ->where($db->quoteName('template') . ' = ' . $db->quote($this->template));
$db->setQuery($query);
$templateParent = $db->loadResult();
$templateOriginal = $templateParent ? $templateParent : $this->template;

// Application and document setup
$app = Factory::getApplication();
$doc = $app->getDocument();
$doc->setHtml5(true);
$doc->setGenerator('');
$user = $app->getIdentity();
$input = $app->input;

// Get input parameters
$option = $input->getCmd('option', '');
$view = $input->getCmd('view', '');
$layout = $input->getCmd('layout', '');
$task = $input->getCmd('task', '');
$itemid = $input->getCmd('Itemid', '');
$sitename = $app->get('sitename', '');

// Get menu and active menu item information
$menu = $app->getMenu();
$active = $menu->getActive();
$activeParams = $active->getParams();
$alias = $active->alias;
$pageclass = $activeParams->get('pageclass_sfx', '');
$parent = $menu->getItem($active->parent_id);
$parentParams = $parent ? $parent->getParams() : '';
$parentAlias = $parent ? $parent->alias : '';
$parentPageclass = $parentParams ? $parentParams->get('pageclass_sfx', '') : '';

// Template-related settings and parameters
$tpath = Uri::root(true) . '/templates/' . $templateOriginal;
$templateParams = $app->getTemplate(true)->params;
$offcanvasDirection = $templateParams->get('offcanvas_direction', 'start');
$wa = $doc->getWebAssetManager();
$war = $wa->getRegistry();
$logo = $templateParams->get('logo', '');
$logo_alt = $templateParams->get('logo_alt', '') ? $templateParams->get('logo_alt') : $sitename;
$doc->addFavicon(Uri::root(true) . '/' . $templateParams->get('favicon', ''));
$doc->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
$custom_css_head = $templateParams->get('custom_css_head', '');
$custom_script_head = $templateParams->get('custom_script_head', '');
$startBodyCode  = $templateParams->get('custom_script_startbody', '');
$endBodyCode  = $templateParams->get('custom_script_endbody', '');
$backtotop = $templateParams->get('backtotop', '1');
$doc->addStyleDeclaration($custom_css_head);
$doc->addScriptDeclaration($custom_script_head);
// Generate CSS classes for the <body> element
$bodyClasses = ($option ? 'option-' . str_replace('com_', '', $option) : 'no-option')
    . ' ' . ($view ? 'view-' . $view : 'no-view')
    . ' ' . ($layout ? 'layout-' . $layout : 'no-layout')
    . ' ' . ($task ? 'task-' . $task : 'no-task')
    . ' ' . ($itemid ? 'itemid-' . $itemid : 'no-itemid')
    . ' ' . ($alias ? 'alias-' . $alias : 'no-alias')
    . ' ' . ($pageclass ? $pageclass : 'no-pageclass')
    . ' ' . ($parentAlias ? 'parent-' . $parentAlias : 'no-parent')
    . ' ' . ($parentPageclass ? $parentPageclass : 'no-parent-pageclass')
    . ' ' . ($doc->getDirection() === 'rtl' ? 'direction-rtl' : 'direction-ltr')
    . ' ' . ($user->guest ? 'user-guest' : 'user-logged-in');
$containerFluid = $templateParams->get('container-fluid', 0) ? '-fluid' : '';
$defaultBoostrapDesktop = '-' . $templateParams->get('default-bootstrap-desktop', 'lg');
$sidebarWidth = $defaultBoostrapDesktop . '-' . $templateParams->get('sidebar-width', '3');

// Load jQuery based on template or Joomla configuration
if ($templateParams->get('load_jquery_from_template') == 1) {
    $wa->registerAndUseScript('jquery_from_template', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/jquery-3.7.0.min.js', array('version' => 'auto'));
    $wa->registerAndUseScript('jquery-noconflict', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/jquery-noconflict.js', array('version' => 'auto'));
    $wa->registerAndUseScript('jquery_migrate_from_template', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/jquery-migrate-3.4.0.min.js', array('version' => 'auto'));
} else {
    // Load jQuery from Joomla
    HTMLHelper::_('jquery.framework', true, true);
}

// Load Bootstrap CSS and JavaScript based on template or Joomla configuration
if ($templateParams->get('bootstrap_from_template')) {
    $wa->registerAndUseStyle('bootstrap_css', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/css/bootstrap.min.css', array('version' => 'auto'));
    $wa->registerAndUseScript('bootstrapbundle_js', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/bootstrap.bundle.min.js', array('version' => 'auto'), array('defer' => true));
} else {
    // Load Bootstrap CSS and JavaScript from Joomla
    HTMLHelper::_('bootstrap.loadCss', true, $this->direction);
    HTMLHelper::_('bootstrap.framework');
}

// Load FontAwesome based on template or Joomla configuration
$loadFontAwesome = $templateParams->get('load_fontawesome', 'css_from_joomla');
if ($loadFontAwesome == 'css_from_template') {
    $wa->registerAndUseStyle('fontawesome_css', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/css/all.min.css', array('version' => 'auto'));
} elseif ($loadFontAwesome == 'js_from_template') {
    $wa->registerAndUseScript('fontawesome_js', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/all.min.js', array('version' => 'auto'), array('defer' => true));
} elseif ($loadFontAwesome == 'css_from_joomla') {
    // Load FontAwesome from Joomla
    $wa->registerAndUseStyle('fontawesome', 'media/vendor/fontawesome-free/css/all.min.css', array('version' => 'auto'));
} else {
    // Do nothing for FontAwesome
}

// Load Joomla 4 system icons
$wa->registerAndUseStyle('icons', 'media/system/css/joomla-fontawesome.min.css', array('version' => 'auto'));
$wa->registerAndUseStyle('template-css', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/css/template.css', array('version' => 'auto'));

// Load template-specific JavaScript after jQuery and Bootstrap
$wa->registerAndUseScript('template-js', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/template.js', array('version' => 'auto'), array('defer' => true));
$wa->registerAndUseStyle($this->template . 'template-css', Uri::root(true) . 'media/templates/site/' . $this->template . '/css/template.css', array('version' => 'auto'));
$wa->registerAndUseScript($this->template . 'template-js', Uri::root(true) . 'media/templates/site/' . $this->template . '/js/template.js', array('version' => 'auto'), array('defer' => true));

// Scan template CSS folder and load all CSS files with "custom" in the name for the original template
$customCssDirectoryOriginalTemplate = JPATH_ROOT . '/media/templates/site/' . $templateOriginal . '/css';

if (is_dir($customCssDirectoryOriginalTemplate)) {
    $customCssFilesOriginalTemplate = Folder::files($customCssDirectoryOriginalTemplate, 'custom', true, true);
    if (is_array($customCssFilesOriginalTemplate) || is_object($customCssFilesOriginalTemplate)) {
        foreach ($customCssFilesOriginalTemplate as $index => $cssFile) {
            $wa->registerAndUseStyle(pathinfo($cssFile, PATHINFO_FILENAME), Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/css' . '/' . basename($cssFile), array('version' => 'auto'));
        }
    }
} else {
    // Handle the case where the directory does not exist
    // You can log an error or take appropriate action here
}

// Scan template CSS folder and load all CSS files with "customfont" in the name for the current template
$customFontCssDirectoryCurrentTemplate = JPATH_ROOT . '/media/templates/site/' . $this->template . '/css';

if (is_dir($customFontCssDirectoryCurrentTemplate)) {
    $customFontCssFilesCurrentTemplate = Folder::files($customFontCssDirectoryCurrentTemplate, 'customfont', true, true);
    if (is_array($customFontCssFilesCurrentTemplate) || is_object($customFontCssFilesCurrentTemplate)) {
        foreach ($customFontCssFilesCurrentTemplate as $index => $cssFile) {
            $wa->registerAndUseStyle($this->template . pathinfo($cssFile, PATHINFO_FILENAME), Uri::root(true) . 'media/templates/site/' . $this->template . '/css' . '/' . basename($cssFile), array('version' => 'auto'));
        }
    }
} else {
    // Handle the case where the directory does not exist
    // You can log an error or take appropriate action here
}

// Scan template CSS folder and load all CSS files with "custom" in the name for the current template
$customCssDirectoryCurrentTemplate = JPATH_ROOT . '/media/templates/site/' . $this->template . '/css';

if (is_dir($customCssDirectoryCurrentTemplate)) {
    $customCssFilesCurrentTemplate = Folder::files($customCssDirectoryCurrentTemplate, 'custom', true, true);
    if (is_array($customCssFilesCurrentTemplate) || is_object($customCssFilesCurrentTemplate)) {
        foreach ($customCssFilesCurrentTemplate as $index => $cssFile) {
            $wa->registerAndUseStyle($this->template . pathinfo($cssFile, PATHINFO_FILENAME), Uri::root(true) . 'media/templates/site/' . $this->template . '/css' . '/' . basename($cssFile), array('version' => 'auto'));
        }
    }
} else {
    // Handle the case where the directory does not exist
    // You can log an error or take appropriate action here
}

// Scan template JS folder and load all JS files with "custom" in the name for the original template
$customJsDirectoryOriginalTemplate = JPATH_ROOT . '/media/templates/site/' . $templateOriginal . '/js';

if (is_dir($customJsDirectoryOriginalTemplate)) {
    $customJsFilesOriginalTemplate = Folder::files($customJsDirectoryOriginalTemplate, 'custom', true, true);
    if (is_array($customJsFilesOriginalTemplate) || is_object($customJsFilesOriginalTemplate)) {
        foreach ($customJsFilesOriginalTemplate as $index => $jsFile) {
            $wa->registerAndUseScript(pathinfo($jsFile, PATHINFO_FILENAME), Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js' . '/' . basename($jsFile), array('version' => 'auto'), array('defer' => true));
        }
    }
} else {
    // Handle the case where the directory does not exist
    // You can log an error or take appropriate action here
}

// Scan template JS folder and load all JS files with "custom" in the name for the current template
$customJsDirectoryCurrentTemplate = JPATH_ROOT . '/media/templates/site/' . $this->template . '/js';

if (is_dir($customJsDirectoryCurrentTemplate)) {
    $customJsFilesCurrentTemplate = Folder::files($customJsDirectoryCurrentTemplate, 'custom', true, true);
    if (is_array($customJsFilesCurrentTemplate) || is_object($customJsFilesCurrentTemplate)) {
        foreach ($customJsFilesCurrentTemplate as $index => $jsFile) {
            $wa->registerAndUseScript($this->template . pathinfo($jsFile, PATHINFO_FILENAME), Uri::root(true) . 'media/templates/site/' . $this->template . '/js' . '/' . basename($jsFile), array('version' => 'auto'), array('defer' => true));
        }
    }
} else {
    // Handle the case where the directory does not exist
    // You can log an error or take appropriate action here
}
