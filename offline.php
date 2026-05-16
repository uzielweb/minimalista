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
use Joomla\CMS\Helper\AuthenticationHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

include_once JPATH_THEMES . '/minimalista/logic.php';

$app = Factory::getApplication();
$offlineDate = $templateParams->get('offline_countdown', '');
$socialLinks = $templateParams->get('offline_social_links', '');

?>
<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <jdoc:include type="head" />
</head>

<body class="offline-page bg-light">
    <div class="vh-100 d-flex align-items-center justify-content-center">
        <div class="container d-flex justify-content-center">
            <div class="card shadow border-0 p-4 p-md-5 text-center animate__animated animate__zoomIn" style="max-width: 600px; width: 100%;">
                <div class="card-body">
                    <?php if ($logo): ?>
                        <img src="<?php echo $logo; ?>" alt="<?php echo $sitename; ?>" class="mb-4 img-fluid" style="max-height: 80px;">
                    <?php else: ?>
                        <h1 class="mb-4"><?php echo $sitename; ?></h1>
                    <?php endif; ?>

                    <h2 class="mb-3 h3 fw-bold"><?php echo Text::_('TPL_MINIMALISTA_OFFLINE_TITLE'); ?></h2>
                    <p class="text-muted mb-4"><?php echo $app->get('offline_message'); ?></p>

                    <?php if ($offlineDate): ?>
                    <div id="countdown" class="d-flex justify-content-center gap-3 gap-md-4 my-5" data-date="<?php echo $offlineDate; ?>">
                        <div class="text-center">
                            <span class="d-block fs-1 fw-bold text-primary" id="days">00</span>
                            <span class="text-uppercase small text-muted"><?php echo Text::_('TPL_MINIMALISTA_DAYS'); ?></span>
                        </div>
                        <div class="text-center">
                            <span class="d-block fs-1 fw-bold text-primary" id="hours">00</span>
                            <span class="text-uppercase small text-muted"><?php echo Text::_('TPL_MINIMALISTA_HOURS'); ?></span>
                        </div>
                        <div class="text-center">
                            <span class="d-block fs-1 fw-bold text-primary" id="minutes">00</span>
                            <span class="text-uppercase small text-muted"><?php echo Text::_('TPL_MINIMALISTA_MINUTES'); ?></span>
                        </div>
                        <div class="text-center">
                            <span class="d-block fs-1 fw-bold text-primary" id="seconds">00</span>
                            <span class="text-uppercase small text-muted"><?php echo Text::_('TPL_MINIMALISTA_SECONDS'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($socialLinks): ?>
                    <div class="d-flex justify-content-center gap-3 mb-5">
                        <?php foreach ($socialLinks as $social): ?>
                            <a href="<?php echo $social->link; ?>" target="_blank" class="text-secondary fs-4 text-decoration-none">
                                <i class="<?php echo $social->icon; ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <hr class="my-4 opacity-25">

                    <button class="btn btn-link text-decoration-none text-muted btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#loginForm">
                        <i class="fas fa-lock me-2"></i> <?php echo Text::_('JLOGIN'); ?>
                    </button>

                    <div class="collapse mt-4 text-start" id="loginForm">
                        <form action="<?php echo Route::_('index.php', true); ?>" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label small"><?php echo Text::_('JGLOBAL_USERNAME'); ?></label>
                                <input name="username" class="form-control" id="username" type="text" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label small"><?php echo Text::_('JGLOBAL_PASSWORD'); ?></label>
                                <input name="password" class="form-control" id="password" type="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 shadow-sm"><?php echo Text::_('JLOGIN'); ?></button>
                            <input type="hidden" name="option" value="com_users">
                            <input type="hidden" name="task" value="user.login">
                            <input type="hidden" name="return" value="<?php echo base64_encode(Uri::base()); ?>">
                            <?php echo HTMLHelper::_('form.token'); ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($offlineDate): ?>
    <script>
        function updateCountdown() {
            const targetDate = new Date(document.getElementById('countdown').dataset.date).getTime();
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                document.getElementById('countdown').innerHTML = "<h3 class='text-primary fw-bold'><?php echo Text::_('TPL_MINIMALISTA_OFFLINE_FINISHED'); ?></h3>";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').innerText = String(days).padStart(2, '0');
            document.getElementById('hours').innerText = String(hours).padStart(2, '0');
            document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
            document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');
        }
        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
    <?php endif; ?>
</body>

</html>