<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.minimalista
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

$module = $displayData['module'];
$params = $displayData['params'];
$attribs = $displayData['attribs'];

// Template params
$templateParams = Factory::getApplication()->getTemplate(true)->params;

if ($module->content === null || $module->content === '') {
    return;
}

$moduleTag = $params->get('module_tag', 'div');
$bootstrapSize = (int) $params->get('bootstrap_size', 0);
$headerTag = $params->get('header_tag', 'h3');
$headerClass = $params->get('header_class', '');
$moduleClassSfx = $params->get('moduleclass_sfx', '');

// Ensure $moduleClassSfx is a string
if (!is_string($moduleClassSfx)) {
    $moduleClassSfx = '';
}

// Check if there is a space at the start of the string
if ($moduleClassSfx) {
    if (substr($moduleClassSfx, 0, 1) !== ' ') {
        $moduleClassSfx = ' ' . $moduleClassSfx;
    }
}

$moduleClass = $bootstrapSize > 0 && strpos($moduleClassSfx, 'col') === false ? ' col-' . $templateParams->get('default-bootstrap-desktop', 'lg') . '-' . $bootstrapSize : '';
$moduleClass = $bootstrapSize === 0 && strpos($moduleClassSfx, 'col') === false ? ' col-' . $templateParams->get('default-bootstrap-desktop', 'lg') . '-12' : $moduleClass;

$moduleAttribs = [];
$moduleLayout = str_replace("_:", "", $params->get('layout', 'default'));
$moduleAttribs['class'] = 'module module-default' . ' module-position-' . $module->position . ' module-name-' . (is_object($module) && isset($module->name) ? $module->name : "empty")  . ' module-layout-' . $moduleLayout . $moduleClassSfx . $moduleClass;
$headerAttribs['class'] = 'module-title' . $headerClass;

// Only add aria if the moduleTag is not a div
if ($moduleTag !== 'div') {
    if ($module->showtitle):
        $moduleAttribs['aria-labelledby'] = 'module-' . $module->id;
        $headerAttribs['id'] = 'module-' . $module->id;
    else:
        $moduleAttribs['aria-label'] = $module->title;
    endif;
}

$header = '<' . $headerTag . ' ' . ArrayHelper::toString($headerAttribs) . '>' . $module->title . '</' . $headerTag . '>';
?>
<<?php echo $moduleTag; ?> <?php echo ArrayHelper::toString($moduleAttribs); ?>>


        <?php if ($module->content): ?>
           
                <?php echo str_replace('{minimalista-year}', date('Y'), $module->content); ?>
        
        <?php endif; ?>

</<?php echo $moduleTag; ?>>
