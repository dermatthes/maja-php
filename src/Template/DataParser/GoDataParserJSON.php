<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.09.17
 * Time: 16:16
 */

namespace Html5\Template\DataParser;


class GoDataParserJSON implements GoDataParser
{

    public function getKey(): string
    {
        return "JSON";
    }

    public function parse(string $input): array
    {
        return json_decode($input, true);
    }
}