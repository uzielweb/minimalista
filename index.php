<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.minmalista
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;
/** @var Joomla\CMS\Document\HtmlDocument $this */
include_once JPATH_THEMES . '/minimalista/logic.php';
?>
<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>
<body class="<?php echo $bodyClasses; ?>">
    <?php echo $startBodyCode; ?>
    <?php
// Sections before the header section
$sectionsBeforeHeader = $templateParams->get('sectionsbeforeheader', '');
if ($sectionsBeforeHeader) {
    foreach ($sectionsBeforeHeader as $section) {
        renderSection($section, $defaultBoostrapDesktop, $this, $templateOriginal);
    }
}
?>
    <!-- head with menu, main, sidebars, footer -->
    <?php if (($logo && $logo_position == 'header-navbar') || $this->countModules('header') || $this->countModules('menu') || $this->countModules('header-top') || $this->countModules('header-bottom')): ?>
    <header class="header" id="header">
        <?php if ($this->countModules('header-top')): ?>
            <div class="header-top" id="header-top">
                <div class="container<?php echo $containerFluid; ?>">
                    <div class="row">
                        <jdoc:include type="modules" name="header-top" style="<?php echo $templateOriginal . '-default'; ?>" />
                    </div>
                </div>
            </div>
        <?php endif;?>
        <?php if ($this->countModules('header')): ?>
            <div class="main-header" id="main-header">
                <div class="container<?php echo $containerFluid; ?>">
                    <div class="row">
                        <jdoc:include type="modules" name="header" style="<?php echo $templateOriginal . '-default'; ?>" />
                    </div>
                </div>
            </div>
        <?php endif;?>
        <div class="container<?php echo $containerFluid; ?>">
            <!-- navbar offcanvas bootstrap 5 -->            <nav class="navbar navbar-expand<?php echo $defaultBoostrapDesktop; ?> row">
                <div class="container-fluid">
                    <?php if ($logo && $logo_position == 'header-navbar'): ?>
                    <a class="navbar-brand" href="<?php echo $this->baseurl; ?>">
                        <img src="<?php echo $logo; ?>" alt="<?php echo $logo_alt; ?>" class="logo" />
                    </a>
                    <?php endif;?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="offcanvas offcanvas-<?php echo $offcanvasDirection; ?>" tabindex="-1"
                        id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <div class="row w-100">
                            <jdoc:include type="modules" name="menu" style="<?php echo $templateOriginal . '-default'; ?>" />
                            </div>
                        </div>
                    </div>
                    <?php if ($this->countModules('search')): ?>
                    <div class="search">
                        <div class="row">
                            <jdoc:include type="modules" name="search" style="<?php echo $templateOriginal . '-default'; ?>" />
                        </div>
                    </div>
                    <?php endif;?>
                </div>
            </nav>
        </div>
        <?php if ($this->countModules('header-bottom')): ?>
            <div class="header-bottom" id="header-bottom">
                <div class="container<?php echo $containerFluid; ?>">
                    <div class="row">
                        <jdoc:include type="modules" name="header-bottom" style="<?php echo $templateOriginal . '-default'; ?>" />
                    </div>
                </div>
            </div>
        <?php endif;?>
    </header>
    <?php endif;?>
    <?php
// Sections after the header section
$sectionsAfterHeader = $templateParams->get('sectionsafterheader', '');
if ($sectionsAfterHeader) {
    foreach ($sectionsAfterHeader as $section) {
        renderSection($section, $defaultBoostrapDesktop, $this, $templateOriginal);
    }
}
?>
    <main class="main">
        <?php if ($this->countModules('slideshow')): ?>
        <section id="slideshow" class="section-slideshow">
            <div class="container-fluid">
                <div class="row">
                    <jdoc:include type="modules" name="slideshow" style="<?php echo $templateOriginal . '-default'; ?>" />
                </div>
            </div>
        </section>
        <?php endif;?>
        <?php if ($this->countModules('slideshow-container')): ?>
        <section id="slideshow-container" class="section-slideshow-container">
            <div class="container">
                <div class="row">
                    <jdoc:include type="modules" name="slideshow-container" style="<?php echo $templateOriginal . '-default'; ?>" />
                </div>
            </div>
        </section>
        <?php endif;?>
        <?php
