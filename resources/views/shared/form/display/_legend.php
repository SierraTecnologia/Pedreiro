<?php // Just the legend for the display_module?>

<div class="legend">
        <?php echo __('pedreiro::display.legend.title'); ?>
        <?php if (!empty($item) && ($url = $item->getUriAttribute())) : ?>
            <a href="<?php echo $url?>"
                target="_blank"
                class="btn btn-secondary btn-sm outline float-right">
                <span class="glyphicon glyphicon-bookmark"></span>
                <?php echo __('pedreiro::display.legend.view'); ?>
            </a>
        <?php endif ?>
</div>
