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
//  template params
$templateParams = Factory::getApplication()->getTemplate(true)->params;
if ($module->content === null || $module->content === '') {
    return;
}
$moduleTag = $params->get('module_tag', 'div');
$bootstrapSize = (int) $params->get('bootstrap_size', 0);
// check if hav col or col-auto in the module class sfx
$moduleClass = '';  // Default value if 'col' is not found
$moduleClass = $bootstrapSize !== 0 ? ' col-' . $templateParams->get('default-bootstrap-desktop') . '-' . $bootstrapSize : ' col-12';
$moduleClassSfx = $params->get('moduleclass_sfx');
if ($moduleClassSfx !== null && strpos($moduleClassSfx, 'col') === false) {
    $moduleClass = $bootstrapSize !== 0 ? ' col-' . $templateParams->get('default-bootstrap-desktop') . '-' . $bootstrapSize : ' col-12';
}
$headerTag = $params->get('header_tag', 'h3');
$headerClass = $params->get('header_class', '');
$moduleClassSfx = $params->get('moduleclass_sfx');
// check if have space in the start of the string
if ($moduleClassSfx) {
    if (substr($moduleClassSfx, 0, 1) !== ' ') {
        $moduleClassSfx = ' ' . $moduleClassSfx;
    }
}
$moduleAttribs = [];
$moduleLayout = str_replace("_:", "", $params->get('layout', 'default'));
$moduleAttribs['class'] = 'module module-default' . ' module-position-' . $module->position . ' module-name-' . $module->name .' module-layout-' . $moduleLayout . $moduleClassSfx . $moduleClass;
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
<div class="inner">
   <?php if ($module->showtitle): ?>
    <?php echo $header; ?>
<?php endif;?>
   <?php if ($module->content): ?>
    <div class="module-content">
        <?php echo str_replace("{year}", date("Y"), $module->content); ?>
    </div>
<?php endif;?>
</div>
</<?php echo $moduleTag; ?>>
