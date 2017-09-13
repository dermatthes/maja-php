<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 23:49
     */

    namespace Html5\Template;

   
    use Html5\Template\Directive\GoNsCallDirective;
    use Html5\Template\Expression\GoExpressionEvaluator;
    use Html5\Template\Node\GoDocumentNode;
    use Html5\Template\Opt\GoDirectiveExecBag;
    use Html5\Template\Plugin\GoBasePlugin;
    use Html5\Template\Plugin\GoPlugin;

    class MajaFacade
    {

        /**
         * @var GoHtmlTemplateParser
         */
        private $mParser;


        /**
         * @var GoDirectiveExecBag
         */
        private $mExecBag;


        
        public function __construct()
        {
            $this->mParser = new GoHtmlTemplateParser();
            $this->mExecBag = new GoDirectiveExecBag(new GoExpressionEvaluator());
            $this->addPlugin(new GoBasePlugin());
        }



        public function setSuperGlobals (array $superGlobals) : self
        {
            $this->mExecBag->scopePrototype = $superGlobals;
            return $this;
        }


        public function addPlugin (GoPlugin $plugin) : self
        {
            $plugin->register($this->mExecBag, $this->mParser->getDirectiveBag());
            return $this;
        }

        public function setCallHandler (callable $callback) : self
        {
            $this->mParser->getDirective(GoNsCallDirective::class)->setCallback($callback);
            return $this;
        }

        public function addFunction ($name, callable $callback) : self
        {
            $this->mExecBag->expressionEvaluator->register($name, $callback);
            return $this;
        }


        public function getParser () : GoHtmlTemplateParser{
            return $this->mParser;
        }

        public function getExecBag () : GoDirectiveExecBag {
            return $this->mExecBag;
        }



        public function build(string $inputTemplateData, string $templateName="unnamed") : GoDocumentNode {
            $this->mParser->loadHtml($inputTemplateData);
            $template = $this->mParser->parse($templateName);
            return $template;
        }


        public function render(string $inputTemplateData, array $scopeData, &$structOutputData = [], string $templateName="unnamed") : string
        {
            $scope = $this->mExecBag->scopePrototype;
            foreach ($scopeData as $key => $val) {
                $scope[$key] = $val;
            }

            $template = $this->build($inputTemplateData, $templateName);
            /*
            $cacheFile = "/tmp/cache." . md5($inputTemplateData);
            if (file_exists($cacheFile))
                $template = unserialize(file_get_contents($cacheFile));
            else
                file_put_contents($cacheFile, serialize());

            */

            $ret = $template->run($scope, $this->mExecBag);
            if (is_array($ret))
                throw new \InvalidArgumentException("render() cannot handle go-struct. Use renderStruct() to return array data.");
            return $ret;
        }


        public function buildFile ($filename) : GoDocumentNode {
            return $this->build(file_get_contents($filename), $filename);
        }

        public function renderHtmlFile(string $filename, array $scopeData = []) : string
        {
            return $this->render(file_get_contents($filename), $scopeData, $data, $filename);
        }



    }