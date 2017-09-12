<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 17:43
     */


    namespace Html5\Template\Directive;

    

    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoDefineDirective implements GoDirective {

        public function register(GoTemplateDirectiveBag $bag) {
            $bag->elemToDirective["maja:define"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int {
            return 1;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag) {
            $as = @$node->attributes["as"];
            $parse = @$node->attributes["parse"];

            $select = @$node->attributes["select"];

            if ($select !== null) {
                $scope[$as] = $execBag->expressionEvaluator->eval($select, $scope);
                return;
            }
            $scope[$as] = $execBag->dataParse($node->getText(), $parse);
            return;
        }
    }

