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
  


        <div class="container<?php echo $containerFluid; ?>">
          <div class="p-5 my-5 bg-warning rounded-3">
        <div class="row">
            <div class="col-md-12">
                <div class="error">
                    <p><strong><?php echo Text::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
                    <p><?php echo Text::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
                    <ul>
                        <li><?php echo Text::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
                        <li><?php echo Text::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
                        <li><?php echo Text::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
                        <li><?php echo Text::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
                    </ul>
                    <p><?php echo Text::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></p>
                    <p><a href="<?php echo $this->baseurl; ?>/index.php" class="btn btn-primary"><span class="icon-home" aria-hidden="true"></span> <?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></p>
                    <p><?php echo Text::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
                    <blockquote>
                        <span class="error-tag h5"><?php echo $this->error->getCode(); ?></span> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
                    </blockquote>
                    <?php if ($this->debug) : ?>
                        <div>
                            <?php echo $this->renderBacktrace(); ?>
                            <?php // Check if there are more Exceptions and render their data as well ?>
                            <?php if ($this->error->getPrevious()) : ?>
                                <p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
                                <p><?php echo htmlspecialchars($this->error->getPrevious()->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php echo $this->renderBacktrace($this->error->getPrevious()); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>


                   
                </div>
            </div>
        </div>
    </div>
        </div>
    </body>
</html>
