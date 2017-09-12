<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 20:26
 */

    namespace Html5\Template;
    
    use Html5\Template\Directive\GoBindDirective;
    use Html5\Template\Directive\GoBreakDirective;
    use Html5\Template\Directive\GoCallDirective;
    use Html5\Template\Directive\GoCallMacroDirective;
    use Html5\Template\Directive\GoClassDirective;
    use Html5\Template\Directive\GoContinueDirective;
    use Html5\Template\Directive\GoDirective;
    use Html5\Template\Directive\GoDumpDirective;
    use Html5\Template\Directive\GoExtendsDirective;
    use Html5\Template\Directive\GoForeachDirective;
    use Html5\Template\Directive\GoHtmlDirective;
    use Html5\Template\Directive\GoIfDirective;
    use Html5\Template\Directive\GoInlineTextDirective;
    use Html5\Template\Directive\GoMacroDirective;
    use Html5\Template\Directive\GoNsCallDirective;
    use Html5\Template\Directive\GoNsParamDirective;
    use Html5\Template\Directive\GoTextDirective;
    use Html5\Template\Directive\GoRepeatDirective;
    use Html5\Template\Directive\GoParamDirective;
    use Html5\Template\Directive\GoStructDirective;
    use Html5\Template\Exception\TemplateParsingException;
    use Html5\Template\Node\GoCommentNode;
    use Html5\Template\Node\GoDocumentNode;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\Node\GoNode;
    use Html5\Template\Node\GoTextNode;
    use HTML5\HTMLReader;
    use Html5\Template\Opt\GoHtmlParser;
    use HTML5\Tokenizer\HtmlCallback;


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

            $this->addDirective(new GoIfDirective());
            $this->addDirective(new GoForeachDirective());
            $this->addDirective(new GoHtmlDirective());
            $this->addDirective(new GoClassDirective());
            $this->addDirective(new GoRepeatDirective());
            $this->addDirective(new GoMacroDirective());
            $this->addDirective(new GoDumpDirective());
            $this->addDirective(new GoInlineTextDirective());
            $this->addDirective(new GoStructDirective());
            $this->addDirective(new GoTextDirective());
            $this->addDirective(new GoNsCallDirective());
            $this->addDirective(new GoNsParamDirective());

            $this->addDirective(new GoBreakDirective());
            $this->addDirective(new GoContinueDirective());
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