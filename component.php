<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.minmalista
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
use Joomla\CMS\Document\Document;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\Folder;
use Joomla\CMS\Router\Route;
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
    <jdoc:include type="message" />
    <jdoc:include type="component" />
</body>
</html>
