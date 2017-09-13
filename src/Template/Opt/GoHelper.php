<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.09.17
 * Time: 16:08
 */

namespace Html5\Template\Opt;


class GoHelper
{

    /**
     * Strip any indention (identified by the first Text-Line)
     *
     * @param $input
     *
     * @return string
     */
    public static function StripTextIndention (string $input) : string {
        $arr = explode("\n", $input);

        // Go to first line with text
        $result = [];
        for ($i = 0; $i < count ($arr); $i++) {
            if (trim ($arr[$i]) === "")
                continue;
            break;
        }

        if ($i == count ($arr))
            return "";

        if ( ! preg_match ("/^(\s*)/", $arr[$i], $matches)) {
            throw new \InvalidArgumentException("Cannot determine indention. ('$arr[$i]')");
        }
        $indention = $matches[1];


        for (; $i<count ($arr); $i++) {
            if ($indention != "" && strpos ($arr[$i], $indention) === 0) {
                $arr[$i] = substr($arr[$i], strlen($indention));
            }
            $result[] = $arr[$i];
        }

        return implode("\n", $result);
    }
}