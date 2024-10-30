<?
function format_size($size) {
      $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
      if ($size == 0) { return('n/a'); } else {
      return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
}

$zipfile = $_POST["zipfile"];
$file = $_POST["file"];


$zip = new ZipArchive();
$zip->open($zipfile);
$zip->addFile($file, basename($file));
$zip->deleteName('test/');
$zip->close();

echo format_size(filesize($zipfile));
?>