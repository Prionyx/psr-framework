<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 */
?>

<?php $this->extend('layout/columns'); ?>

<?php $this->params['title'] = 'Cabinet'; ?>

<?php $this->beginBlock('sidebar') ?>
    <div class="panel panel-default">
        <div class="panel-heading">Cabinet</div>
        <div class="panel-body">Cabinet navigation</div>
    </div>
<?php $this->endBlock(); ?>

<ul class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">Cabinet</li>
</ul>

<h1>Cabinet of <? = htmlentities(1name, ENT_QUOTES | ENT_SUBSTITUTE) ?></h1>
