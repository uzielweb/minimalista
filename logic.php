<?php
// this is the logic.php file with the code that is common to both index.php, offline.php and error.php and other files
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
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
$wa = $doc->getWebAssetManager();
$war = $wa->getRegistry();
$bodyClasses = $option ? 'option-' . str_replace('com_', '', $option) : 'no-option'
    . ' ' . ($view ? 'view-' . $view : 'no-view')
    . ' ' . ($layout ? 'layout-' . $layout : 'no-layout')
    . ' ' . ($task ? 'task-' . $task : 'no-task')
    . ' ' . ($itemid ? 'itemid-' . $itemid : 'no-itemid')
    . ' ' . ($alias ? 'alias-' . $alias : 'no-alias')
    . ' ' . ($pageclass ? $pageclass : '')
    . ' ' . ($parentAlias ? 'parent-' . $parentAlias : 'no-parent')
    . ' ' . ($parentPageclass ? $parentPageclass : '')
    . ' ' . ($doc->getDirection() === 'rtl' ? 'rtl' : 'ltr')
    . ' ' . ($user->guest ? 'guest' : 'logged-in');
$containerFluid = $templateParams->get('container-fluid', 0) ? '-fluid' : '';
$defaultBoostrapDesktop = '-' . $templateParams->get('default-bootstrap-desktop', 'lg');
$sidebarWidth = $defaultBoostrapDesktop . '-' . $templateParams->get('sidebar-width', '3');
// load jquery
HTMLHelper::_('jquery.framework', true, true);
// load bootstrap
HTMLHelper::_('bootstrap.framework');
// load bootstrap css
HTMLHelper::_('bootstrap.loadCss', true, $this->direction);
// load joomla 4 fontawesome
$wa->registerAndUseStyle('fontawesome', 'media/vendor/fontawesome-free/css/fontawesome.min.css', array('version' => 'auto'));
// load Joomal 4 system icons
$wa->registerAndUseStyle('icons', 'media/system/css/joomla-fontawesome.min.css', array('version' => 'auto'));
$wa->registerAndUseStyle('template-css', Uri::root(true) . 'media/templates/site/' . $this->template . '/css/template.css', array('version' => 'auto'));

//  load template js only after jquery and bootstrap
$wa->registerAndUseScript('template-js', Uri::root(true) . 'media/templates/site/' . $this->template . '/js/template.js', array('version' => 'auto'), array('defer' => true));

