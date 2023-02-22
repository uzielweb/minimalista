<?php
defined('_JEXEC') or die;
include_once JPATH_THEMES . '/' . $this->template . '/logic.php';
?>
<!doctype html>
<html lang="<?php echo $doc->getLanguage(); ?>" dir="<?php echo $doc->getDirection(); ?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>
<body class="<?php echo implode(' ', $bodyClasses); ?>">
    <!-- head with menu, main, sidebars, footer -->
    <header class="header">
        <div class="container<?php echo $containerFluid; ?>">
            <div class="row">
                <div class="col-12">
                    <jdoc:include type="modules" name="header" style="none" />
                </div>
            </div>
        </div>
        <div class="container<?php echo $containerFluid; ?>">
            <div class="row">
                <div class="col-12">
                    <jdoc:include type="modules" name="menu" style="none" />
                </div>
            </div>
        </div>
    </header>
    <main class="main">
        <div class="container<?php echo $containerFluid; ?>">
            <div class="row">
               <?php if ($this->countModules('sidebar-left')) : ?>
                <div class="sidebar-left col-12 col<?php echo $defaultBoostrapDesktop; ?><?php echo $sidebarWidth; ?>">
                    <jdoc:include type="modules" name="sidebar-left" style="none" />
                </div>
                <?php endif; ?>
                <div class="component col-12 col<?php echo $defaultBoostrapDesktop; ?>">
                    <jdoc:include type="modules" name="main-top" style="none" />
                    <jdoc:include type="message" />
                    <jdoc:include type="component" />
                    <jdoc:include type="modules" name="main-bottom" style="none" />
                </div>
                <?php if ($this->countModules('sidebar-right')) : ?>
                <div class="sidebar-right col-12 col<?php echo $defaultBoostrapDesktop; ?><?php echo $sidebarWidth; ?>">
                    <jdoc:include type="modules" name="sidebar-right" style="none" />
                </div>
                <?php endif; ?>
            </div>
        </div>
</body>
</html>
