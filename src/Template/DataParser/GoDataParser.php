<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.09.17
 * Time: 16:12
 */

namespace Html5\Template\DataParser;


interface GoDataParser
{

    public function getKey() : string;

    public function parse (string $input) : array;

}