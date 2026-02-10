<?php
    /**
 * @package     Joomla.Site (4, 5 & 6)
 * @subpackage  Templates.minimalista
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
    defined('_JEXEC') or die;
    // Include common libraries and dependencies
    use Joomla\CMS\Document\Document;
    use Joomla\CMS\Factory;
    use Joomla\CMS\Helper\UserGroupsHelper;
    use Joomla\CMS\HTML\HTMLHelper;
    use Joomla\CMS\Table\Table;
    use Joomla\CMS\Uri\Uri;
    use Joomla\Filesystem\Folder;
    /** @var Joomla\CMS\Document\HtmlDocument $this */
    // Application and document setup
    $app   = Factory::getApplication();
    $input = $app->getInput();
    $doc   = $app->getDocument();

    // Template-related settings and parameters - Joomla 4/5/6 native way
    $activeStyle      = $app->getTemplate(true);
    $templateOriginal = $activeStyle->parent ?: $activeStyle->template;
    // Get the current user object and group titles using the native Joomla helper (no manual SQL)
    $user           = $app->getIdentity();
    $userGroupIds   = $user->getAuthorisedGroups();
    $userGroupNames = [];
    $allGroups      = UserGroupsHelper::getInstance()->getAll();

    foreach ($userGroupIds as $groupId) {
    if (isset($allGroups[$groupId])) {
        $userGroupNames[] = $allGroups[$groupId]->title;
    }
    }
    $wa  = $doc->getWebAssetManager();
    $war = $wa->getRegistry();
    $doc->setHtml5(true);
    $doc->setGenerator('');
    // Get input parameters
    $option   = $input->getCmd('option', '');
    $view     = $input->getCmd('view', '');
    $layout   = $input->getCmd('layout', '');
    $task     = $input->getCmd('task', '');
    $itemid   = $input->getCmd('Itemid', '');
    $sitename = $app->get('sitename', '');
    // Get menu and active menu item information
    $menu            = $app->getMenu();
    $active          = $menu->getActive();
    $activeParams    = $active ? $active->getParams() : null;
    $alias           = $active ? $active->alias : '';
    $pageclass       = $activeParams ? $activeParams->get('pageclass_sfx', '') : '';
    $parent          = $active ? $menu->getItem($active->parent_id) : null;
    $parentParams    = $parent ? $parent->getParams() : null;
    $parentAlias     = $parent ? $parent->alias : '';
    $parentPageclass = $parentParams ? $parentParams->get('pageclass_sfx', '') : '';
    // Template-related settings and parameters
    $tpath              = 'templates/' . $templateOriginal;
    $templateParams     = $activeStyle->params;
    $offcanvasDirection = $templateParams->get('offcanvas_direction', 'start');
    $logo               = $templateParams->get('logo', '');
    $logo_alt           = $templateParams->get('logo_alt', '') ? $templateParams->get('logo_alt') : $sitename;
    $logo_position      = $templateParams->get('logo_position', 'header-navbar');
    $doc->addFavicon(Uri::root(true) . '/' . $templateParams->get('favicon', ''));
    $doc->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
    $custom_css_head    = $templateParams->get('custom_css_head', '');
    $custom_script_head = $templateParams->get('custom_script_head', '');
    $startBodyCode      = $templateParams->get('custom_script_startbody', '');
    $endBodyCode        = $templateParams->get('custom_script_endbody', '');
    $custom_head_code   = $templateParams->get('custom_head_code', '');
    $backtotop          = $templateParams->get('backtotop', '1');
    // custom_css_head custom_script_head
    if ($custom_css_head) {
    $wa->addInlineStyle($custom_css_head);
    }
    if ($custom_script_head) {
    $wa->addInlineScript($custom_script_head);
    }
    if ($custom_head_code) {
    $doc->addCustomTag($custom_head_code);
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
    $containerFluid         = $templateParams->get('container-fluid', '0') == '1' ? '-fluid' : '';
    $defaultBoostrapDesktop = '-' . $templateParams->get('default-bootstrap-desktop', 'lg');
    $sidebarWidth           = $defaultBoostrapDesktop . '-' . $templateParams->get('sidebar-width', '3');
    if ($this->countModules('sidebar-left') && $this->countModules('sidebar-right')) {
    $mainWidth = $defaultBoostrapDesktop . '-' . (12 - $templateParams->get('sidebar-width', '3') * 2);
    } elseif ($this->countModules('sidebar-left') || $this->countModules('sidebar-right')) {
    $mainWidth = $defaultBoostrapDesktop . '-' . (12 - $templateParams->get('sidebar-width', '3'));
    } else {
    $mainWidth = $defaultBoostrapDesktop . '-' . '12';
    }
    // Load jQuery based on template or Joomla configuration
    if ($templateParams->get('load_jquery_from_template', 1) == 1) {
    $wa->registerAndUseScript('jquery_from_template', 'media/templates/site/' . $templateOriginal . '/js/jquery-3.7.1.min.js', ['version' => 'auto']);
    $wa->registerAndUseScript('jquery-noconflict', 'media/templates/site/' . $templateOriginal . '/js/jquery-noconflict.js', ['version' => 'auto']);
    $wa->registerAndUseScript('jquery_migrate_from_template', 'media/templates/site/' . $templateOriginal . '/js/jquery-migrate-3.4.0.min.js', ['version' => 'auto']);
    } else {
    // Load jQuery from Joomla
    HTMLHelper::_('jquery.framework', true, true);
    }
    // Load Bootstrap CSS and JavaScript based on template or Joomla configuration
    if ($templateParams->get('bootstrap_from_template', 1) == 1) {
    $wa->registerAndUseStyle('bootstrap_css', 'media/templates/site/' . $templateOriginal . '/css/bootstrap.min.css', ['version' => 'auto']);
    $wa->registerAndUseScript('bootstrapbundle_js', 'media/templates/site/' . $templateOriginal . '/js/bootstrap.bundle.min.js', ['version' => 'auto'], ['defer' => true]);
    } else {
    // Load Bootstrap CSS and JavaScript from Joomla
    HTMLHelper::_('bootstrap.loadCss', true, $this->direction);
    HTMLHelper::_('bootstrap.framework');
    }
    // Load FontAwesome based on template or Joomla configuration
    $loadFontAwesome = $templateParams->get('load_fontawesome', 'css_from_template');
    if ($loadFontAwesome == 'css_from_template') {
    $wa->registerAndUseStyle('fontawesome_css', 'media/templates/site/' . $templateOriginal . '/css/all.min.css', ['version' => 'auto']);
    } elseif ($loadFontAwesome == 'js_from_template') {
    $wa->registerAndUseScript('fontawesome_js', 'media/templates/site/' . $templateOriginal . '/js/all.min.js', ['version' => 'auto'], ['defer' => true]);
    } elseif ($loadFontAwesome == 'css_from_joomla') {
    // Load FontAwesome from Joomla
    $wa->registerAndUseStyle('fontawesome', 'media/vendor/fontawesome-free/css/all.min.css', ['version' => 'auto']);
    } else {
    // Do nothing for FontAwesome
    }
    // Load Joomla 4 system icons
    $wa->registerAndUseStyle('icons', 'media/system/css/joomla-fontawesome.min.css', ['version' => 'auto']);
    $cssFilePath = JPATH_ROOT . '/media/templates/site/' . $templateOriginal . '/css/template.css';
    if (file_exists($cssFilePath)) {
    $wa->registerAndUseStyle('template-css', 'media/templates/site/' . $templateOriginal . '/css/template.css', ['version' => filemtime($cssFilePath)]);
    }
    // Load template-specific JavaScript after jQuery and Bootstrap
    $wa->registerAndUseScript('template-js', 'media/templates/site/' . $templateOriginal . '/js/template.js', ['version' => 'auto'], ['defer' => true]);
    $wa->registerAndUseStyle($this->template . 'template-css', 'media/templates/site/' . $this->template . '/css/template.css', ['version' => 'auto']);
    $wa->registerAndUseScript($this->template . 'template-js', 'media/templates/site/' . $this->template . '/js/template.js', ['version' => 'auto'], ['defer' => true]);
    // Assuming $this is an instance of Document
    // media/templates/site/minimalista/css/adtitional/
    $additionalcssDirectory = JPATH_ROOT . '/media/templates/site/' . $this->template . '/css/additional';
    if (is_dir($additionalcssDirectory)) {
    $additionalcssFiles = Folder::files($additionalcssDirectory, 'css', true, true);
    if (is_array($additionalcssFiles) || is_object($additionalcssFiles)) {
        foreach ($additionalcssFiles as $index => $cssFile) {
            $wa->registerAndUseStyle($this->template . pathinfo($cssFile, PATHINFO_FILENAME), 'media/templates/site/' . $this->template . '/css/additional' . '/' . basename($cssFile), ['version' => 'auto']);
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
            $wa->registerAndUseScript($this->template . pathinfo($jsFile, PATHINFO_FILENAME), 'media/templates/site/' . $this->template . '/js/additional' . '/' . basename($jsFile), ['version' => 'auto'], ['defer' => true]);
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
            $wa->registerAndUseStyle(pathinfo($cssFile, PATHINFO_FILENAME), 'media/templates/site/' . $templateOriginal . '/css' . '/' . basename($cssFile), ['version' => 'auto']);
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
            $wa->registerAndUseStyle($this->template . pathinfo($cssFile, PATHINFO_FILENAME), 'media/templates/site/' . $this->template . '/css' . '/' . basename($cssFile), ['version' => 'auto']);
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
            $wa->registerAndUseStyle($this->template . pathinfo($cssFile, PATHINFO_FILENAME), 'media/templates/site/' . $this->template . '/css' . '/' . basename($cssFile), ['version' => 'auto']);
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
            $wa->registerAndUseScript(pathinfo($jsFile, PATHINFO_FILENAME), 'media/templates/site/' . $templateOriginal . '/js' . '/' . basename($jsFile), ['version' => 'auto'], ['defer' => true]);
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
            $wa->registerAndUseScript($this->template . pathinfo($jsFile, PATHINFO_FILENAME), 'media/templates/site/' . $this->template . '/js' . '/' . basename($jsFile), ['version' => 'auto'], ['defer' => true]);
        }
    }
    } else {
    // Handle the case where the directory does not exist
    // You can log an error or take appropriate action here
    }
    $responsiveCssPath = JPATH_ROOT . '/media/templates/site/' . $this->template . '/css/responsive.css';
    if (file_exists($responsiveCssPath)) {
    $wa->registerAndUseStyle($this->template . 'responsive-css', 'media/templates/site/' . $this->template . '/css/responsive.css', ['version' => filemtime($responsiveCssPath)]);
    }

    // load social meta tags OpenGraph for Faceboook, Twitter Cards and Schema.org
    if ($templateParams->get('enable_social_meta_tags', 1)) {
    $disable_in = $templateParams->get('disable_in', '');
    if (! is_array($disable_in)) {
        // If 'disable_in' is a string, convert it to an array with a single element
        $disable_in = [$disable_in];
    }
    if (! in_array($option, $disable_in)) {
        $title         = $doc->getTitle() ? $doc->getTitle() : $sitename;
        $description   = $doc->getDescription() ? $doc->getDescription() : Factory::getConfig()->get('MetaDesc');
        $image         = $templateParams->get('social_image', $logo);
        $image_alt     = $templateParams->get('social_image_alt', $logo_alt);
        $arrobasite    = $templateParams->get('arrobasite', '');
        $arrobacreator = $templateParams->get('arrobaauthor', '');
        // Detect locale (ex: pt-BR to pt_BR)
        $locale = str_replace('-', '_', Factory::getApplication()->getLanguage()->getTag());
        $type   = ($option == 'com_content' && $view == 'article') ? 'article' : 'website';

        // Set common metadata
        setMetadata($doc, $title, $description, $image, $image_alt, $arrobasite, $arrobacreator, $type, $locale);
        // Additional metadata for specific conditions and is not homepage
        if ($option == 'com_content' && $view == 'article' && Factory::getApplication()->input->getInt('id') && $active && $active->home != 1) {
            $content = Table::getInstance('content');
            $content->load(Factory::getApplication()->input->getInt('id'));
            $articleImage    = '';
            $articleImageAlt = '';
            $images          = null;
            if (! empty($content->images) && is_string($content->images)) {
                $images = json_decode($content->images);
            }
            if ($images) {
                $articleImage    = $images->image_intro ? $images->image_intro : $images->image_fulltext;
                $articleImageAlt = $images->image_intro_alt ? $images->image_intro_alt : $images->image_fulltext_alt;
            }
            $textlimit          = $templateParams->get('textlimit', 300);
            $text               = $content->introtext . $content->fulltext;
            $firstTextImageRegx = '/<img[^>]+src=[\'"](?P<src>[^\'"]+)[\'"][^>]*>/i';
            preg_match($firstTextImageRegx, $text, $matches);
            if (isset($matches['src']) && empty($articleImage)) {
                $articleImage = $matches['src'];
            }
            // Load Category of the Article
            $articleCategory = Table::getInstance('category');
            $articleCategory->load($content->catid);
            $categoryTitle = $articleCategory->title;

            // Check if "Category as Author" is enabled and if this category is under the target parent
            $isColumnistCategory  = false;
            $enableCategoryAuthor = $templateParams->get('enable_category_author', 0);
            $targetParentId       = $templateParams->get('category_author_parent', 0);

            if ($enableCategoryAuthor && $targetParentId) {
                // Load the target parent category to get its lft/rgt values
                $parentCat = Table::getInstance('category');
                $parentCat->load($targetParentId);

                // Check if current article category is the target or a descendant (using Nested Set Model)
                if ($articleCategory->lft >= $parentCat->lft && $articleCategory->rgt <= $parentCat->rgt) {
                    $isColumnistCategory = true;
                }
            }

            // Get Author name - Priority changes if it's a columnist category
            if ($isColumnistCategory) {
                $authorName = $categoryTitle;
            } else {
                $authorName = ! empty($content->created_by_alias) ? $content->created_by_alias : '';
                if (empty($authorName)) {
                    $authorUser = Factory::getUser($content->created_by);
                    $authorName = $authorUser->name;
                }
            }

            // Image Fallback: Article Image > Category Image > Template Social Image
            if (empty($articleImage) && ! empty($articleCategory->params)) {
                $katParams = json_decode($articleCategory->params);
                if (! empty($katParams->image)) {
                    $articleImage = $katParams->image;
                }
            }

            // Define final image variables
            $finalImage    = ! empty($articleImage) ? $articleImage : $image;
            $finalImageAlt = ! empty($articleImageAlt) ? $articleImageAlt : $image_alt;

            setMetadata($doc, $title, $text, $finalImage, $finalImageAlt, $arrobasite, $arrobacreator, 'article', $locale, $authorName);

            // Add article specific tags
            if (! empty($content->publish_up)) {
                $doc->setMetaData('article:published_time', Factory::getDate($content->publish_up)->format('c'), 'property');
            }
            if (! empty($content->modified) && $content->modified != Factory::getContainer()->get('DatabaseDriver')->getNullDate()) {
                $doc->setMetaData('article:modified_time', Factory::getDate($content->modified)->format('c'), 'property');
            }
            if (! empty($authorName)) {
                $doc->setMetaData('article:author', $authorName, 'property');
            }
            // Add Section (Category Name)
            $doc->setMetaData('article:section', $categoryTitle, 'property');
        }
        if ($option == 'com_content' && $view == 'category' && Factory::getApplication()->input->getInt('id') && $active && $active->home != 1) {
            $category = Table::getInstance('category');
            $category->load(Factory::getApplication()->input->getInt('id'));
            $textlimit = $templateParams->get('textlimit', 300);

            // Description Fallback: Category Desc > Document Desc > Site Config MetaDesc
            $categoryDesc = ! empty($category->description) ? substr(strip_tags($category->description), 0, $textlimit) : $description;

            // Check if the properties exist before accessing them
            $categoryImage    = property_exists($category, 'image') ? $category->image : '';
            $categoryImageAlt = property_exists($category, 'image_alt') ? $category->image_alt : '';

            // Use category image if available, otherwise use default
            $finalImage    = ! empty($categoryImage) ? $categoryImage : $image;
            $finalImageAlt = ! empty($categoryImageAlt) ? $categoryImageAlt : $image_alt;

            setMetadata($doc, $title, $categoryDesc, $finalImage, $finalImageAlt, $arrobasite, $arrobacreator, 'website', $locale);
        }
    }
    }
    // Function to set common metadata
    function setMetadata($doc, $title, $description, $image, $image_alt, $arrobasite = '', $arrobacreator = '', $type = 'website', $locale = 'pt_BR', $author = '')
    {
    $doc->setMetaData('og:title', $title, 'property');
    $doc->setMetaData('og:description', $description, 'property');
    // Clean image URL - remove #joomlaImage: and everything after it
    $image = preg_replace('/#joomlaImage:.*$/', '', $image);
    // Check if image URL is already absolute
    $imageUrl = (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) ? $image : Uri::root() . $image;
    $doc->setMetaData('og:image', $imageUrl, 'property');
    // fb:app_id
    $fb_app_id = Factory::getApplication()->getTemplate(true)->params->get('fb_app_id', '');
    if ($fb_app_id) {
        $doc->setMetaData('fb:app_id', $fb_app_id, 'property');
    }
    $doc->setMetaData('og:image:alt', $image_alt, 'property');
    $doc->setMetaData('og:image:width', '1200', 'property');
    $doc->setMetaData('og:image:height', '630', 'property');
    $doc->setMetaData('og:type', $type, 'property');
    $doc->setMetaData('og:locale', $locale, 'property');
    if (! empty($author)) {
        $doc->setMetaData('author', $author);
    }
    $doc->setMetaData('og:url', Uri::getInstance()->toString(), 'property');
    $doc->setMetaData('og:site_name', Factory::getApplication()->get('sitename'), 'property');
    $doc->setMetaData('twitter:card', 'summary_large_image');
    $doc->setMetaData('twitter:title', $title);
    $doc->setMetaData('twitter:description', $description);
    $doc->setMetaData('twitter:image', $imageUrl);
    $doc->setMetaData('twitter:image:alt', $image_alt);
    $doc->setMetaData('twitter:site', $arrobasite);
    $doc->setMetaData('twitter:creator', $arrobacreator);
    $doc->setMetaData('twitter:url', Uri::getInstance()->toString());

    // Schema.org mapping (Legacy Meta support)
    $doc->setMetaData('schema:name', $title);
    $doc->setMetaData('schema:description', $description);
    $doc->setMetaData('schema:image', $imageUrl);
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
    $hasModules  = hasModules($section->positions, $template);
    if ($hasModules) {
        ?>
<section id="<?php echo $sectionName; ?>"
    class="<?php echo 'section-' . $sectionName . ($section->section_class ? ' ' . $section->section_class : ''); ?>">
    <div class="<?php echo $section->containerwidth; ?>">
        <div class="row">
            <?php foreach ($section->positions as $position): ?>
            <div
                class="<?php echo 'position-' . strtolower($position->position); ?> col<?php echo $defaultBoostrapDesktop . ($position->width ? '-' . $position->width : ''); ?><?php echo $position->customclass ? ' ' . $position->customclass : ''; ?>">
                <div class="row">
                    <jdoc:include type="modules" name="<?php echo $position->position; ?>"
                        style="<?php echo $templateOriginal . '-default'; ?>" />
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php
}
}