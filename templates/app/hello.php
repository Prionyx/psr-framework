<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 */
?>

<?php $this->extend('layout/default'); ?>

<?php $this->params['title'] = 'Hello'; ?>

<?php $this->beginBlock('meta'); ?>
    <meta name="description" content="Home Page description" />
<?php $this->endBlock(); ?>

<?php $this->beginBlock('content') ?>
    <div class="jumbotron">
        <h1>Hello!</h1>
        <p>
            Congratulatoins! You have successfully created your application.
        </p>
    </div>
<?php $this->endBlock() ?>
