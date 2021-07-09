<?php
/**
 * Default layout
 *
 * @var string $title
 * @var \App\View\AppView $this
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title><?= $this->fetch('title') ?></title>
</head>
<body>

<?= $this->element('navbar') ?>
<div class="container">
    <?= $this->fetch('content') ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
<?= $this->fetch('script') ?>
</body>
</html>
