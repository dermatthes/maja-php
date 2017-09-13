<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 12:51
     */

    namespace Html5\Template\Directive;


    use cebe\markdown\GithubMarkdown;
    use cebe\markdown\Markdown;
    use cebe\markdown\MarkdownExtra;
    use Html5\Template\Opt\GoDirectiveExecBag;
    use Html5\Template\Opt\GoHelper;
    use Html5\Template\Opt\GoTemplateDirectiveBag;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\Node\GoRawHtmlNode;

    class GoMarkdownDirective implements GoDirective {



        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["maja:markdown"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return -999;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {

            $flavor = @$node->attributes["flavor"];
            if ($flavor === null)
                $flavor = "default";

            $flavorMap = [
                "default" => Markdown::class,
                "github" => GithubMarkdown::class,
                "extra" => MarkdownExtra::class
            ];

            if ( ! isset ($flavorMap[$flavor]))
                throw new \InvalidArgumentException("maja:markdown flavor='$flavor': Undefined flavor.");


            $mp = new $flavorMap[$flavor]();
            if ( ! $mp instanceof Markdown)
                throw new \Exception("Markdown parser is not instanceof Markdown");

            $text = GoHelper::StripTextIndention($node->getText());
            $txt = $mp->parse($text);
            $node->name = "div";
            $node->ns = null;
            $node->attributes["_maja_orig"] = "maja:markdown";
            $node->childs = [ new GoRawHtmlNode((string)$txt) ];
            return $node;

        }

    }