<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.minimalista
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
// Include common libraries and dependencies
use Joomla\CMS\Document\Document;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\Folder;
/** @var Joomla\CMS\Document\HtmlDocument $this */
// Database connection setup
$db = Factory::getContainer()->get('DatabaseDriver');
$query = $db->getQuery(true);
$query->select($db->quoteName('parent'))
    ->from($db->quoteName('#__template_styles'))
    ->where($db->quoteName('template') . ' = ' . $db->quote($this->template));
$db->setQuery($query);
$templateParent = $db->loadResult();
$templateOriginal = $templateParent ? $templateParent : $this->template;
// Get the current user object
$user = Factory::getApplication()->getIdentity();
// Get the user group IDs
$userGroupIds = $user->getAuthorisedGroups();
// Initialize an array to hold the user group names
$userGroupNames = [];
// Loop through the user group IDs to get their names
foreach ($userGroupIds as $groupId) {
    $query = $db->getQuery(true)
        ->select($db->quoteName('title'))
        ->from($db->quoteName('#__usergroups'))
        ->where($db->quoteName('id') . ' = ' . (int) $groupId);
    $db->setQuery($query);
    $groupName = $db->loadResult();
    if ($groupName) {
        $userGroupNames[] = $groupName;
    }
}
// Application and document setup
$app   = Factory::getApplication();
$input = $app->getInput();
$wa    = $this->getWebAssetManager();
$doc = $app->getDocument();
$wa = $doc->getWebAssetManager();
$war = $wa->getRegistry();
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
$logo = $templateParams->get('logo', '');
$logo_alt = $templateParams->get('logo_alt', '') ? $templateParams->get('logo_alt') : $sitename;
$doc->addFavicon(Uri::root(true) . '/' . $templateParams->get('favicon', ''));
$doc->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
$custom_css_head = $templateParams->get('custom_css_head', '');
$custom_script_head = $templateParams->get('custom_script_head', '');
$startBodyCode = $templateParams->get('custom_script_startbody', '');
$endBodyCode = $templateParams->get('custom_script_endbody', '');
$backtotop = $templateParams->get('backtotop', '1');
// custom_css_head custom_script_head
if ($custom_css_head) {
    $wa->addInlineStyle($custom_css_head);
}
if ($custom_script_head) {
    $wa->addInlineScript($custom_script_head);
}
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
// Output the user group names as CSS classes
foreach ($userGroupNames as $groupName) {
    $bodyClasses .= ' user-group-' . strtolower($groupName);
}
$containerFluid = $templateParams->get('container-fluid', '0') == '1' ? '-fluid' : '';
$defaultBoostrapDesktop = '-' . $templateParams->get('default-bootstrap-desktop', 'lg');
$sidebarWidth = $defaultBoostrapDesktop . '-' . $templateParams->get('sidebar-width', '3');
if ($this->countModules('sidebar-left') && $this->countModules('sidebar-right')) {
    $mainWidth = $defaultBoostrapDesktop . '-' . (12 - $templateParams->get('sidebar-width', '3') * 2);
} elseif ($this->countModules('sidebar-left') || $this->countModules('sidebar-right')) {
    $mainWidth = $defaultBoostrapDesktop . '-' . (12 - $templateParams->get('sidebar-width', '3'));
} else {
    $mainWidth = $defaultBoostrapDesktop . '-' . '12';
}
// Load jQuery based on template or Joomla configuration
if ($templateParams->get('load_jquery_from_template', 1) == 1) {
    $wa->registerAndUseScript('jquery_from_template', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/jquery-3.7.1.min.js', array('version' => 'auto'));
    $wa->registerAndUseScript('jquery-noconflict', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/jquery-noconflict.js', array('version' => 'auto'));
    $wa->registerAndUseScript('jquery_migrate_from_template', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/jquery-migrate-3.4.0.min.js', array('version' => 'auto'));
} else {
    // Load jQuery from Joomla
    HTMLHelper::_('jquery.framework', true, true);
}
// Load Bootstrap CSS and JavaScript based on template or Joomla configuration
if ($templateParams->get('bootstrap_from_template', 1) == 1) {
    $wa->registerAndUseStyle('bootstrap_css', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/css/bootstrap.min.css', array('version' => 'auto'));
    $wa->registerAndUseScript('bootstrapbundle_js', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/bootstrap.bundle.min.js', array('version' => 'auto'), array('defer' => true));
} else {
    // Load Bootstrap CSS and JavaScript from Joomla
    HTMLHelper::_('bootstrap.loadCss', true, $this->direction);
    HTMLHelper::_('bootstrap.framework');
}
// Load FontAwesome based on template or Joomla configuration
$loadFontAwesome = $templateParams->get('load_fontawesome', 'css_from_template');
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
$cssFilePath = JPATH_ROOT . '/media/templates/site/' . $templateOriginal . '/css/template.css';
if (file_exists($cssFilePath)) {
    $wa->registerAndUseStyle('template-css', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/css/template.css', array('version' => filemtime($cssFilePath)));
}
// Load template-specific JavaScript after jQuery and Bootstrap
$wa->registerAndUseScript('template-js', Uri::root(true) . 'media/templates/site/' . $templateOriginal . '/js/template.js', array('version' => 'auto'), array('defer' => true));
$wa->registerAndUseStyle($this->template . 'template-css', Uri::root(true) . 'media/templates/site/' . $this->template . '/css/template.css', array('version' => 'auto'));
$wa->registerAndUseScript($this->template . 'template-js', Uri::root(true) . 'media/templates/site/' . $this->template . '/js/template.js', array('version' => 'auto'), array('defer' => true));
// Assuming $this is an instance of Document
// media/templates/site/minimalista/css/adtitional/
$additionalcssDirectory = JPATH_ROOT . '/media/templates/site/' . $this->template . '/css/additional';
if (is_dir($additionalcssDirectory)) {
    $additionalcssFiles = Folder::files($additionalcssDirectory, 'css', true, true);
    if (is_array($additionalcssFiles) || is_object($additionalcssFiles)) {
        foreach ($additionalcssFiles as $index => $cssFile) {
            $wa->registerAndUseStyle($this->template . pathinfo($cssFile, PATHINFO_FILENAME), Uri::root(true) . 'media/templates/site/' . $this->template . '/css/additional' . '/' . basename($cssFile), array('version' => 'auto'));
        }
    }
} else {
    // Handle the case where the directory does not exist
    // You can log an error or take appropriate action here
}
$additionaljsDirectory = JPATH_ROOT . '/media/templates/site/' . $this->template . '/js/additional';
if (is_dir($additionaljsDirectory)) {
    $additionaljsFiles = Folder::files($additionaljsDirectory, 'js', true, true);
    if (is_array($additionaljsFiles) || is_object($additionaljsFiles)) {
        foreach ($additionaljsFiles as $index => $jsFile) {
            $wa->registerAndUseScript($this->template . pathinfo($jsFile, PATHINFO_FILENAME), Uri::root(true) . 'media/templates/site/' . $this->template . '/js/additional' . '/' . basename($jsFile), array('version' => 'auto'), array('defer' => true));
        }
    }
} else {
    // Handle the case where the directory does not exist
    // You can log an error or take appropriate action here
}
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
$responsiveCssPath = JPATH_ROOT . '/media/templates/site/' . $this->template . '/css/responsive.css';
if (file_exists($responsiveCssPath)) {
    $wa->registerAndUseStyle($this->template . 'responsive-css', Uri::root(true) . 'media/templates/site/' . $this->template . '/css/responsive.css', array('version' => filemtime($responsiveCssPath)));
}

// load social meta tags OpenGraph for Faceboook, Twitter Cards and Schema.org
if ($templateParams->get('enable_social_meta_tags', 1)) {
    $disable_in = $templateParams->get('disable_in', '');
    if (!is_array($disable_in)) {
        // If 'disable_in' is a string, convert it to an array with a single element
        $disable_in = array($disable_in);
    }
    if (!in_array($option, $disable_in)) {
        $title = $doc->getTitle() ? $doc->getTitle() : $sitename;
        $description = $doc->getDescription() ? $doc->getDescription() : Factory::getConfig()->get('MetaDesc');
        $image = $templateParams->get('social_image', $logo);
        $image_alt = $templateParams->get('social_image_alt', $logo_alt);
        $arrobasite = $templateParams->get('arrobasite', '');
        $arrobacreator = $templateParams->get('arrobaauthor', '');
        // Set common metadata
        setMetadata($doc, $title, $description, $image, $image_alt, $arrobasite, $arrobacreator);
        // Additional metadata for specific conditions and is not homepage
        if ($option == 'com_content' && $view == 'article' && Factory::getApplication()->input->getInt('id') && $active->home != 1) {
            $content = Table::getInstance('content');
            $content->load(Factory::getApplication()->input->getInt('id'));
            $images = null;
            if (!empty($content->images) && is_string($content->images)) {
                $images = json_decode($content->images);
            }
            if ($images) {
                $image = $images->image_intro ? $images->image_intro : $images->image_fulltext;
                $image_alt = $images->image_intro_alt ? $images->image_intro_alt : $images->image_fulltext_alt;
            }
            $textlimit = $templateParams->get('textlimit', 300);
            $text = $content->introtext . $content->fulltext;
            $firstTextImageRegx = '/<img[^>]+src=[\'"](?P<src>[^\'"]+)[\'"][^>]*>/i';
            preg_match($firstTextImageRegx, $text, $matches);
            if (isset($matches['src']) && empty($image)) {
                $image = $matches['src'];
            }
            $text = strip_tags($text);
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);
            $text = substr($text, 0, $textlimit);
            setMetadata($doc, $title, $text, Uri::root() . $image, $image_alt);
        }
        if ($option == 'com_content' && $view == 'category' && Factory::getApplication()->input->getInt('id') && $active->home != 1) {
            $category = Table::getInstance('category');
            $category->load(Factory::getApplication()->input->getInt('id'));
            $textlimit = $templateParams->get('textlimit', 300);
            // Check if the properties exist before accessing them
            $image = property_exists($category, 'image') ? $category->image : '';
            $image_alt = property_exists($category, 'image_alt') ? $category->image_alt : '';
            if (!empty($image)) {
                setMetadata($doc, $title, substr(strip_tags($category->description), 0, $textlimit), Uri::root() . $image, $image_alt);
            }
        }
    }
}
// Function to set common metadata
function setMetadata($doc, $title, $description, $image, $image_alt, $arrobasite = '', $arrobacreator = '')
{
    $doc->setMetaData('og:title', $title);
    $doc->setMetaData('og:description', $description);
    $doc->setMetaData('og:image', Uri::root() . $image);
    // fb:app_id
    $doc->setMetaData('fb:app_id', '');
    $doc->setMetaData('og:image:alt', $image_alt);
    $doc->setMetaData('og:type', 'website');
    $doc->setMetaData('og:url', Uri::root());
    $doc->setMetaData('og:site_name', Factory::getApplication()->get('sitename'));
    $doc->setMetaData('twitter:card', 'summary_large_image');
    $doc->setMetaData('twitter:title', $title);
    $doc->setMetaData('twitter:description', $description);
    $doc->setMetaData('twitter:image', $image);
    $doc->setMetaData('twitter:image:alt', $image_alt);
    $doc->setMetaData('twitter:site', $arrobasite);
    $doc->setMetaData('twitter:creator', $arrobacreator);
    $doc->setMetaData('schema:name', $title);
    $doc->setMetaData('schema:description', $description);
    $doc->setMetaData('schema:image', $image);
    $doc->setMetaData('schema:image:alt', $image_alt);
}
// functions.php
function cleanSectionName($sectionName)
{
    $sectionName = str_replace(' ', '-', $sectionName);
    $sectionName = strtolower($sectionName);
    $sectionName = iconv('UTF-8', 'ASCII//TRANSLIT', $sectionName);
    $sectionName = preg_replace('/[^a-zA-Z0-9\-]/', '', $sectionName);
    return $sectionName;
}
function hasModules($positions, $template)
{
    foreach ($positions as $position) {
        if ($template->countModules($position->position) > 0) {
            return true;
        }
    }
    return false;
}
function renderSection($section, $defaultBoostrapDesktop, $template, $templateOriginal)
{
    $sectionName = cleanSectionName($section->section);
    $hasModules = hasModules($section->positions, $template);
    if ($hasModules) {
        ?>
        <section id="<?php echo $sectionName; ?>"
            class="<?php echo 'section-' . $sectionName . ($section->section_class ? ' ' . $section->section_class : ''); ?>">
            <div class="<?php echo $section->containerwidth; ?>">
                <div class="row">
                    <?php foreach ($section->positions as $position): ?>
                        <div class="<?php echo 'position-' . strtolower($position->position); ?> col<?php echo $defaultBoostrapDesktop . ($position->width ? '-' . $position->width : ''); ?><?php echo $position->customclass ? ' ' . $position->customclass : ''; ?>">
                            <div class="row">
                                <jdoc:include type="modules" name="<?php echo $position->position; ?>" style="<?php echo $templateOriginal . '-default'; ?>" />
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </section>
        <?php
}
}
