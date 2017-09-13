<?php


namespace Maja;

use Html5\Template\Directive\GoDirectiveExecBag;
use Html5\Template\GoYamlTemplateParser;
use Html5\Template\MajaFacade;
use Html5\Template\Opt\GoHelper;
use Symfony\Component\Yaml\Yaml;
use Tester\Assert;
use Tester\Environment;

require __DIR__ . "/../../vendor/autoload.php";




Environment::setup();


$t = <<<EOT
A
B
EOT;

Assert::equal("A\nB", GoHelper::StripTextIndention($t));


$t = <<<EOT
    A
    B
EOT;
Assert::equal("A\nB", GoHelper::StripTextIndention($t));


