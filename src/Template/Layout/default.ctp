<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketing : <?= $this->fetch('title') ?></title>
    <?=
        $this->Html->meta('icon').
        $this->fetch('meta').
        $this->Html->css([
            '/plugins/bootstrap/css/bootstrap.min',
            '/plugins/jModal/css/jmodal'
            ]).
        $this->fetch('css')
    ?>
</head>
<body>
    <?=
        $this->Element('header').
        '<main class="container">'.
            $this->fetch('content').
        '</main>'.
        $this->Html->script([
            '/plugins/jquery/jquery.1.9.1.min',
            '/plugins/bootstrap/js/bootstrap.min',
            '/plugins/underscore/underscore.min',
            '/plugins/jModal/js/jmodal'
            ]).
        $this->fetch('script')
    ?>
</body>
</html>
