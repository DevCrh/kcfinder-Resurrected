<?php

namespace kcfinder;
?>
<script src="js/index.php" type="text/javascript"></script>
<script src="js_localize.php?lng=<?php echo $this->lang ?>" type="text/javascript"></script>
<?php
if ($this->opener['name'] == "tinymce") :
?>
    <script src="<?php echo $this->config['_tinyMCEPath'] ?>/tiny_mce_popup.js" type="text/javascript"></script>
<?php
endif;

if (file_exists("themes/{$this->config['theme']}/js.php")) :
?>
    <script src="themes/<?php echo $this->config['theme'] ?>/js.php" type="text/javascript"></script>
<?php
endif;
?>
<script type="text/javascript">
    _.version = "<?= self::VERSION ?>";
    _.support.zip = <?= (class_exists('ZipArchive') && !$this->config['denyZipDownload']) ? "true" : "false" ?>;
    _.lang = "<?= text::jsValue($this->lang) ?>";
    _.type = "<?= text::jsValue($this->type) ?>";
    _.theme = "<?= text::jsValue($this->config['theme']) ?>";
    _.access = <?= json_encode($this->config['access']) ?>;
    _.dir = "<?= text::jsValue($this->session['dir']) ?>";
    _.uploadURL = "<?= text::jsValue(CUR_PAGE . $this->config['uploadURL']) ?>";
    _.thumbsURL = _.uploadURL + "/<?= text::jsValue($this->config['thumbsDir']) ?>";
    _.opener = <?= json_encode($this->opener) ?>;
    _.cms = "<?= text::jsValue($this->cms) ?>";
    _.dropUploadMaxFilesize = <?= isset($this->config['_dropUploadMaxFilesize']) ? intVal($this->config['_dropUploadMaxFilesize']) : "10485760" ?>;
    _.langs = <?= json_encode($this->getLangs()) ?>;
    $.$.kuki.domain = "<?= text::jsValue($this->config['cookieDomain']) ?>";
    $.$.kuki.path = "<?= text::jsValue($this->config['cookiePath']) ?>";
    $.$.kuki.prefix = "<?= text::jsValue($this->config['cookiePrefix']) ?>";
    $(function() {
        _.resize();
        _.init();
    });
    $(window).resize(_.resize);
</script>