<?php

require 'config.php';
extract($config);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (hash('sha256', ($_GET['pass'] ?? '')) != $security_password_hash) die('Not authorized');

$path = realpath($_REQUEST['path'] ?? '.');
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
  <?= DIRECTORY_SEPARATOR ?><a href="?pass=<?= $_GET['pass'] . '&path=' . ($path_accumulator .= DIRECTORY_SEPARATOR . $path_part) ?>"><?= $path_part ?></a>
  <?php endforeach; ?>
</h2>

<?php // DIRECTORY BROWSER
  if (is_dir($path)):
    $files = array_diff(scandir($path), ['.']); ?>
    <p>Info: CTRL+SHIFT+File opens file/folder in new tab</p>
    <table border="0" cellspacing="0" cellpadding="5">
    <?php $count = 0; foreach ($files as $file): ?>
    <tr <?= $count++ % 2 ? 'bgcolor="lightgray"' : '' ?> >
<td><?= is_dir($path . DIRECTORY_SEPARATOR . $file) ? '📁' : '📄' ?></td>
<td><a href="?path=<?= $path.DIRECTORY_SEPARATOR.$file ?>&pass=<?= $_GET['pass'] ?>"><?= $file ?></a></td>
<td><?= is_writable($path .DIRECTORY_SEPARATOR.$file) ? '✍ writable' : '🔒 not writable' ?></td>
</tr>
   <?php endforeach; ?>
    </table>
<hr>

<?php else: // FILE EDITOR ?>

<p><?= pathinfo($path, PATHINFO_EXTENSION) == 'php' ? `php -l $path` : '' ?></p>
<form method="POST">
<input type="hidden" name="path" value="<?= $path ?>">
<input id="submit" type="submit" style="padding: 1rem; background-color: lightgreen">
<div id="editor"></div>
<textarea id="content" name="content" style="display: none"><?= htmlentities(file_get_contents($path)); ?></textarea>
</form>
<script src="js/editor.bundle.js" type="text/javascript" charset="utf-8"></script>
<?php endif; ?>

</body>
</html>
