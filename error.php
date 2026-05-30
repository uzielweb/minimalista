<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.minimalista
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\Document\ErrorDocument $this */

// Load template logic
include JPATH_THEMES . '/minimalista/logic.php';

// Prepare Search Route for Smart Search (com_finder)
$searchRoute = Route::_('index.php?option=com_finder&view=search');

?>
<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <jdoc:include type="head" />
</head>

<body class="error-page <?php echo $bodyClasses ?>">
    <a class="skip-link" href="#main-content"><?php echo Text::_('TPL_MINIMALISTA_SKIP_TO_CONTENT'); ?></a>

    <?php 
    // Reusing the header from index.php
    ?>
    <header class="header" id="header">
        <div class="container<?php echo $containerFluid; ?>">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid p-0">
                    <?php if ($logo): ?>
                    <a class="navbar-brand" href="<?php echo $this->baseurl; ?>">
                        <img src="<?php echo $logo; ?>" alt="<?php echo $logo_alt; ?>" class="logo img-fluid" loading="lazy" />
                    </a>
                    <?php else: ?>
                    <a class="navbar-brand" href="<?php echo $this->baseurl; ?>">
                        <?php echo $sitename; ?>
                    </a>
                    <?php endif;?>
                </div>
            </nav>
        </div>
    </header>

    <main class="main" id="main-content">
        <div class="container<?php echo $containerFluid; ?>">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center py-5">
                    <div class="error-container animate__animated animate__fadeIn">
                        <h1 class="display-1 fw-bold text-primary"><?php echo $this->error->getCode(); ?></h1>
                        <h2 class="mb-4"><?php echo Text::_('TPL_MINIMALISTA_ERROR_TITLE'); ?></h2>
                        <p class="lead mb-5"><?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
                        
                        <div class="search-box mb-5">
                            <p><?php echo Text::_('TPL_MINIMALISTA_ERROR_SEARCH_PROMPT'); ?></p>
                            <form action="<?php echo $searchRoute; ?>" method="get" class="d-flex justify-content-center">
                                <div class="input-group">
                                    <input type="text" name="q" class="form-control form-control-lg" placeholder="<?php echo Text::_('TPL_MINIMALISTA_SEARCH_PLACEHOLDER'); ?>" required>
                                    <button class="btn btn-primary btn-lg" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="option" value="com_finder">
                                <input type="hidden" name="view" value="search">
                            </form>
                        </div>

                        <div class="actions">
                            <a href="<?php echo $this->baseurl; ?>/index.php" class="btn btn-outline-primary btn-lg px-5">
                                <i class="fas fa-home me-2"></i> <?php echo Text::_('TPL_MINIMALISTA_BACK_TO_HOME'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php if ($this->countModules('footer')): ?>
    <footer class="footer py-5" id="footer">
        <div class="container<?php echo $containerFluid; ?>">
            <div class="row">
                <jdoc:include type="modules" name="footer" style="<?php echo $templateOriginal . '-default'; ?>" />
            </div>
        </div>
    </footer>
    <?php endif;?>

    <?php if ($backtotop): ?>
    <button href="#top" id="back-top" class="btn back-to-top-link" aria-label="<?php echo Text::_('TPL_MINIMALISTA_BACKTOTOP'); ?>">
        <i class="fas fa-arrow-up" aria-hidden="true"></i>
    </button>
    <?php endif; ?>

    <?php echo $endBodyCode; ?>
</body>

</html>
