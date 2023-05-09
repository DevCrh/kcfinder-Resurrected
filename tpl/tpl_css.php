<link href="css/index.php" rel="stylesheet" type="text/css" />
<style type="text/css">
    div.file {
        width: <?= $this->config['thumbWidth'] ?>px;
        height: <?= $this->config['thumbHeight'] + 45?>px;
        background-color: #EAEAEA;
    }

    div.file .thumb {
        width: <?= $this->config['thumbWidth'] ?>px;
        height: <?= $this->config['thumbHeight'] ?>px
    }

    div.file .name {
        color: green;
        font-size: 12px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        
    }
</style>
<link href="themes/<?= $this->config['theme'] ?>/css.php" rel="stylesheet" type="text/css" />