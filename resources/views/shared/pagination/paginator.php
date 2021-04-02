<?php
try {
    $controller = app('facilitador.wildcard')->detectController();
    $perPage = $controller::$per_page;
} catch (\Throwable $th) {
    $perPage = 15; // @todo Usar valor padrao
}

if ($paginator->total() > $perPage) : ?>
    <div class="pagination-wrapper">

        <?php // The list of pages ?>
        <?php if ($paginator->lastPage() > 1) : ?>
            <span class="pagination-desktop">
                <?php echo $paginator->render('pedreiro::shared.pagination.desktop') ?>
            </span>

            <?php // On mobile, just show first, prev, current, next, last pagination buttons ?>
            <span class="pagination-mobile">
                <?php echo $paginator->render('pedreiro::shared.pagination.mobile') ?>
            </span>
        <?php endif ?>

        <?php // Per page selector ?>
        <span class="per-page">
            <?php echo $paginator->render('pedreiro::shared.pagination.per_page') ?>
        </span>
    </div>
<?php endif ?>
