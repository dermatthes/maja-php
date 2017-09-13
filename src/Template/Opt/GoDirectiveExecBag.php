<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 12:28
     */


    namespace Html5\Template\Opt;


    use Html5\Template\DataParser\GoDataParser;
    use Html5\Template\Expression\GoExpressionEvaluator;
    use Prophecy\Exception\InvalidArgumentException;

    class GoDirectiveExecBag {

        public function __construct(GoExpressionEvaluator $expressionCompiler) {
            $this->expressionEvaluator = $expressionCompiler;
        }


        /**
         * @var GoExpressionEvaluator
         */
        public $expressionEvaluator;
        
        public $macros = [];

        public $scopePrototype = [];

        public $dataToReturnScope = [];


        /**
         * @var GoDataParser[]
         */
        public $dataTextParsers = [];

        public function dataParse ($input, $parse) {
            if ($parse === null)
                return $input;
            if ( ! isset ($this->dataTextParsers[strtoupper($parse)]))
                throw new InvalidArgumentException("Unrecognized DataParser: parse='$parse': '$parse' is not defined");
            return $this->dataTextParsers[strtoupper($parse)]->parse($input);
        }

    }