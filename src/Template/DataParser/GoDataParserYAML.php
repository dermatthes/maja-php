<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.09.17
 * Time: 16:16
 */

namespace Html5\Template\DataParser;


class GoDataParserYAML implements GoDataParser
{

    public function getKey(): string
    {
        return "YAML";
    }

    public function parse(string $input): array
    {
        return yaml_parse($input, true);
    }
}