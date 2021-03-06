<?php

namespace Html5\Template;

use Tester\Assert;

require __DIR__ . "/../../vendor/autoload.php";


\Tester\Environment::setup();


$start = microtime(true);

$dirs = glob(__DIR__ . "/tests/*");
$tt = new MajaFacade();
foreach ($dirs as $dir) {
    echo "\nTesting $dir...";
    $data = require ($dir . "/in.php");
    $out = $tt->renderHtmlFile($dir . "/in.html", $data);
    Assert::equal(file_get_contents($dir . "/expected.html"), $out, "Error in check: {$dir}");
    echo " [OK]";
}

echo "\nDuration: " . number_format((microtime(true) - $start), 3);
