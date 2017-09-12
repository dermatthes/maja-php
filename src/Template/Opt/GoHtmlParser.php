<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 12.09.17
 * Time: 16:07
 */

namespace Html5\Template\Opt;


use Html5\Template\Exception\TemplateParsingException;
use Html5\Template\GoTemplateDirectiveBag;
use Html5\Template\Node\GoCommentNode;
use Html5\Template\Node\GoDocumentNode;
use Html5\Template\Node\GoElementNode;
use Html5\Template\Node\GoNode;
use Html5\Template\Node\GoTextNode;
use HTML5\Tokenizer\HtmlCallback;

class GoHtmlParser implements HtmlCallback {

    private $html5EmptyTags = ["img", "meta", "br", "hr", "input", "link"]; // Tags to treat as empty although they're not

    /**
     * @var GoNode
     */
    private $curNode;

    /**
     * @var GoDocumentNode
     */
    private $rootNode;

    private $curLine = 1;
    /**
     * @var GoTemplateDirectiveBag
     */
    private $directiveBag;

    public function __construct(GoNode $rootNode, GoTemplateDirectiveBag $directiveBag) {
        $this->curNode = $rootNode;
        $this->rootNode = $rootNode;
        $this->directiveBag = $directiveBag;
    }

    private $curWhiteSpace = "";

    public function onWhitespace(string $ws) {
        $this->curLine += substr_count($ws, "\n");
        $this->curWhiteSpace = $ws;
    }

    public function onTagOpen(string $name, array $attributes, $isEmpty, $ns=null) {
        $newNode = new GoElementNode();

        $newNode->ns = $ns;
        $newNode->name = $name;
        $newNode->lineNo = $this->curLine;

        $newNode->isEmptyElement = $isEmpty;

        if (in_array($name, $this->html5EmptyTags)) {
            $newNode->isEmptyElement = true;
        }

        $newNode->useInlineTextDirective($this->directiveBag->textDirective);


        if ($newNode->ns !== null && isset ($this->directiveBag->elemToDirective["{$newNode->ns}:{$newNode->name}"])) {
            $newNode->useDirective($this->directiveBag->elemToDirective["{$newNode->ns}:{$newNode->name}"]);
        } elseif ($newNode->ns !== null && isset ($this->directiveBag->elemNsToDirective[$newNode->ns])) {
            $newNode->useDirective($this->directiveBag->elemNsToDirective[$newNode->ns]);
        } else if (isset ($this->directiveBag->elemToDirective[$newNode->name])) {
            $newNode->useDirective($this->directiveBag->elemToDirective[$newNode->name]);
        }

        $newNode->preWhiteSpace = $this->curWhiteSpace;
        $this->curWhiteSpace = "";
        $newNode->parent = $this->curNode;


        foreach ($attributes as $attributeName => $attributeValue) {
            if (isset ($this->directiveBag->attrToDirective[$attributeName])) {
                $newNode->useDirective($this->directiveBag->attrToDirective[$attributeName]);
            }
            $newNode->attributes[$attributeName] = $attributeValue;
        }


        $newNode->postInit();

        $this->curNode->childs[] = $newNode;
        if ( ! $newNode->isEmptyElement) {
            $this->curNode = $newNode;
        }
    }

    public function onText(string $text) {
        $this->curLine += substr_count($text, "\n");

        $text = new GoTextNode($text, $this->directiveBag->textDirective);
        $text->preWhiteSpace = $this->curWhiteSpace;
        $this->curWhiteSpace = "";
        $this->curNode->childs[] = $text;
    }

    public function onTagClose(string $name, $ns=null) {
        if (in_array($name, $this->html5EmptyTags)) {
            //Ignore
            return;
        }
        $this->curNode->postWhiteSpace = $this->curWhiteSpace;
        if ($this->curNode instanceof GoDocumentNode) {
            throw new TemplateParsingException("Closing Tag mismatch in template '{$this->rootNode->getTemplateName()}'.");
        }
        $this->curNode = $this->curNode->parent;
    }

    public function onProcessingInstruction(string $data) {
        if ($this->curNode instanceof GoDocumentNode) {
            $this->curNode->processingInstructions = $data;
        }
    }

    public function onComment(string $data) {
        $this->curLine += substr_count($data, "\n");
        $this->curNode->childs[] = $newChild = new GoCommentNode($data);
        $newChild->preWhiteSpace = $this->curWhiteSpace;
        $this->curWhiteSpace = "";
    }
}