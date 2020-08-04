<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (($_GET['pass'] ?? '') !='gk') return '';

$path = $_REQUEST['path'] ?? '.';
if (is_dir($path) == false && isset($_POST['content'])) {
  file_put_contents($path, html_entity_decode($_POST['content']));
}

// https://stackoverflow.com/a/21073572/925196

function scandir_r($dir = '.') {
/*    $scan = array_diff(scandir($dir), array('.', '..');
    $tree = array();
    $queue = array();
    foreach ( $scan as $item ) 
        if ( is_file($item) ) $queue[] = $item;
        else $tree[] = scanRecursively($dir . '/' . $item);
    return array_merge($tree, $queue);*/
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
