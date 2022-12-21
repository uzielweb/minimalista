<?php

defined('_JEXEC') or die;
use Joomla\CMS\Factory as CMSFactory;
$app = CMSFactory::getApplication();
$doc = CMSFactory::getDocument();
$user = CMSFactory::getUser();
$this->language = $doc->language;
$this->direction = $doc->direction;

?>
<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>
<body class="<?php echo $this->direction === 'rtl' ? 'rtl' : ''; ?>">
    <jdoc:include type="message" />
    <jdoc:include type="component" />
</body>
</html>