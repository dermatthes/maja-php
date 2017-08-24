<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 20:26
 */

    namespace Html5\Template;
    
    use Html5\Template\Directive\GoBindDirective;
    use Html5\Template\Directive\GoCallDirective;
    use Html5\Template\Directive\GoCallMacroDirective;
    use Html5\Template\Directive\GoClassDirective;
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
    use HTML5\Tokenizer\HtmlCallback;
    use Symfony\Component\Yaml\Yaml;


    class GoYamlTemplateParser
    {


        /**
         * @var GoTemplateDirectiveBag
         */
        private $directiveBag;


        private $mYamlData = [];

        private $emptyTags = ["meta"=>true, "img"=>true, "br"=>true, "hr"=>true, "input"=>true, "link"=>true];

        public function __construct()
        {
            $this->directiveBag = new GoTemplateDirectiveBag();

            $this->addDirective(new GoIfDirective());
            $this->addDirective(new GoForeachDirective());
            $this->addDirective(new GoBindDirective());
            $this->addDirective(new GoHtmlDirective());
            $this->addDirective(new GoClassDirective());
            $this->addDirective(new GoRepeatDirective());
            $this->addDirective(new GoMacroDirective());
            $this->addDirective(new GoCallMacroDirective());
            $this->addDirective(new GoDumpDirective());
            $this->addDirective(new GoInlineTextDirective());
            $this->addDirective(new GoParamDirective());
            $this->addDirective(new GoStructDirective());
            $this->addDirective(new GoExtendsDirective());
            $this->addDirective(new GoCallDirective());
            $this->addDirective(new GoTextDirective());
            $this->addDirective(new GoNsCallDirective());
            $this->addDirective(new GoNsParamDirective());
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


        public function loadYaml($input)
        {
            $this->mYamlData = Yaml::parse($input);
        }


        public function loadHtmlFile($filename) {
            $this->loadHtml(file_get_contents($filename));
        }
    


        protected function createElement ($elemDef, GoNode $parentNode) {
            $arr = explode("@", $elemDef);
            $tagName = trim(strtolower(array_shift($arr)));
            $attrs = [];
            $qmIndex = 0;
            foreach ($arr as $attdef) {
                if ($attdef === "")
                    continue;
                @list ($key, $val) = explode("=", $attdef, 2);
                if ( ! isset ($val)) {
                    $attrs[trim ($key)] = null;
                    continue;
                }
                $val = trim ($val);
                if (isset ($arrayArgs)) {
                    if ($val == "?" && isset ($arrayArgs[$qmIndex])) {
                        $val = $arrayArgs[$qmIndex];
                        $qmIndex++;
                    }
                }
                $attrs[trim($key)] = $val;
            }
            $newNode = new GoElementNode();
            if (isset ($this->emptyTags[$tagName]))
                $newNode->isEmptyElement = true;
            $newNode->name = $tagName;
            $newNode->parent = $parentNode;
            $newNode->attributes = $attrs;
            if ($newNode->parent instanceof GoDocumentNode) {
                $newNode->preWhiteSpace = "\n";
            } else {
                $newNode->preWhiteSpace = $newNode->parent->preWhiteSpace . "    ";
            }
            return $newNode;
        }


        protected function parseLevel ($key, $value, GoNode $parentNode, GoDocumentNode $rootNode) {
            if (is_string($key)) {
                // Element
                $node = $this->createElement($key, $parentNode);
                $parentNode->childs[] = $node;
                if (is_array($value)) {
                    foreach ($value as $key => $value)
                        $this->parseLevel($key, $value, $node, $rootNode);
                }
                return;
            }
            if (is_array($value)) {
                foreach ($value as $index => $curVal) {
                    $this->parseLevel($index, $curVal, $parentNode, $rootNode);
                }
                return;
            }
            $parentNode->childs[] = new GoTextNode($value);
            return;
        }



        public function parse($templateName="unnamed") : GoDocumentNode
        {
            $rootNode = new GoDocumentNode();
            $rootNode->setTemplateName($templateName);
            $this->parseLevel("html", $this->mYamlData["html"], $rootNode, $rootNode);
            return $rootNode;
        }


    }