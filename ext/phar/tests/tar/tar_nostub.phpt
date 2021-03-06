--TEST--
Phar: tar-based phar, third-party tar with no stub, Phar->getStub()
--SKIPIF--
<?php if (!extension_loaded('phar')) die('skip'); ?>
--INI--
phar.readonly=1
phar.require_hash=0
--FILE--
<?php
include dirname(__FILE__) . '/files/tarmaker.php.inc';
$fname = dirname(__FILE__) . '/tar_nostub.phar.tar';
$alias = 'phar://' . $fname;
$fname2 = dirname(__FILE__) . '/tar_nostub.tar';

$tar = new tarmaker($fname, 'none');
$tar->init();
$tar->addFile('tar_004.php', '<?php var_dump(__FILE__);');
$tar->addFile('internal/file/here', "hi there!\n");
$tar->close();

try {
	$phar = new Phar($fname);
	var_dump($phar->getStub());
} catch (Exception $e) {
	echo $e->getMessage()."\n";
}

copy($fname, $fname2);

try {
	$phar = new PharData($fname2);
	var_dump($phar->getStub());
} catch (Exception $e) {
	echo $e->getMessage()."\n";
}

?>
===DONE===
--CLEAN--
<?php
@unlink(dirname(__FILE__) . '/tar_nostub.phar.tar');
@unlink(dirname(__FILE__) . '/tar_nostub.tar');
?>
--EXPECTF--
RecursiveDirectoryIterator::__construct(phar://%star_nostub.phar.tar/): failed to open dir: '%star_nostub.phar.tar' is not a phar archive. Use PharData::__construct() for a standard zip or tar archive
phar url "phar://%star_nostub.phar.tar/" is unknown
string(0) ""
===DONE===
