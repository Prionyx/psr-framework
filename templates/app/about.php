<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 */
?>

<?php $this->extend('layout/default'); ?>

<?php $this->params['title'] = 'About'; ?>

<?php $this->beginBlock('meta'); ?>
    <meta name="description" content="About Page description" />
<?php $this->endBlock(); ?>

<ul class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">About</li>
</ul>

<h1>About the site</h1>
