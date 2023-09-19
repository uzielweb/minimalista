<?php
// this is the logic.php file with the code that is common to both index.php, offline.php and error.php and other files
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\Folder;
$app = Factory::getApplication();
$doc = $app->getDocument();
// minimize the output html
$doc->setHtml5(true);
$doc->setGenerator('');
$user = $app->getIdentity();
$input = $app->input;
$option = $input->getCmd('option', '');
$view = $input->getCmd('view', '');
$layout = $input->getCmd('layout', '');
$task = $input->getCmd('task', '');
$itemid = $input->getCmd('Itemid', '');
$sitename = $app->get('sitename', '');
$menu = $app->getMenu();
$active = $menu->getActive();
$activeParams = $active->getParams();
$alias = $active->alias;
$pageclass = $activeParams->get('pageclass_sfx', '');
$parent = $menu->getItem($active->parent_id);
$parentParams = $parent ? $parent->getParams() : '';
$parentAlias = $parent ? $parent->alias : '';
$parentPageclass = $parentParams ? $parentParams->get('pageclass_sfx', '') : '';
$tpath = Uri::root(true) . '/templates/' . $this->template;
$templateParams = $app->getTemplate(true)->params;
$offcanvasDirection = $templateParams->get('offcanvas_direction', 'start');
$wa = $doc->getWebAssetManager();
$war = $wa->getRegistry();
$logo = $templateParams->get('logo', '');
$logo_alt = $templateParams->get('logo_alt', '') ? $templateParams->get('logo_alt') : $sitename;
$doc->addFavicon(Uri::root(true) . '/' . $templateParams->get('favicon', ''));
$doc->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
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
// jquery from template or from joomla
if ($templateParams->get('load_jquery_from_template') == 1) {
    $wa->registerAndUseScript('jquery_from_template', Uri::root(true) . 'media/templates/site/' . $this->template . '/js/jquery-3.7.0.min.js', array('version' => 'auto'));
    $wa->registerAndUseScript('jquery-noconflict', Uri::root(true) . 'media/templates/site/' . $this->template . '/js/jquery-noconflict.js', array('version' => 'auto'));
    $wa->registerAndUseScript('jquery_migrate_from_template', Uri::root(true) . 'media/templates/site/' . $this->template . '/js/jquery-migrate-3.4.0.min.js', array('version' => 'auto'));
} else {
    // load jquery
    HTMLHelper::_('jquery.framework', true, true);
}
//  if params is bootstrap from template
if ($templateParams->get('bootstrap_from_template')) {
    $wa->registerAndUseStyle('bootstrap_css', Uri::root(true) . 'media/templates/site/' . $this->template . '/css/bootstrap.min.css', array('version' => 'auto'));
    $wa->registerAndUseScript('bootstrapbundle_js', Uri::root(true) . 'media/templates/site/' . $this->template . '/js/bootstrap.bundle.min.js', array('version' => 'auto'), array('defer' => true));
} else {
    // load bootstrap css
    HTMLHelper::_('bootstrap.loadCss', true, $this->direction);
    // load bootstrap js
    HTMLHelper::_('bootstrap.framework');
}
$loadFontAwesome = $templateParams->get('load_fontawesome', 'css_from_joomla');
//  if params is load fontawesome from template
if ($loadFontAwesome == 'css_from_template') {
    $wa->registerAndUseStyle('fontawesome_css', Uri::root(true) . 'media/templates/site/' . $this->template . '/css/all.min.css', array('version' => 'auto'));
} elseif ($loadFontAwesome == 'js_from_template') {
    $wa->registerAndUseScript('fontawesome_js', Uri::root(true) . 'media/templates/site/' . $this->template . '/js/all.min.js', array('version' => 'auto'), array('defer' => true));
} elseif ($loadFontAwesome == 'css_from_joomla') {
    // load fontawesome
    $wa->registerAndUseStyle('fontawesome', 'media/vendor/fontawesome-free/css/all.min.css', array('version' => 'auto'));
} else {
    // nothing
}
// load Joomla 4 system icons
$wa->registerAndUseStyle('icons', 'media/system/css/joomla-fontawesome.min.css', array('version' => 'auto'));
$wa->registerAndUseStyle('template-css', Uri::root(true) . 'media/templates/site/' . $this->template . '/css/template.css', array('version' => 'auto'));
//  load template js only after jquery and bootstrap
$wa->registerAndUseScript('template-js', Uri::root(true) . 'media/templates/site/' . $this->template . '/js/template.js', array('version' => 'auto'), array('defer' => true));
//  scan template css folder and load all css files with "customfont" in the name
foreach (Folder::files(JPATH_ROOT . '/media/templates/site/' . $this->template . '/css', 'customfont', true, true) as $i => $file) {
    $wa->registerAndUseStyle(pathinfo($file, PATHINFO_FILENAME), Uri::root(true) . 'media/templates/site/' . $this->template . '/css' . '/' . basename($file), array('version' => 'auto'));
}
//  scan template css folder and load all css files with "custom" in the name
foreach (Folder::files(JPATH_ROOT . '/media/templates/site/' . $this->template . '/css', 'custom', true, true) as $i => $file) {
    $wa->registerAndUseStyle(pathinfo($file, PATHINFO_FILENAME), Uri::root(true) . 'media/templates/site/' . $this->template . '/css' . '/' . basename($file), array('version' => 'auto'));
}
//  scan template css folder and load all css files with "custom" in the name
foreach (Folder::files(JPATH_ROOT . '/media/templates/site/' . $this->template . '/js', 'custom', true, true) as $i => $file) {
    $wa->registerAndUseScript(pathinfo($file, PATHINFO_FILENAME), Uri::root(true) . 'media/templates/site/' . $this->template . '/js' . '/' . basename($file), array('version' => 'auto'), array('defer' => true));
}
