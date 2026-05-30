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
    if ($doc instanceof \Joomla\CMS\Document\ErrorDocument) {
        $assetFile = JPATH_ROOT . '/media/templates/site/' . $templateOriginal . '/joomla.asset.json';
        $devAssetFile = JPATH_THEMES . '/' . $templateOriginal . '/media/joomla.asset.json';
        
        if (is_file($assetFile)) {
            $war->addRegistryFile($assetFile);
        } elseif (is_file($devAssetFile)) {
            $war->addRegistryFile($devAssetFile);
        }
    }
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
    $enableDarkMode = $templateParams->get('enable_dark_mode', 0);
    $customFonts = $templateParams->get('custom_fonts', '');
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
    $colorPrimary       = $templateParams->get('color_primary', '');
    $colorSecondary     = $templateParams->get('color_secondary', '');

    // CSP Nonce Injection
    $nonce = method_exists($doc, 'getNonce') ? $doc->getNonce() : '';
    $nonceAttr = $nonce ? ' nonce="' . $nonce . '"' : '';

    if ($startBodyCode && $nonceAttr) {
        $startBodyCode = str_ireplace('<script', '<script' . $nonceAttr, $startBodyCode);
    }
    if ($endBodyCode && $nonceAttr) {
        $endBodyCode = str_ireplace('<script', '<script' . $nonceAttr, $endBodyCode);
    }
    if ($custom_head_code && $nonceAttr) {
        $custom_head_code = str_ireplace('<script', '<script' . $nonceAttr, $custom_head_code);
    }

    // CSS Variables Injection
    if ($colorPrimary || $colorSecondary) {
        $cssVars = ":root {\n";
        if ($colorPrimary) $cssVars .= "    --bs-primary: {$colorPrimary};\n";
        if ($colorSecondary) $cssVars .= "    --bs-secondary: {$colorSecondary};\n";
        $cssVars .= "}";
        $wa->addInlineStyle($cssVars);
    }

    // Preload Logo for Core Web Vitals (LCP)
    if ($logo) {
        $logoPath = strpos($logo, '/') === 0 ? $logo : '/' . $logo;
        $doc->addHeadLink(Uri::root(true) . $logoPath, 'preload', 'rel', ['as' => 'image']);
    }

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
    if ($customFonts) {
        $doc->addCustomTag($customFonts);
    }

    // Dark Mode FOUC Prevention
    if ($enableDarkMode) {
        $foucScript = "
            (function() {
                var theme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-bs-theme', theme);
            })();
        ";
        $wa->addInlineScript($foucScript);
    }

    // Google Fonts Preload
    $googleFontsUrl = $templateParams->get('google_fonts_url', '');
    if ($googleFontsUrl) {
        $doc->addCustomTag('<link rel="preconnect" href="https://fonts.googleapis.com">');
        $doc->addCustomTag('<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>');
        $doc->addCustomTag('<link href="' . htmlspecialchars($googleFontsUrl) . '" rel="stylesheet">');
    }

    // Smart Header
    if ($templateParams->get('smart_header', 0) == 1) {
        $smartHeaderScript = "
        document.addEventListener('DOMContentLoaded', function() {
            var lastScrollTop = 0;
            var header = document.getElementById('header');
            if(!header) return;
            header.style.position = 'sticky';
            header.style.top = '0';
            header.style.zIndex = '1030';
            header.style.transition = 'top 0.3s ease-in-out';
            window.addEventListener('scroll', function() {
                var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                if (scrollTop > lastScrollTop && scrollTop > header.offsetHeight) {
                    header.style.top = '-' + header.offsetHeight + 'px';
                } else {
                    header.style.top = '0';
                }
                lastScrollTop = scrollTop;
            });
        });
        ";
        $wa->addInlineScript($smartHeaderScript);
    }

    // Reading Progress Bar
    if ($templateParams->get('reading_progress_bar', 0) == 1 && $view == 'article') {
        $progressBarScript = "
        document.addEventListener('DOMContentLoaded', function() {
            var progressBarContainer = document.createElement('div');
            progressBarContainer.style.position = 'fixed';
            progressBarContainer.style.top = '0';
            progressBarContainer.style.left = '0';
            progressBarContainer.style.width = '100%';
            progressBarContainer.style.height = '4px';
            progressBarContainer.style.zIndex = '9999';
            progressBarContainer.style.backgroundColor = 'transparent';
            
            var progressBar = document.createElement('div');
            progressBar.style.height = '100%';
            progressBar.style.backgroundColor = 'var(--bs-primary, #0d6efd)';
            progressBar.style.width = '0%';
            progressBar.style.transition = 'width 0.2s';
            
            progressBarContainer.appendChild(progressBar);
            document.body.appendChild(progressBarContainer);
            
            window.addEventListener('scroll', function() {
                var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                var scrolled = (winScroll / height) * 100;
                progressBar.style.width = scrolled + '%';
            });
        });
        ";
        $wa->addInlineScript($progressBarScript);
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
    // 1. Controle do Container Global da Página
    $containerFluid = $templateParams->get('container-fluid', '0') == '1' ? '-fluid' : '';
    
    if (strpos($pageclass, 'fluid-page') !== false) {
        $containerFluid = '-fluid';
    } elseif (strpos($pageclass, 'container-page') !== false) {
        $containerFluid = '';
    }

    // 2. Controle do Container Específico do Componente
    $compParam = $templateParams->get('component_container_fluid', 'default');
    $componentContainerFluid = $compParam === 'container-fluid' ? '-fluid' : ($compParam === 'container' ? '' : $containerFluid);

    if (strpos($pageclass, 'fluid-component') !== false) {
        $componentContainerFluid = '-fluid';
    } elseif (strpos($pageclass, 'container-component') !== false) {
        $componentContainerFluid = '';
    }
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
        $wa->useScript('template.minimalista.jquery-noconflict');
    } else {
        // Load jQuery from Joomla
        HTMLHelper::_('jquery.framework', true, true);
    }
    // Load Bootstrap CSS and JavaScript based on template or Joomla configuration
    if ($templateParams->get('bootstrap_from_template', 1) == 1) {
        $wa->useStyle('template.minimalista.bootstrap');
        $wa->useScript('template.minimalista.bootstrap');
    } else {
        // Load Bootstrap CSS and JavaScript from Joomla
        HTMLHelper::_('bootstrap.loadCss', true, $this->direction);
        HTMLHelper::_('bootstrap.framework');
    }
    // Load FontAwesome based on template or Joomla configuration
    $loadFontAwesome = $templateParams->get('load_fontawesome', 'css_from_template');
    if ($loadFontAwesome == 'css_from_template') {
        $wa->useStyle('template.minimalista.fontawesome');
    } elseif ($loadFontAwesome == 'js_from_template') {
        $wa->useScript('template.minimalista.fontawesome');
    } elseif ($loadFontAwesome == 'svg_from_template') {
        // Use FontAwesome SVG Core (JS) with auto-replace configured for SVG
        $wa->useScript('template.minimalista.fontawesome');
    } elseif ($loadFontAwesome == 'css_from_joomla') {
        // Load FontAwesome from Joomla
        $wa->useStyle('fontawesome');
    } else {
        // Do nothing for FontAwesome
    }
    // Load Joomla 4 system icons
    $wa->useStyle('joomla-fontawesome');
    // Load Animate.css, Template CSS and Template JS
    $wa->useStyle('template.minimalista.animate');
    $wa->useStyle('template.minimalista.main');
    $wa->useScript('template.minimalista.main');

    if ($this->template !== $templateOriginal) {
        $wa->registerAndUseStyle($this->template . 'template-css', 'media/templates/site/' . $this->template . '/css/template.css', ['version' => 'auto']);
        $wa->registerAndUseScript($this->template . 'template-js', 'media/templates/site/' . $this->template . '/js/template.js', ['version' => 'auto'], ['defer' => true]);
    }
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
    $wa->useStyle('template.minimalista.responsive');

    /**
 * Helper function to clean text for metadata.
 * Handles cleaning, double decoding, and word/sentence-safe truncation.
 */
    if (!function_exists('cleanMetaText')) {
        function cleanMetaText($txt, $len = 0)
        {
        if (empty($txt)) {
            return '';
        }

        // Decode twice to handle double encoding (e.g. &amp;amp;)
        $txt = html_entity_decode(html_entity_decode($txt, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
        // Remove Joomla plugin placeholders
        $txt = preg_replace('/\{[a-zA-Z0-9\s\-_]+\}/', '', $txt);
        // Strip tags
        $txt = strip_tags($txt);
        // Normalise spaces
        $txt = trim(preg_replace('/\s+/', ' ', $txt));

        // Truncate if needed
        if ($len > 0 && mb_strlen($txt) > $len) {
            $txt = mb_substr($txt, 0, $len);

            // Try to find the last sentence end (., !, ?) within the limit
            $lastDot   = mb_strrpos($txt, '.');
            $lastExcl  = mb_strrpos($txt, '!');
            $lastQuest = mb_strrpos($txt, '?');

            $lastSentenceEnd = max($lastDot, $lastExcl, $lastQuest);

            if ($lastSentenceEnd !== false && $lastSentenceEnd > ($len * 0.5)) {
                $txt = mb_substr($txt, 0, $lastSentenceEnd + 1);
            } else {
                // Fallback: Word-safe truncation
                $lastSpace = mb_strrpos($txt, ' ');
                if ($lastSpace !== false) {
                    $txt = mb_substr($txt, 0, $lastSpace);
                }
            }
        }

        return $txt;
        }
    }

    // load social meta tags OpenGraph for Faceboook, Twitter Cards and Schema.org
    if ($templateParams->get('enable_social_meta_tags', 1)) {
    $disable_in = $templateParams->get('disable_in', '');
    if (! is_array($disable_in)) {
        // If 'disable_in' is a string, convert it to an array with a single element
        $disable_in = [$disable_in];
    }
    if (! in_array($option, $disable_in)) {
        $title = $doc->getTitle() ? $doc->getTitle() : $sitename;

        // Description Fallback: Document Desc > Site Config MetaDesc > Site Name
        $description = $doc->getDescription();
        if (empty($description)) {
            $description = Factory::getConfig()->get('MetaDesc', '');
        }
        if (empty($description)) {
            $description = $sitename;
        }

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
            $articleId = Factory::getApplication()->input->getInt('id');
            $db = Factory::getContainer()->get('DatabaseDriver');
            $query = $db->getQuery(true)->select('*')->from($db->quoteName('#__content'))->where($db->quoteName('id') . ' = :id')->bind(':id', $articleId, \PDO::PARAM_INT);
            $content = $db->setQuery($query)->loadObject();
            if (!$content) {
                $content = (object) ['images' => '', 'introtext' => '', 'fulltext' => '', 'catid' => 0, 'title' => '', 'publish_up' => '', 'modified' => '', 'created_by_alias' => '', 'created_by' => 0];
            }
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
            $queryCat = $db->getQuery(true)->select('*')->from($db->quoteName('#__categories'))->where($db->quoteName('id') . ' = :catid')->bind(':catid', $content->catid, \PDO::PARAM_INT);
            $articleCategory = $db->setQuery($queryCat)->loadObject();
            if (!$articleCategory) {
                $articleCategory = (object) ['title' => '', 'lft' => 0, 'rgt' => 0, 'params' => ''];
            }
            $categoryTitle = cleanMetaText($articleCategory->title);

            // Check if "Category as Author" is enabled and if this category is under the target parent
            $isColumnistCategory  = false;
            $enableCategoryAuthor = $templateParams->get('enable_category_author', 0);
            $targetParentId       = $templateParams->get('category_author_parent', 0);

            if ($enableCategoryAuthor && $targetParentId) {
                // Load the target parent category to get its lft/rgt values
                $queryParentCat = $db->getQuery(true)->select('*')->from($db->quoteName('#__categories'))->where($db->quoteName('id') . ' = :pid')->bind(':pid', $targetParentId, \PDO::PARAM_INT);
                $parentCat = $db->setQuery($queryParentCat)->loadObject();
                if (!$parentCat) {
                    $parentCat = (object) ['lft' => 0, 'rgt' => 0];
                }

                // Check if current article category is the target or a descendant (using Nested Set Model)
                if ($articleCategory->lft >= $parentCat->lft && $articleCategory->rgt <= $parentCat->rgt) {
                    $isColumnistCategory = true;
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

            // Get Author name - Priority changes if it's a columnist category
            $authorName = '';
            if ($isColumnistCategory) {
                $authorName = $categoryTitle;
            } else {
                $authorName = ! empty($content->created_by_alias) ? $content->created_by_alias : '';
                if (empty($authorName)) {
                    $authorUser = Factory::getUser($content->created_by);
                    $authorName = $authorUser->name;
                }
            }

            // Clean author name
            $authorName = cleanMetaText($authorName);

            setMetadata($doc, $content->title, $text, $finalImage, $finalImageAlt, $arrobasite, $arrobacreator, 'article', $locale, $authorName);

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
            $catId = Factory::getApplication()->input->getInt('id');
            $db = Factory::getContainer()->get('DatabaseDriver');
            $queryCat = $db->getQuery(true)->select('*')->from($db->quoteName('#__categories'))->where($db->quoteName('id') . ' = :id')->bind(':id', $catId, \PDO::PARAM_INT);
            $category = $db->setQuery($queryCat)->loadObject();
            if (!$category) {
                $category = (object) ['description' => '', 'image' => '', 'image_alt' => ''];
            }
            $textlimit = $templateParams->get('textlimit', 300);

            // Description Fallback: Category Desc > Document Desc > Site Config MetaDesc > Site Name
            $categoryDesc = '';
            if (! empty($category->description)) {
                $categoryDesc = cleanMetaText($category->description, $textlimit);
            }
            if (empty($categoryDesc) && ! empty($description)) {
                $categoryDesc = $description;
            }
            if (empty($categoryDesc)) {
                $categoryDesc = Factory::getConfig()->get('MetaDesc', '');
            }
            if (empty($categoryDesc)) {
                $categoryDesc = $sitename;
            }

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
    if (!function_exists('setMetadata')) {
    function setMetadata($doc, $title, $description, $image, $image_alt, $arrobasite = '', $arrobacreator = '', $type = 'website', $locale = 'pt_BR', $author = '')
    {
    $cleanTitle = cleanMetaText($title);
    $cleanDesc  = cleanMetaText($description);

    // Remove redundant title from the start of the description
    if (! empty($cleanTitle) && ! empty($cleanDesc)) {
        if (mb_strpos($cleanDesc, $cleanTitle) === 0) {
            $cleanDesc = trim(mb_substr($cleanDesc, mb_strlen($cleanTitle)));
        }
    }

    // Apply truncation to description (limit 300)
    $description = cleanMetaText($cleanDesc, 300);
    $title       = $cleanTitle;
    $author      = cleanMetaText($author);

    // Final fallback: if description is still empty, use the title
    if (empty($description)) {
        $description = $title;
    }

    // Set standard HTML meta description (essential for SEO)
    $doc->setDescription($description);

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


    }
    }
    // functions.php
    if (!function_exists('cleanSectionName')) {
    function cleanSectionName($sectionName)
    {
    $sectionName = str_replace(' ', '-', $sectionName);
    $sectionName = strtolower($sectionName);
    $sectionName = iconv('UTF-8', 'ASCII//TRANSLIT', $sectionName);
    $sectionName = preg_replace('/[^a-zA-Z0-9\-]/', '', $sectionName);
    return $sectionName;
    }
    }
    if (!function_exists('hasModules')) {
    function hasModules($positions, $template)
    {
    foreach ($positions as $position) {
        if ($template->countModules($position->position) > 0) {
            return true;
        }
    }
    return false;
    }
    }
    if (!function_exists('renderSection')) {
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
            <?php
                if ($template->countModules($position->position) == 0) {
                            continue;
                        }
                    ?>
            <div
                class="<?php echo 'position-' . strtolower($position->position); ?> col<?php echo $defaultBoostrapDesktop . ($position->width ? '-' . $position->width : ''); ?><?php echo $position->customclass ? ' ' . $position->customclass : ''; ?>">
                <div class="row">
                    <jdoc:include type="modules" name="<?php echo $position->position; ?>" style="<?php echo $templateOriginal . '-default'; ?>" />
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php
}
}
}