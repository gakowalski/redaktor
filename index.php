<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (($_GET['pass'] ?? '') !='gk') die('Not authorized');

$path = realpath($_REQUEST['path'] ?? '.');
$path_limit = '/var/www/html';
if (strpos($path, $path_limit) === false) die('Not authorized');

if (is_dir($path) == false && isset($_POST['content'])) {
  file_put_contents($path, html_entity_decode($_POST['content']));
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?= basename($path) ?></title>
</head>
<body>
<h2>
  <?php $path_accumulator = ''; foreach(explode(DIRECTORY_SEPARATOR, $path) as $index => $path_part): ?>
  <?php if ($index == 0) continue; ?>
  /<a href="?pass=<?= $_GET['pass'] . '&path=' . ($path_accumulator .= DIRECTORY_SEPARATOR . $path_part) ?>"><?= $path_part ?></a>
  <?php endforeach; ?>
</h2>
<?php
  if (is_dir($path)):
    $files = array_diff(scandir($path), ['.']); ?>
    <table border="0" cellspacing="0" cellpadding="5">
    <?php $count = 0; foreach ($files as $file): ?>
    <tr <?= $count++ % 2 ? 'bgcolor="lightgray"' : '' ?> >
<td><?= is_dir($path . '/' . $file) ? '📁' : '📄' ?></td>
<td><a href="?path=<?= $path.'/'.$file ?>&pass=<?= $_GET['pass'] ?>"><?= $file ?></a></td>
<td><?= is_writable($path .'/'.$file) ? '✍ writable' : '🔒 not writable' ?></td>
</tr>
   <?php endforeach; ?>
    </table>
<?php else: ?>
<form method="POST">
<input type="hidden" name="path" value="<?= $path ?>">
<div id="editor" style="height: 80vh;"></div>
<textarea id="content" name="content" style="display: none"><?= htmlentities(file_get_contents($path)); ?></textarea>
<br><input type="submit" style="padding: 1rem; background-color: lightgreen">
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
