<?php
defined('_JEXEC') or die;
include_once JPATH_THEMES . '/' . $this->template . '/logic.php';
use Joomla\CMS\Language\Text;

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
