<?php
/**
 * @var \Framework\Template\PhpRenderer $this
 * @var \Throwable $exception
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Error</title>
    <?= $this->renderBlock('meta') ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
</head>
<body>
<div class="app-content">
    <main class="container">
        <h1>Excepiton: <?= $this->encode($exception->getMessage()) ?></h1>

        <p>Code: <?= $this->encode($exception->getCode()) ?></p>
        <p><?= $this->encode($exception->getFile()) ?> on line <?= $this->encode($exception->getLine()) ?></p>
        <?php foreach ($exception->getTrace() as $trace): ?>
            <p><?= $this->encode($trace['file']) ?> on line <?= $this->encode($trace['line']) ?></p>
        <?php endforeach ?>
    </main>
</div>
</body>
</html>