// Sections before the component section
$sectionsBeforeComponent = $templateParams->get('sectionsbeforecomponent', '');
if ($sectionsBeforeComponent) {
    foreach ($sectionsBeforeComponent as $section) {
        renderSection($section, $defaultBoostrapDesktop, $this, $templateOriginal);
    }
}
?>
        <section id="section-component" class="section-component">
            <div class="container<?php echo $containerFluid; ?>">
                <div class="inner">
                <?php if ($this->countModules('breadcrumbs')): ?>
                    <div class="component-breadcrumbs">
                        <div class="row">
                            <jdoc:include type="modules" name="breadcrumbs" style="<?php echo $templateOriginal . '-default'; ?>" />
                        </div>
                    </div>
                    <?php endif;?>
                    <?php if ($this->countModules('main-top')): ?>
                    <div class="main-top">
                        <div class="row">
                            <jdoc:include type="modules" name="main-top" style="<?php echo $templateOriginal . '-default'; ?>" />
                        </div>
                    </div>
                    <?php endif;?>
                    <div class="row">                        <?php if ($this->countModules('sidebar-left') || ($logo && $logo_position == 'sidebar-left')): ?>
                        <div class="sidebar-left col-12 col<?php echo $sidebarWidth; ?>" id="sidebar-left">
                            <?php if ($logo && $logo_position == 'sidebar-left'): ?>
                            <div class="sidebar-logo mb-4">
                                <a href="<?php echo $this->baseurl; ?>">
                                    <img src="<?php echo $logo; ?>" alt="<?php echo $logo_alt; ?>" class="logo img-fluid" />
                                </a>
                            </div>
                            <?php endif; ?>
                              <?php if ($this->countModules('sidebar-left')): ?>
                            <div class="row">
                                <jdoc:include type="modules" name="sidebar-left" style="<?php echo $templateOriginal . '-default'; ?>" />
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif;?>
                        <div class="component col-12 col<?php echo $mainWidth; ?>">
                            <?php if ($this->countModules('content-top')): ?>
                                <div class="content-top" id="content-top">
                                    <div class="row">
                                        <jdoc:include type="modules" name="content-top" style="<?php echo $templateOriginal . '-default'; ?>" />
                                    </div>
                                </div>
                            <?php endif;?>
                            <jdoc:include type="message" />
                            <jdoc:include type="component" />
                            <?php if ($this->countModules('content-bottom')): ?>
                                <div class="content-bottom" id="content-bottom">
                                    <div class="row">
                                        <jdoc:include type="modules" name="content-bottom" style="<?php echo $templateOriginal . '-default'; ?>" />
                                    </div>
                                </div>
                            <?php endif;?>
                        </div>                        <?php if ($this->countModules('sidebar-right') || ($logo && $logo_position == 'sidebar-right')): ?>
                        <div class="sidebar-right col-12 col<?php echo $sidebarWidth; ?>" id="sidebar-right">
                            <?php if ($logo && $logo_position == 'sidebar-right'): ?>
                            <div class="sidebar-logo mb-4">
                                <a href="<?php echo $this->baseurl; ?>">
                                    <img src="<?php echo $logo; ?>" alt="<?php echo $logo_alt; ?>" class="logo img-fluid" />
                                </a>
                            </div>
                            <?php endif; ?>
                              <?php if ($this->countModules('sidebar-right')): ?>
                            <div class="row">
                                <jdoc:include type="modules" name="sidebar-right" style="<?php echo $templateOriginal . '-default'; ?>" />
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif;?>
                    </div>
                    <?php if ($this->countModules('main-bottom')): ?>
                    <div class="main-bottom" id="main-bottom">
                        <div class="row">
                            <jdoc:include type="modules" name="main-bottom" style="<?php echo $templateOriginal . '-default'; ?>" />
                        </div>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </section>
        <?php
// Sections after the component section
$sectionsAfterComponent = $templateParams->get('sectionsaftercomponent', '');
if ($sectionsAfterComponent) {
    foreach ($sectionsAfterComponent as $section) {
        renderSection($section, $defaultBoostrapDesktop, $this, $templateOriginal);
    }
}
?>
    </main>
    <?php if ($this->countModules('footer')): ?>
    <footer class="footer" id="footer">
        <div class="container<?php echo $containerFluid; ?>">
            <div class="row">
                <jdoc:include type="modules" name="footer" style="<?php echo $templateOriginal . '-default'; ?>" />
            </div>
        </div>
    </footer>
    <?php endif;?>
    <?php if ($this->countModules('copyright')): ?>
    <div class="copyright" id="copyright">
        <div class="container<?php echo $containerFluid; ?>">
            <div class="row">
                <jdoc:include type="modules" name="copyright" style="<?php echo $templateOriginal . '-default'; ?>" />
            </div>
        </div>
    </div>
    <?php endif;?>
    <?php echo $endBodyCode; ?>
    <?php if ($backtotop): ?>
    <button href="#top" id="back-top" class="btn back-to-top-link"
        aria-label="<?php echo Text::_('TPL_MINIMALISTA_BACKTOTOP'); ?>">
        <i class="fas fa-arrow-up" aria-hidden="true"></i>
    </button>
    <?php endif;?>
    <jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
