<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 22:46
 */

    namespace Html5\Template\Directive;
    
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\Opt\GoDirectiveExecBag;
    use Html5\Template\Opt\GoTemplateDirectiveBag;

    class GoIfDirective implements GoPreDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->attrToDirective["maja:if"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return 999;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {
            $code = $node->attributes["maja:if"];
            if ($execBag->expressionEvaluator->eval($code, $scope)) {
                return $node;
            }
            return FALSE; //Skip other Directives
        }
    }