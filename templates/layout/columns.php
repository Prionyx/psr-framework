<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 */
?>

<?php $this->extend('layout/default'); ?>

<div class="row">
    <div class="col-md-9">
        <?= $content ?>
    </div>
    <?= $this->renderBlock('sidebar') ?>
</div>
