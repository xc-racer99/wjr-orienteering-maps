<?php

$relativeLocation = substr(str_replace('\\', '/', __DIR__), strlen($_SERVER['DOCUMENT_ROOT'])) . '/';

$files = array();

/* Make sure we're getting passed a numeric value */
if(is_numeric($_GET["mapId"])) {
	/* Match all files in photos/{mapId}-{someText} */
	$dir = glob( 'photos/' . $_GET["mapId"] . '-*');

	if ($handle = opendir($dir[0])) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				if (preg_match('/.+\.png|.+\.jpg/i', $entry)) {
					$files[] = $relativeLocation . $dir[0] . '/' . $entry;
				}
			}
		}
		closedir($handle);
	}
}

echo json_encode($files);
?>

