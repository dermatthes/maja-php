<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 13.09.17
 * Time: 12:18
 */

namespace Html5\Template\Plugin;


use HTML5\HTMLReader;
use Html5\Template\Directive\GoBreakDirective;
use Html5\Template\Directive\GoClassDirective;
use Html5\Template\Directive\GoContinueDirective;
use Html5\Template\Directive\GoDumpDirective;
use Html5\Template\Directive\GoForeachDirective;
use Html5\Template\Directive\GoHtmlDirective;
use Html5\Template\Directive\GoIfDirective;
use Html5\Template\Directive\GoInlineTextDirective;
use Html5\Template\Directive\GoMacroDirective;
use Html5\Template\Directive\GoMarkdownDirective;
use Html5\Template\Directive\GoNsCallDirective;
use Html5\Template\Directive\GoNsParamDirective;
use Html5\Template\Directive\GoRepeatDirective;
use Html5\Template\Directive\GoStructDirective;
use Html5\Template\Directive\GoTextDirective;
use Html5\Template\Opt\GoDirectiveExecBag;
use Html5\Template\Opt\GoTemplateDirectiveBag;

class GoBasePlugin implements GoPlugin
{

    public function register(GoDirectiveExecBag $directiveExecBag, GoTemplateDirectiveBag $goTemplateDirectiveBag, HTMLReader $HTMLReader)
    {
        (new GoIfDirective())->register($goTemplateDirectiveBag);
        (new GoForeachDirective())->register($goTemplateDirectiveBag);
        (new GoHtmlDirective())->register($goTemplateDirectiveBag);
        (new GoClassDirective())->register($goTemplateDirectiveBag);
        (new GoRepeatDirective())->register($goTemplateDirectiveBag);
        (new GoMacroDirective())->register($goTemplateDirectiveBag);
        (new GoDumpDirective())->register($goTemplateDirectiveBag);
        (new GoInlineTextDirective())->register($goTemplateDirectiveBag);
        (new GoStructDirective())->register($goTemplateDirectiveBag);
        (new GoTextDirective())->register($goTemplateDirectiveBag);
        (new GoNsCallDirective())->register($goTemplateDirectiveBag);
        (new GoNsParamDirective())->register($goTemplateDirectiveBag);

        (new GoMarkdownDirective())->register($goTemplateDirectiveBag);
        $HTMLReader->addNoParseTag("maja:markdown");

        (new GoBreakDirective())->register($goTemplateDirectiveBag);
        (new GoContinueDirective())->register($goTemplateDirectiveBag);
    }
}