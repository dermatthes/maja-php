<?php


namespace Maja;

use Html5\Template\Directive\GoDirectiveExecBag;
use Html5\Template\GoYamlTemplateParser;
use Html5\Template\MajaFacade;
use Symfony\Component\Yaml\Yaml;
use Tester\Environment;

require __DIR__ . "/../../vendor/autoload.php";




Environment::setup();

print_r (Yaml::parse(file_get_contents(__DIR__ . "/input.yml")));

$t = new MajaFacade();

$parser = new GoYamlTemplateParser();
$parser->loadYaml(file_get_contents(__DIR__ . "/input.yml"));
$doc = $parser->parse();


$data = [];
echo $doc->run($data, $t->getExecBag());
