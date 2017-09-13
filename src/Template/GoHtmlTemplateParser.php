<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 20:26
 */

    namespace Html5\Template;
    



    use HTML5\HTMLReader;
    use Html5\Template\Directive\GoDirective;
    use Html5\Template\Node\GoDocumentNode;
    use Html5\Template\Opt\GoHtmlParser;
    use Html5\Template\Opt\GoTemplateDirectiveBag;

    class GoHtmlTemplateParser
    {


        /**
         * @var GoTemplateDirectiveBag
         */
        private $directiveBag;

        /**
         * @var HTMLReader
         */
        private $htmlReader;
        
        public function __construct()
        {
            $this->directiveBag = new GoTemplateDirectiveBag();
            $this->htmlReader = new HTMLReader();
        }


        public function getDirective(string $className) : GoDirective
        {
            return $this->directiveBag->directiveClassNameMap[$className];
        }

        public function addDirective(GoDirective $d)
        {
            $d->register($this->directiveBag);
        }


        public function getDirectiveBag() : GoTemplateDirectiveBag
        {
            return $this->directiveBag;
        }

        public function loadHtml($input)
        {
            $this->htmlReader->loadHtmlString($input);
        }


        public function loadHtmlFile($filename) {
            $this->loadHtml(file_get_contents($filename));
        }
    

        public function parse($templateName="unnamed") : GoDocumentNode
        {
            $rootNode = new GoDocumentNode();
            $rootNode->setTemplateName($templateName);
            $reader = $this->htmlReader;
            
            $reader->setHandler(new GoHtmlParser($rootNode, $this->directiveBag));
            $reader->parse();

            return $rootNode;
        }


    }