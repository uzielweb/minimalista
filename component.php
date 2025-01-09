<?php
/**
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
include_once JPATH_THEMES . '/minimalista/logic.php';

?>
<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <jdoc:include type="head" />
    </head>
    <body class="<?php echo implode(' ', $bodyClasses); ?>">
<body class="<?php echo $this->direction === 'rtl' ? 'rtl' : ''; ?>">
    <jdoc:include type="message" />
    <jdoc:include type="component" />
</body>
</html>
