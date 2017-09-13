<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.09.17
 * Time: 14:10
 */

namespace Html5\Template;

use Tester\Assert;

require __DIR__ . "/../../vendor/autoload.php";


\Tester\Environment::setup();



$p = new MajaFacade();
$p->setCallHandler(function ($name, $params) {
    return "CALL:$name:" . json_encode($params);
});


Assert::equal(
    "CALL:someFn:[]",
    $p->render("<call:someFn></call:someFn>", [])
);


Assert::equal(
    'CALL:someFn:{"varName":"ABC"}',
    $p->render("<call:someFn><p:varName>ABC</p:varName></call:someFn>", [])
);

Assert::equal(
    'CALL:someFn:{&quot;varName&quot;:&quot;ABC&quot;}',
    $p->render("<call:someFn as='varB'><p:varName>ABC</p:varName></call:someFn>{{ varB }}", [])
);

Assert::equal(
    'CALL:someFn:{"varName":"FROM_VAR"}',
    $p->render("<call:someFn><p:varName select='varA'></p:varName></call:someFn>", ["varA"=>"FROM_VAR"])
);


