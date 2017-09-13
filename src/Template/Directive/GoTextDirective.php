<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 15.09.16
     * Time: 12:12
     */

    namespace Html5\Template\Directive;


    use Html5\Template\Opt\GoDirectiveExecBag;
    use Html5\Template\Opt\GoTemplateDirectiveBag;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\Node\GoTextNode;

    class GoTextDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->attrToDirective["maja:text"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return 0;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {
            $expression = $node->attributes["maja:text"];

            $val = $execBag->expressionEvaluator->eval($expression, $scope);

            $clone = clone $node;
            if ($val !== null)
                $clone->childs = [new GoTextNode($val)];
            return $clone;
        }
    }