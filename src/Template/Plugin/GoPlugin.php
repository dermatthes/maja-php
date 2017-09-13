<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.09.17
 * Time: 08:56
 */

namespace Html5\Template\Plugin;


use Html5\Template\Opt\GoDirectiveExecBag;
use Html5\Template\Opt\GoTemplateDirectiveBag;

interface GoPlugin
{


    public function register (GoDirectiveExecBag $directiveExecBag, GoTemplateDirectiveBag $goTemplateDirectiveBag);

}