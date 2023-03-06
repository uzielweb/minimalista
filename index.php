<?php
defined('_JEXEC') or die;
include_once JPATH_THEMES . '/' . $this->template . '/logic.php';
?>
<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
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
                <jdoc:include type="modules" name="header" style="<?php echo $this->template.'-default';?>" />
            </div>
        </div>
        <div class="container<?php echo $containerFluid; ?>">
            <div class="row">
                <jdoc:include type="modules" name="menu" style="<?php echo $this->template.'-default';?>" />
            </div>
        </div>
    </header>
    <main class="main">
        <div class="container<?php echo $containerFluid; ?>">
            <?php if ($this->countModules('main-top')): ?>
            <div class="main-top row">
                <jdoc:include type="modules" name="main-top" style="<?php echo $this->template . '-default'; ?>" />
            </div>
            <?php endif; ?>
            <div class="row">
                <?php if ($this->countModules('sidebar-left')) : ?>
                <div class="sidebar-left col-12 col<?php echo $defaultBoostrapDesktop; ?><?php echo $sidebarWidth; ?>">
                    <div class="row">
                        <jdoc:include type="modules" name="sidebar-left" style="<?php echo $this->template.'-default';?>" />
                    </div>
                </div>
                <?php endif; ?>
                <div class="component col-12 col<?php echo $defaultBoostrapDesktop; ?>">
                    <?php if ($this->countModules('content-top')) : ?>
                    <div class="row">
                        <jdoc:include type="modules" name="content-top" style="<?php echo $this->template.'-default';?>" />
                    </div>
                    <?php endif; ?>
                    <jdoc:include type="message" />
                    <jdoc:include type="component" />
                    <?php if ($this->countModules('content-bottom')) : ?>
                    <div class="row">
                        <jdoc:include type="modules" name="content-bottom" style="<?php echo $this->template.'-default';?>" />
                    </div>
                    <?php endif;?>
                </div>
                <?php if ($this->countModules('sidebar-right')) : ?>
                <div class="sidebar-right col-12 col<?php echo $defaultBoostrapDesktop; ?><?php echo $sidebarWidth; ?>">
                    <div class="row">
                        <jdoc:include type="modules" name="sidebar-right" style="<?php echo $this->template.'-default';?>" />
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($this->countModules('main-bottom')): ?>
            <div class="main-bottom row">
                <jdoc:include type="modules" name="main-bottom" style="<?php echo $this->template . '-default'; ?>" />
            </div>
            <?php endif; ?>
        </div>
    </main>
    <footer class="footer">
        <div class="container<?php echo $containerFluid; ?>">
            <?php if ($this->countModules('footer')): ?>
            <div class="row">
                <jdoc:include type="modules" name="footer" style="<?php echo $this->template.'-default';?>" />
            </div>
            <?php endif;?>
        </div>
    </footer>
</body>
</html>
