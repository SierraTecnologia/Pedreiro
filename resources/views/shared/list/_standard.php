<div class="standard-list <?php echo $layout!='form'?'fieldset':null?>"
    data-js-view="standard-list"
    data-controller-route="<?php echo URL::to(app('pedreiro.url')->action($controller))?>"
    data-position-offset="<?php echo $paginator_from?>"
    data-with-trashed="<?php echo $with_trashed?>"<?php 
    if ($parent_controller) { echo ' data-parent-controller="'.$parent_controller.'"'; } ?>
  >

    <?php
    // Create the page title for the sidebar layout
    if ($layout == 'sidebar') {
        echo View::make('pedreiro::shared.list._sidebar_header', $__data)->render();

        // Create the page title for a full page layout
    } else if ($layout == 'full') {
        echo View::make('pedreiro::shared.list._full_header', $__data)->render();
    }
    ?>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="box box-info panel-info card-info card card-info">
            <div class="box-header panel-header card-header with-border">
                <h3 class="box-title panel-title card-title">Dispositivos</h3>
            </div>
            <!-- /.box-header panel-header card-header -->
            <div class="box-body panel-body card-body table-responsive p-0">
            
       
<?php
    // Render the full table.  This could be broken up into smaller chunks but
    // leaving it as is until the need arises
    echo '<div class="listing-wrapper">'
    .View::make('pedreiro::shared.list._table', $__data)->render()
    .'</div>';

    // Add sidebar pagination
    if (!empty($layout) && $layout != 'full' && $count > count($listing)) : ?>
        <a href="<?php echo app('pedreiro.url')->relative('index', $parent_id, $controller)?>" class="btn btn-secondary btn-sm btn-block full-list"><?php echo __('pedreiro::list.standard.related', ['title' => title_case($title)]) ?></b></a>
    <?php endif ?>


<?php
// Render pagination
echo View::make('pedreiro::shared.pagination.index', $__data)->render();

?>
            </div>
        </div>
    </div>
</div>
