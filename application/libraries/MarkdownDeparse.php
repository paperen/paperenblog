<?php

/**
 * default configuration
 */
define('MDFY_BODYWIDTH', false);
define('MDFY_KEEPHTML', true);

/**
 * HTML to Markdown converter class
 */
class MarkdownDeparse
{
    /**
     * html parser object
     *
     * @var parseHTML
     */
    protected $parser;

    /**
     * markdown output
     *
     * @var string
     */
    protected $output;

    /**
     * stack with tags which where not converted to html
     *
     * @var array<string>
     */
    protected $notConverted = array();

    /**
     * skip conversion to markdown
     *
     * @var bool
     */
    protected $skipConversion = false;

    /* options */

    /**
     * keep html tags which cannot be converted to markdown
     *
     * @var bool
     */
    protected $keepHTML = false;

    /**
     * wrap output, set to 0 to skip wrapping
     *
     * @var int
     */
    protected $bodyWidth = 0;

    /**
     * minimum body width
     *
     * @var int
     */
    protected $minBodyWidth = 25;

    /**
     * position where the link reference will be displayed
     *
     *
     * @var int
     */
    protected $linkPosition;
    const LINK_AFTER_CONTENT = 0;
    const LINK_AFTER_PARAGRAPH = 1;
    const LINK_IN_PARAGRAPH = 2;

    /**
     * stores current buffers
     *
     * @var array<string>
     */
    protected $buffer = array();

    /**
     * stores current buffers
     *
     * @var array<string>
     */
    protected $footnotes = array();

    /**
     * tags with elements which can be handled by markdown
     *
     * @var array<string>
     */
    protected $isMarkdownable = array(
        'p' => array(),
        'ul' => array(),
        'ol' => array(),
        'li' => array(),
        'br' => array(),
        'blockquote' => array(),
        'code' => array(),
        'pre' => array(),
        'a' => array(
            'href' => 'required',
            'title' => 'optional',
        ),
        'strong' => array(),
        'b' => array(),
        'em' => array(),
        'i' => array(),
        'img' => array(
            'src' => 'required',
            'alt' => 'optional',
            'title' => 'optional',
        ),
        'h1' => array(),
        'h2' => array(),
        'h3' => array(),
        'h4' => array(),
        'h5' => array(),
        'h6' => array(),
        'hr' => array(),
    );

    /**
     * html tags to be ignored (contents will be parsed)
     *
     * @var array<string>
     */
    protected $ignore = array(
        'html',
        'body',
    );

    /**
     * html tags to be dropped (contents will not be parsed!)
     *
     * @var array<string>
     */
    protected $drop = array(
        'script',
        'head',
        'style',
        'form',
        'area',
        'object',
        'param',
        'iframe',
    );

    /**
     * html block tags that allow inline & block children
     *
     * @var array<string>
     */
    protected $allowMixedChildren = array(
        'li'
    );

    /**
     * Markdown indents which could be wrapped
     * @note: use strings in regex format
     *
     * @var array<string>
     */
    protected $wrappableIndents = array(
        '\*   ', // ul
        '\d.  ', // ol
        '\d\d. ', // ol
        '> ', // blockquote
        '', // p
    );

    /**
     * list of chars which have to be escaped in normal text
     * @note: use strings in regex format
     *
     * @var array
     *
     * TODO: what's with block chars / sequences at the beginning of a block?
     */
    protected $escapeInText = array(
        '\*\*([^*]+)\*\*' => '\*\*$1\*\*', // strong
        '\*([^*]+)\*' => '\*$1\*', // em
        '__(?! |_)(.+)(?!<_| )__' => '\_\_$1\_\_', // strong
        '_(?! |_)(.+)(?!<_| )_' => '\_$1\_', // em
        '([-*_])([ ]{0,2}\1){2,}' => '\\\\$0', // hr
        '`' => '\`', // code
        '\[(.+)\](\s*\()' => '\[$1\]$2', // links: [text] (url) => [text\] (url)
        '\[(.+)\](\s*)\[(.*)\]' => '\[$1\]$2\[$3\]', // links: [text][id] => [text\][id\]
        '^#(#{0,5}) ' => '\#$1 ', // header
    );

    /**
     * wether last processed node was a block tag or not
     *
     * @var bool
     */
    protected $lastWasBlockTag = false;

    /**
     * name of last closed tag
     *
     * @var string
     */
    protected $lastClosedTag = '';

    /**
     * number of line breaks before next inline output
     */
    protected $lineBreaks = 0;

    /**
     * node stack, e.g. for <a> and <abbr> tags
     *
     * @var array<array>
     */
    protected $stack = array();

    /**
     * current indentation
     *
     * @var string
     */
    protected $indent = '';

    /**
     * constructor, set options, setup parser
     *
     * @param int $linkPosition define the position of links
     * @param int $bodyWidth whether or not to wrap the output to the given width
     *             defaults to false
     * @param bool $keepHTML whether to keep non markdownable HTML or to discard it
     *             defaults to true (HTML will be kept)
     * @return void
     */
    public function __construct($linkPosition = self::LINK_AFTER_CONTENT, $bodyWidth = MDFY_BODYWIDTH, $keepHTML = MDFY_KEEPHTML)
    {
        $this->linkPosition = $linkPosition;
        $this->keepHTML = $keepHTML;

        if ($bodyWidth > $this->minBodyWidth) {
            $this->bodyWidth = intval($bodyWidth);
        } else {
            $this->bodyWidth = false;
        }

        $this->parser = new Parser;
        $this->parser->noTagsInCode = true;

        // we don't have to do this every time
        $search = array();
        $replace = array();
        foreach ($this->escapeInText as $s => $r) {
            array_push($search, '@(?<!\\\)' . $s . '@U');
            array_push($replace, $r);
        }
        $this->escapeInText = array(
            'search' => $search,
            'replace' => $replace
        );
    }

    /**
     * parse a HTML string
     *
     * @param string $html
     * @return string markdown formatted
     */
    public function parseString($html)
    {
        $this->parser->html = $html;
        $this->parse();

        return $this->output;
    }

    /**
     * set the position where the link reference will be displayed
     *
     * @param int $linkPosition
     * @return void
     */
    public function setLinkPosition($linkPosition)
    {
        $this->linkPosition = $linkPosition;
    }

    /**
     * set keep HTML tags which cannot be converted to markdown
     *
     * @param bool $linkPosition
     * @return void
     */
    public function setKeepHTML($keepHTML)
    {
        $this->keepHTML = $keepHTML;
    }

    /**
     * iterate through the nodes and decide what we
     * shall do with the current node
     *
     * @param void
     * @return void
     */
    protected function parse()
    {
        $this->output = '';
        // drop tags
        $this->parser->html = preg_replace('#<(' . implode('|', $this->drop) . ')[^>]*>.*</\\1>#sU', '', $this->parser->html);
        while ($this->parser->nextNode()) {
            switch ($this->parser->nodeType) {
                case 'doctype':
                    break;
                case 'pi':
                case 'comment':
                    if ($this->keepHTML) {
                        $this->flushLinebreaks();
                        $this->out($this->parser->node);
                        $this->setLineBreaks(2);
                    }
                    // else drop
                    break;
                case 'text':
                    $this->handleText();
                    break;
                case 'tag':
                    if (in_array($this->parser->tagName, $this->ignore)) {
                        break;
                    }
                    // If the previous tag was not a block element, we simulate a paragraph tag
                    if ($this->parser->isBlockElement && $this->parser->isNextToInlineContext && !in_array($this->parent(), $this->allowMixedChildren)) {
                        $this->setLineBreaks(2);
                    }
                    if ($this->parser->isStartTag) {
                        $this->flushLinebreaks();
                    }
                    if ($this->skipConversion) {
                        $this->isMarkdownable(); // update notConverted
                        $this->handleTagToText();
                        continue;
                    }

                    // block elements
                    if (!$this->parser->keepWhitespace && $this->parser->isBlockElement) {
                        $this->fixBlockElementSpacing();
                    }

                    // inline elements
                    if (!$this->parser->keepWhitespace && $this->parser->isInlineContext) {
                        $this->fixInlineElementSpacing();
                    }

                    if ($this->isMarkdownable()) {
                        if ($this->parser->isBlockElement && $this->parser->isStartTag && !$this->lastWasBlockTag && !empty($this->output)) {
                            if (!empty($this->buffer)) {
                                $str =& $this->buffer[count($this->buffer) - 1];
                            } else {
                                $str =& $this->output;
                            }
                            if (substr($str, -strlen($this->indent) - 1) != "\n" . $this->indent) {
                                $str .= "\n" . $this->indent;
                            }
                        }
                        $func = 'handleTag_' . $this->parser->tagName;
                        $this->$func();
                        if ($this->linkPosition == self::LINK_AFTER_PARAGRAPH && $this->parser->isBlockElement && !$this->parser->isStartTag && empty($this->parser->openTags)) {
                            $this->flushFootnotes();
                        }
                        if (!$this->parser->isStartTag) {
                            $this->lastClosedTag = $this->parser->tagName;
                        }
                    } else {
                        $this->handleTagToText();
                        $this->lastClosedTag = '';
                    }
                    break;
                default:
                    trigger_error('invalid node type', E_USER_ERROR);
                    break;
            }
            $this->lastWasBlockTag = $this->parser->nodeType == 'tag' && $this->parser->isStartTag && $this->parser->isBlockElement;
        }
        if (!empty($this->buffer)) {
            // trigger_error('buffer was not flushed, this is a bug. please report!', E_USER_WARNING);
            while (!empty($this->buffer)) {
                $this->out($this->unbuffer());
            }
        }
        // cleanup
        $this->output = rtrim(str_replace('&amp;', '&', str_replace('&lt;', '<', str_replace('&gt;', '>', $this->output))));
        // end parsing, flush stacked tags
        $this->flushFootnotes();
        $this->stack = array();
    }

    /**
     * check if current tag can be converted to Markdown
     *
     * @param void
     * @return bool
     */
    protected function isMarkdownable()
    {
        if (!isset($this->isMarkdownable[$this->parser->tagName])) {
            // simply not markdownable

            return false;
        }
        if ($this->parser->isStartTag) {
            $return = true;
            if ($this->keepHTML) {
                $diff = array_diff(array_keys($this->parser->tagAttributes), array_keys($this->isMarkdownable[$this->parser->tagName]));
                if (!empty($diff)) {
                    // non markdownable attributes given
                    $return = false;
                }
            }
            if ($return) {
                foreach ($this->isMarkdownable[$this->parser->tagName] as $attr => $type) {
                    if ($type == 'required' && !isset($this->parser->tagAttributes[$attr])) {
                        // required markdown attribute not given
                        $return = false;
                        break;
                    }
                }
            }
            if (!$return) {
                array_push($this->notConverted, $this->parser->tagName . '::' . implode('/', $this->parser->openTags));
            }

            return $return;
        } else {
            if (!empty($this->notConverted) && end($this->notConverted) === $this->parser->tagName . '::' . implode('/', $this->parser->openTags)) {
                array_pop($this->notConverted);

                return false;
            }

            return true;
        }
    }

    /**
     * output footnotes
     *
     * @param void
     * @return void
     */
    protected function flushFootnotes()
    {
        $out = false;
        foreach ($this->footnotes as $k => $tag) {
            if (!isset($tag['unstacked'])) {
                if (!$out) {
                    $out = true;
                    $this->out("\n\n", true);
                } else {
                    $this->out("\n", true);
                }
                $this->out(' [' . $tag['linkID'] . ']: ' . $this->getLinkReference($tag), true);
                $tag['unstacked'] = true;
                $this->footnotes[$k] = $tag;
            }
        }
    }

    /**
     * return formated link reference
     *
     * @param array $tag
     * @return string link reference
     */
    protected function getLinkReference($tag)
    {
        return $tag['href'] . (isset($tag['title']) ? ' "' . $tag['title'] . '"' : '');
    }

    /**
     * flush enqued linebreaks
     *
     * @param void
     * @return void
     */
    protected function flushLinebreaks()
    {
        if ($this->lineBreaks && !empty($this->output)) {
            $this->out(str_repeat("\n" . $this->indent, $this->lineBreaks), true);
        }
        $this->lineBreaks = 0;
    }

    /**
     * handle non Markdownable tags
     *
     * @param void
     * @return void
     */
    protected function handleTagToText()
    {
        if (!$this->keepHTML) {
            if (!$this->parser->isStartTag && $this->parser->isBlockElement) {
                $this->setLineBreaks(2);
            }
        } else {
            // dont convert to markdown inside this tag
            /** TODO: markdown extra **/
            if (!$this->parser->isEmptyTag) {
                if ($this->parser->isStartTag) {
                    if (!$this->skipConversion) {
                        $this->skipConversion = $this->parser->tagName . '::' . implode('/', $this->parser->openTags);
                    }
                } else {
                    if ($this->skipConversion == $this->parser->tagName . '::' . implode('/', $this->parser->openTags)) {
                        $this->skipConversion = false;
                    }
                }
            }

            if ($this->parser->isBlockElement) {
                if ($this->parser->isStartTag) {
                    // looks like ins or del are block elements now
                    if (in_array($this->parent(), array('ins', 'del'))) {
                        $this->out("\n", true);
                        $this->indent('  ');
                    }
                    // don't indent inside <pre> tags
                    if ($this->parser->tagName == 'pre') {
                        $this->out($this->parser->node);
                        static $indent;
                        $indent = $this->indent;
                        $this->indent = '';
                    } else {
                        $this->out($this->parser->node . "\n" . $this->indent);
                        if (!$this->parser->isEmptyTag) {
                            $this->indent('  ');
                        } else {
                            $this->setLineBreaks(1);
                        }
                        $this->parser->html = ltrim($this->parser->html);
                    }
                } else {
                    if (!$this->parser->keepWhitespace) {
                        $this->output = rtrim($this->output);
                    }
                    if ($this->parser->tagName != 'pre') {
                        $this->indent('  ');
                        $this->out("\n" . $this->indent . $this->parser->node);
                    } else {
                        // reset indentation
                        $this->out($this->parser->node);
                        static $indent;
                        $this->indent = $indent;
                    }

                    if (in_array($this->parent(), array('ins', 'del'))) {
                        // ins or del was block element
                        $this->out("\n");
                        $this->indent('  ');
                    }
                    if ($this->parser->tagName == 'li') {
                        $this->setLineBreaks(1);
                    } else {
                        $this->setLineBreaks(2);
                    }
                }
            } else {
                $this->out($this->parser->node);
            }
            if (in_array($this->parser->tagName, array('code', 'pre'))) {
                if ($this->parser->isStartTag) {
                    $this->buffer();
                } else {
                    // add stuff so cleanup just reverses this
                    $this->out(str_replace('&lt;', '&amp;lt;', str_replace('&gt;', '&amp;gt;', $this->unbuffer())));
                }
            }
        }
    }

    /**
     * handle plain text
     *
     * @param void
     * @return void
     */
    protected function handleText()
    {
        if ($this->hasParent('pre') && strpos($this->parser->node, "\n") !== false) {
            $this->parser->node = str_replace("\n", "\n" . $this->indent, $this->parser->node);
        }
        if (!$this->hasParent('code') && !$this->hasParent('pre')) {
            // entity decode
            $this->parser->node = $this->decode($this->parser->node);
            if (!$this->skipConversion) {
                // escape some chars in normal Text
                $this->parser->node = preg_replace($this->escapeInText['search'], $this->escapeInText['replace'], $this->parser->node);
            }
        } else {
            $this->parser->node = str_replace(array('&quot;', '&apos'), array('"', '\''), $this->parser->node);
        }
        $this->out($this->parser->node);
        $this->lastClosedTag = '';
    }

    /**
     * handle <em> and <i> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_em()
    {
        $this->out('_', true);
    }

    protected function handleTag_i()
    {
        $this->handleTag_em();
    }

    /**
     * handle <strong> and <b> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_strong()
    {
        $this->out('**', true);
    }

    protected function handleTag_b()
    {
        $this->handleTag_strong();
    }

    /**
     * handle <h1> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_h1()
    {
        $this->handleHeader(1);
    }

    /**
     * handle <h2> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_h2()
    {
        $this->handleHeader(2);
    }

    /**
     * handle <h3> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_h3()
    {
        $this->handleHeader(3);
    }

    /**
     * handle <h4> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_h4()
    {
        $this->handleHeader(4);
    }

    /**
     * handle <h5> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_h5()
    {
        $this->handleHeader(5);
    }

    /**
     * handle <h6> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_h6()
    {
        $this->handleHeader(6);
    }

    /**
     * handle header tags (<h1> - <h6>)
     *
     * @param int $level 1-6
     * @return void
     */
    protected function handleHeader($level)
    {
        if ($this->parser->isStartTag) {
            $this->out(str_repeat('#', $level) . ' ', true);
        } else {
            $this->setLineBreaks(2);
        }
    }

    /**
     * handle <p> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_p()
    {
        if (!$this->parser->isStartTag) {
            $this->setLineBreaks(2);
        }
    }

    /**
     * handle <a> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_a()
    {
        if ($this->parser->isStartTag) {
            $this->buffer();
            $this->handleTag_a_parser();
            $this->stack();
        } else {
            $tag = $this->unstack();
            $buffer = $this->unbuffer();
            $this->handleTag_a_converter($tag, $buffer);
            $this->out($this->handleTag_a_converter($tag, $buffer), true);
        }
    }

    /**
     * handle <a> tags parsing
     *
     * @param void
     * @return void
     */
    protected function handleTag_a_parser()
    {
        if (isset($this->parser->tagAttributes['title'])) {
            $this->parser->tagAttributes['title'] = $this->decode($this->parser->tagAttributes['title']);
        } else {
            $this->parser->tagAttributes['title'] = null;
        }
        $this->parser->tagAttributes['href'] = $this->decode(trim($this->parser->tagAttributes['href']));
    }

    /**
     * handle <a> tags conversion
     *
     * @param array $tag
     * @param string $buffer
     * @return string The markdownified link
     */
    protected function handleTag_a_converter($tag, $buffer)
    {
        if (empty($tag['href']) && empty($tag['title'])) {
            // empty links... testcase mania, who would possibly do anything like that?!
            return '[' . $buffer . ']()';
        }

        if ($buffer == $tag['href'] && empty($tag['title'])) {
            // <http://example.com>
            return '<' . $buffer . '>';
        }

        $bufferDecoded = $this->decode(trim($buffer));
        if (substr($tag['href'], 0, 7) == 'mailto:' && 'mailto:' . $bufferDecoded == $tag['href']) {
            if (is_null($tag['title'])) {
                // <mail@example.com>
                return '<' . $bufferDecoded . '>';
            }
            // [mail@example.com][1]
            // ...
            //  [1]: mailto:mail@example.com Title
            $tag['href'] = 'mailto:' . $bufferDecoded;
        }

        if ($this->linkPosition == self::LINK_IN_PARAGRAPH) {
            return '[' . $buffer . '](' . $this->getLinkReference($tag) . ')';
        }

        // [This link][id]
        foreach ($this->footnotes as $tag2) {
            if ($tag2['href'] == $tag['href'] && $tag2['title'] === $tag['title']) {
                $tag['linkID'] = $tag2['linkID'];
                break;
            }
        }
        if (!isset($tag['linkID'])) {
            $tag['linkID'] = count($this->footnotes) + 1;
            array_push($this->footnotes, $tag);
        }

        return '[' . $buffer . '][' . $tag['linkID'] . ']';
    }

    /**
     * handle <img /> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_img()
    {
        if (!$this->parser->isStartTag) {
            return; // just to be sure this is really an empty tag...
        }

        if (isset($this->parser->tagAttributes['title'])) {
            $this->parser->tagAttributes['title'] = $this->decode($this->parser->tagAttributes['title']);
        } else {
            $this->parser->tagAttributes['title'] = null;
        }
        if (isset($this->parser->tagAttributes['alt'])) {
            $this->parser->tagAttributes['alt'] = $this->decode($this->parser->tagAttributes['alt']);
        } else {
            $this->parser->tagAttributes['alt'] = null;
        }

        if (empty($this->parser->tagAttributes['src'])) {
            // support for "empty" images... dunno if this is really needed
            // but there are some test cases which do that...
            if (!empty($this->parser->tagAttributes['title'])) {
                $this->parser->tagAttributes['title'] = ' ' . $this->parser->tagAttributes['title'] . ' ';
            }
            $this->out('![' . $this->parser->tagAttributes['alt'] . '](' . $this->parser->tagAttributes['title'] . ')', true);

            return;
        } else {
            $this->parser->tagAttributes['src'] = $this->decode($this->parser->tagAttributes['src']);
        }

        $out = '![' . $this->parser->tagAttributes['alt'] . ']';
        if ($this->linkPosition == self::LINK_IN_PARAGRAPH) {
            $out .= '(' . $this->parser->tagAttributes['src'];
            if ($this->parser->tagAttributes['title']) {
                $out .= ' "' . $this->parser->tagAttributes['title'] . '"';
            }
            $out .= ')';
            $this->out($out, true);
            return;
        }

        // ![This image][id]
        $link_id = false;
        if (!empty($this->footnotes)) {
            foreach ($this->footnotes as $tag) {
                if ($tag['href'] == $this->parser->tagAttributes['src']
                    && $tag['title'] === $this->parser->tagAttributes['title']
                ) {
                    $link_id = $tag['linkID'];
                    break;
                }
            }
        }
        if (!$link_id) {
            $link_id = count($this->footnotes) + 1;
            $tag = array(
                'href' => $this->parser->tagAttributes['src'],
                'linkID' => $link_id,
                'title' => $this->parser->tagAttributes['title']
            );
            array_push($this->footnotes, $tag);
        }
        $out .= '[' . $link_id . ']';

        $this->out($out, true);
    }

    /**
     * handle <code> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_code()
    {
        if ($this->hasParent('pre')) {
            // ignore code blocks inside <pre>

            return;
        }
        if ($this->parser->isStartTag) {
            $this->buffer();
        } else {
            $buffer = $this->unbuffer();
            // use as many backticks as needed
            preg_match_all('#`+#', $buffer, $matches);
            if (!empty($matches[0])) {
                rsort($matches[0]);

                $ticks = '`';
                while (true) {
                    if (!in_array($ticks, $matches[0])) {
                        break;
                    }
                    $ticks .= '`';
                }
            } else {
                $ticks = '`';
            }
            if ($buffer[0] == '`' || substr($buffer, -1) == '`') {
                $buffer = ' ' . $buffer . ' ';
            }
            $this->out($ticks . $buffer . $ticks, true);
        }
    }

    /**
     * handle <pre> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_pre()
    {
        if ($this->keepHTML && $this->parser->isStartTag) {
            // check if a simple <code> follows
            if (!preg_match('#^\s*<code\s*>#Us', $this->parser->html)) {
                // this is no standard markdown code block
                $this->handleTagToText();

                return;
            }
        }
        $this->indent('    ');
        if (!$this->parser->isStartTag) {
            $this->setLineBreaks(2);
        } else {
            $this->parser->html = ltrim($this->parser->html);
        }
    }

    /**
     * handle <blockquote> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_blockquote()
    {
        $this->indent('> ');
    }

    /**
     * handle <ul> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_ul()
    {
        if ($this->parser->isStartTag) {
            $this->stack();
            if (!$this->keepHTML && $this->lastClosedTag == $this->parser->tagName) {
                $this->out("\n" . $this->indent . '<!-- -->' . "\n" . $this->indent . "\n" . $this->indent);
            }
        } else {
            $this->unstack();
            if ($this->parent() != 'li' || preg_match('#^\s*(</li\s*>\s*<li\s*>\s*)?<(p|blockquote)\s*>#sU', $this->parser->html)) {
                // dont make Markdown add unneeded paragraphs
                $this->setLineBreaks(2);
            }
        }
    }

    /**
     * handle <ul> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_ol()
    {
        // same as above
        $this->parser->tagAttributes['num'] = 0;
        $this->handleTag_ul();
    }

    /**
     * handle <li> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_li()
    {
        if ($this->parent() == 'ol') {
            $parent =& $this->getStacked('ol');
            if ($this->parser->isStartTag) {
                $parent['num']++;
                $this->out(str_repeat(' ', 3 - strlen($parent['num'])) . $parent['num'] . '. ', true);
            }
        } else {
            if ($this->parser->isStartTag) {
                $this->out('  * ', true);
            }
        }
        $this->indent('    ', false);
        if (!$this->parser->isStartTag) {
            $this->setLineBreaks(1);
        }
    }

    /**
     * handle <hr /> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_hr()
    {
        if (!$this->parser->isStartTag) {
            return; // just to be sure this really is an empty tag
        }
        $this->out('* * *', true);
        $this->setLineBreaks(2);
    }

    /**
     * handle <br /> tags
     *
     * @param void
     * @return void
     */
    protected function handleTag_br()
    {
        $this->out("  \n" . $this->indent, true);
        $this->parser->html = ltrim($this->parser->html);
    }

    /**
     * add current node to the stack
     * this only stores the attributes
     *
     * @param void
     * @return void
     */
    protected function stack()
    {
        if (!isset($this->stack[$this->parser->tagName])) {
            $this->stack[$this->parser->tagName] = array();
        }
        array_push($this->stack[$this->parser->tagName], $this->parser->tagAttributes);
    }

    /**
     * remove current tag from stack
     *
     * @param void
     * @return array
     */
    protected function unstack()
    {
        if (!isset($this->stack[$this->parser->tagName]) || !is_array($this->stack[$this->parser->tagName])) {
            trigger_error('Trying to unstack from empty stack. This must not happen.', E_USER_ERROR);
        }

        return array_pop($this->stack[$this->parser->tagName]);
    }

    /**
     * get last stacked element of type $tagName
     *
     * @param string $tagName
     * @return array
     */
    protected function &getStacked($tagName)
    {
        // no end() so it can be referenced
        return $this->stack[$tagName][count($this->stack[$tagName]) - 1];
    }

    /**
     * set number of line breaks before next start tag
     *
     * @param int $number
     * @return void
     */
    protected function setLineBreaks($number)
    {
        if ($this->lineBreaks < $number) {
            $this->lineBreaks = $number;
        }
    }

    /**
     * buffer next parser output until unbuffer() is called
     *
     * @param void
     * @return void
     */
    protected function buffer()
    {
        array_push($this->buffer, '');
    }

    /**
     * end current buffer and return buffered output
     *
     * @param void
     * @return string
     */
    protected function unbuffer()
    {
        return array_pop($this->buffer);
    }

    /**
     * append string to the correct var, either
     * directly to $this->output or to the current
     * buffers
     *
     * @param string $put
     * @param boolean $nowrap
     * @return void
     */
    protected function out($put, $nowrap = false)
    {
        if (empty($put)) {
            return;
        }
        if (!empty($this->buffer)) {
            $this->buffer[count($this->buffer) - 1] .= $put;
        } else {
            if ($this->bodyWidth && !$this->parser->keepWhitespace) { // wrap lines
                // get last line
                $pos = strrpos($this->output, "\n");
                if ($pos === false) {
                    $line = $this->output;
                } else {
                    $line = substr($this->output, $pos);
                }

                if ($nowrap) {
                    if ($put[0] != "\n" && $this->strlen($line) + $this->strlen($put) > $this->bodyWidth) {
                        $this->output .= "\n" . $this->indent . $put;
                    } else {
                        $this->output .= $put;
                    }

                    return;
                } else {
                    $put .= "\n"; // make sure we get all lines in the while below
                    $lineLen = $this->strlen($line);
                    while ($pos = strpos($put, "\n")) {
                        $putLine = substr($put, 0, $pos + 1);
                        $put = substr($put, $pos + 1);
                        $putLen = $this->strlen($putLine);
                        if ($lineLen + $putLen < $this->bodyWidth) {
                            $this->output .= $putLine;
                            $lineLen = $putLen;
                        } else {
                            $split = preg_split('#^(.{0,' . ($this->bodyWidth - $lineLen) . '})\b#', $putLine, 2, PREG_SPLIT_OFFSET_CAPTURE | PREG_SPLIT_DELIM_CAPTURE);
                            $this->output .= rtrim($split[1][0]) . "\n" . $this->indent . $this->wordwrap(ltrim($split[2][0]), $this->bodyWidth, "\n" . $this->indent, false);
                        }
                    }
                    $this->output = substr($this->output, 0, -1);

                    return;
                }
            } else {
                $this->output .= $put;
            }
        }
    }

    /**
     * indent next output (start tag) or unindent (end tag)
     *
     * @param string $str indentation
     * @param bool $output add indendation to output
     * @return void
     */
    protected function indent($str, $output = true)
    {
        if ($this->parser->isStartTag) {
            $this->indent .= $str;
            if ($output) {
                $this->out($str, true);
            }
        } else {
            $this->indent = substr($this->indent, 0, -strlen($str));
        }
    }

    /**
     * decode email addresses
     *
     * @author derernst@gmx.ch <http://www.php.net/manual/en/function.html-entity-decode.php#68536>
     * @author Milian Wolff <http://milianw.de>
     */
    protected function decode($text, $quote_style = ENT_QUOTES)
    {
        return htmlspecialchars_decode($text, $quote_style);
    }

    /**
     * callback for decode() which converts a hexadecimal entity to UTF-8
     *
     * @param array $matches
     * @return string UTF-8 encoded
     */
    protected function _decode_hex($matches)
    {
        return $this->unichr(hexdec($matches[1]));
    }

    /**
     * callback for decode() which converts a numerical entity to UTF-8
     *
     * @param array $matches
     * @return string UTF-8 encoded
     */
    protected function _decode_numeric($matches)
    {
        return $this->unichr($matches[1]);
    }

    /**
     * UTF-8 chr() which supports numeric entities
     *
     * @author grey - greywyvern - com <http://www.php.net/manual/en/function.chr.php#55978>
     * @param array $matches
     * @return string UTF-8 encoded
     */
    protected function unichr($dec)
    {
        if ($dec < 128) {
            $utf = chr($dec);
        } elseif ($dec < 2048) {
            $utf = chr(192 + (($dec - ($dec % 64)) / 64));
            $utf .= chr(128 + ($dec % 64));
        } else {
            $utf = chr(224 + (($dec - ($dec % 4096)) / 4096));
            $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
            $utf .= chr(128 + ($dec % 64));
        }

        return $utf;
    }

    /**
     * UTF-8 strlen()
     *
     * @param string $str
     * @return int
     *
     * @author dtorop 932 at hotmail dot com <http://www.php.net/manual/en/function.strlen.php#37975>
     * @author Milian Wolff <http://milianw.de>
     */
    protected function strlen($str)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, 'UTF-8');
        } else {
            return preg_match_all('/[\x00-\x7F\xC0-\xFD]/', $str, $var_empty);
        }
    }

    /**
     * wordwrap for utf8 encoded strings
     *
     * @param string $str
     * @param integer $len
     * @param string $what
     * @return string
     */
    protected function wordwrap($str, $width, $break, $cut = false)
    {
        if (!$cut) {
            $regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){1,' . $width . '}\b#';
        } else {
            $regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){' . $width . '}#';
        }
        $return = '';
        while (preg_match($regexp, $str, $matches)) {
            $string = $matches[0];
            $str = ltrim(substr($str, strlen($string)));
            if (!$cut && isset($str[0]) && in_array($str[0], array('.', '!', ';', ':', '?', ','))) {
                $string .= $str[0];
                $str = ltrim(substr($str, 1));
            }
            $return .= $string . $break;
        }

        return $return . ltrim($str);
    }

    /**
     * check if current node has a $tagName as parent (somewhere, not only the direct parent)
     *
     * @param string $tagName
     * @return bool
     */
    protected function hasParent($tagName)
    {
        return in_array($tagName, $this->parser->openTags);
    }

    /**
     * get tagName of direct parent tag
     *
     * @param void
     * @return string $tagName
     */
    protected function parent()
    {
        return end($this->parser->openTags);
    }

    /**
     * Trims whitespace in block-level elements, on the left side.
     */
    protected function fixBlockElementSpacing()
    {
        if ($this->parser->isStartTag) {
            $this->parser->html = ltrim($this->parser->html);
        }
    }

    /**
     * Moves leading/trailing whitespace from inline elements outside of the
     * element. This is to fix cases like `<strong> Text</strong>`, which if
     * converted to `** strong**` would be incorrect Markdown.
     *
     * Examples:
     *
     *   * leading: `<strong> Text</strong>` becomes ` <strong>Text</strong>`
     *   * trailing: `<strong>Text </strong>` becomes `<strong>Text</strong> `
     */
    protected function fixInlineElementSpacing()
    {
        if ($this->parser->isStartTag) {
            // move spaces after the start element to before the element
            if (preg_match('~^(\s+)~', $this->parser->html, $matches)) {
                $this->out($matches[1]);
                $this->parser->html = ltrim($this->parser->html, " \t\0\x0B");
            }
        } else {
            if (!empty($this->buffer)) {
                $str =& $this->buffer[count($this->buffer) - 1];
            } else {
                $str =& $this->output;
            }

            // move spaces before the end element to after the element
            if (preg_match('~(\s+)$~', $str, $matches)) {
                $str = rtrim($this->output, " \t\0\x0B");
                $this->parser->html = $matches[1] . $this->parser->html;
            }
        }
    }
}

class Parser
{
    public static $skipWhitespace = true;
    public static $a_ord;
    public static $z_ord;
    public static $special_ords;

    /**
     * tags which are always empty (<br /> etc.)
     *
     * @var array<string>
     */
    public $emptyTags = array(
        'br',
        'hr',
        'input',
        'img',
        'area',
        'link',
        'meta',
        'param',
    );

    /**
     * tags with preformatted text
     * whitespaces wont be touched in them
     *
     * @var array<string>
     */
    public $preformattedTags = array(
        'script',
        'style',
        'pre',
        'code',
    );

    /**
     * supress HTML tags inside preformatted tags (see above)
     *
     * @var bool
     */
    public $noTagsInCode = false;

    /**
     * html to be parsed
     *
     * @var string
     */
    public $html = '';

    /**
     * node type:
     *
     * - tag (see isStartTag)
     * - text (includes cdata)
     * - comment
     * - doctype
     * - pi (processing instruction)
     *
     * @var string
     */
    public $nodeType = '';

    /**
     * current node content, i.e. either a
     * simple string (text node), or something like
     * <tag attrib="value"...>
     *
     * @var string
     */
    public $node = '';

    /**
     * wether current node is an opening tag (<a>) or not (</a>)
     * set to NULL if current node is not a tag
     * NOTE: empty tags (<br />) set this to true as well!
     *
     * @var bool | null
     */
    public $isStartTag = null;

    /**
     * wether current node is an empty tag (<br />) or not (<a></a>)
     *
     * @var bool | null
     */
    public $isEmptyTag = null;

    /**
     * tag name
     *
     * @var string | null
     */
    public $tagName = '';

    /**
     * attributes of current tag
     *
     * @var array (attribName=>value) | null
     */
    public $tagAttributes = null;

    /**
     * whether or not the actual context is a inline context
     *
     * @var bool | null
     */
    public $isInlineContext = null;

    /**
     * whether the current tag is a block element
     *
     * @var bool | null
     */
    public $isBlockElement = null;

    /**
     * whether the previous tag (browser) is a block element
     *
     * @var bool | null
     */
    public $isNextToInlineContext = null;

    /**
     * keep whitespace
     *
     * @var int
     */
    public $keepWhitespace = 0;

    /**
     * list of open tags
     * count this to get current depth
     *
     * @var array
     */
    public $openTags = array();

    /**
     * list of block elements
     *
     * @var array
     * TODO: what shall we do with <del> and <ins> ?!
     */
    public $blockElements = array(
        // tag name => <bool> is block
        // block elements
        'address' => true,
        'blockquote' => true,
        'center' => true,
        'del' => true,
        'dir' => true,
        'div' => true,
        'dl' => true,
        'fieldset' => true,
        'form' => true,
        'h1' => true,
        'h2' => true,
        'h3' => true,
        'h4' => true,
        'h5' => true,
        'h6' => true,
        'hr' => true,
        'ins' => true,
        'isindex' => true,
        'menu' => true,
        'noframes' => true,
        'noscript' => true,
        'ol' => true,
        'p' => true,
        'pre' => true,
        'table' => true,
        'ul' => true,
        // set table elements and list items to block as well
        'thead' => true,
        'tbody' => true,
        'tfoot' => true,
        'td' => true,
        'tr' => true,
        'th' => true,
        'li' => true,
        'dd' => true,
        'dt' => true,
        // header items and html / body as well
        'html' => true,
        'body' => true,
        'head' => true,
        'meta' => true,
        'link' => true,
        'style' => true,
        'title' => true,
        // unfancy media tags, when indented should be rendered as block
        'map' => true,
        'object' => true,
        'param' => true,
        'embed' => true,
        'area' => true,
        // inline elements
        'a' => false,
        'abbr' => false,
        'acronym' => false,
        'applet' => false,
        'b' => false,
        'basefont' => false,
        'bdo' => false,
        'big' => false,
        'br' => false,
        'button' => false,
        'cite' => false,
        'code' => false,
        'del' => false,
        'dfn' => false,
        'em' => false,
        'font' => false,
        'i' => false,
        'img' => false,
        'ins' => false,
        'input' => false,
        'iframe' => false,
        'kbd' => false,
        'label' => false,
        'q' => false,
        'samp' => false,
        'script' => false,
        'select' => false,
        'small' => false,
        'span' => false,
        'strong' => false,
        'sub' => false,
        'sup' => false,
        'textarea' => false,
        'tt' => false,
        'var' => false,
    );

    /**
     * get next node, set $this->html prior!
     *
     * @param void
     * @return bool
     */
    public function nextNode()
    {
        if (empty($this->html)) {
            // we are done with parsing the html string

            return false;
        }

        if ($this->isStartTag && !$this->isEmptyTag) {
            array_push($this->openTags, $this->tagName);
            if (in_array($this->tagName, $this->preformattedTags)) {
                // dont truncate whitespaces for <code> or <pre> contents
                $this->keepWhitespace++;
            }
        }

        if ($this->html[0] == '<') {
            $token = substr($this->html, 0, 9);
            if (substr($token, 0, 2) == '<?') {
                // xml prolog or other pi's
                /** TODO **/
                // trigger_error('this might need some work', E_USER_NOTICE);
                $pos = strpos($this->html, '>');
                $this->setNode('pi', $pos + 1);

                return true;
            }
            if (substr($token, 0, 4) == '<!--') {
                // comment
                $pos = strpos($this->html, '-->');
                if ($pos === false) {
                    // could not find a closing -->, use next gt instead
                    // this is firefox' behaviour
                    $pos = strpos($this->html, '>') + 1;
                } else {
                    $pos += 3;
                }
                $this->setNode('comment', $pos);

                static::$skipWhitespace = true;

                return true;
            }
            if ($token == '<!DOCTYPE') {
                // doctype
                $this->setNode('doctype', strpos($this->html, '>') + 1);

                static::$skipWhitespace = true;

                return true;
            }
            if ($token == '<![CDATA[') {
                // cdata, use text node

                // remove leading <![CDATA[
                $this->html = substr($this->html, 9);

                $this->setNode('text', strpos($this->html, ']]>') + 3);

                // remove trailing ]]> and trim
                $this->node = substr($this->node, 0, -3);
                $this->handleWhitespaces();

                static::$skipWhitespace = true;

                return true;
            }
            if ($this->parseTag()) {
                // seems to be a tag
                // handle whitespaces
                if ($this->isBlockElement) {
                    static::$skipWhitespace = true;
                } else {
                    static::$skipWhitespace = false;
                }

                return true;
            }
        }
        if ($this->keepWhitespace) {
            static::$skipWhitespace = false;
        }
        // when we get here it seems to be a text node
        $pos = strpos($this->html, '<');
        if ($pos === false) {
            $pos = strlen($this->html);
        }
        $this->setNode('text', $pos);
        $this->handleWhitespaces();
        if (static::$skipWhitespace && $this->node == ' ') {
            return $this->nextNode();
        }
        $this->isInlineContext = true;
        static::$skipWhitespace = false;

        return true;
    }

    /**
     * parse tag, set tag name and attributes, see if it's a closing tag and so forth...
     *
     * @param void
     * @return bool
     */
    protected function parseTag()
    {
        if (!isset(static::$a_ord)) {
            static::$a_ord = ord('a');
            static::$z_ord = ord('z');
            static::$special_ords = array(
                ord(':'), // for xml:lang
                ord('-'), // for http-equiv
            );
        }

        $tagName = '';

        $pos = 1;
        $isStartTag = $this->html[$pos] != '/';
        if (!$isStartTag) {
            $pos++;
        }
        // get tagName
        while (isset($this->html[$pos])) {
            $pos_ord = ord(strtolower($this->html[$pos]));
            if (($pos_ord >= static::$a_ord && $pos_ord <= static::$z_ord) || (!empty($tagName) && is_numeric($this->html[$pos]))) {
                $tagName .= $this->html[$pos];
                $pos++;
            } else {
                $pos--;
                break;
            }
        }

        $tagName = strtolower($tagName);
        if (empty($tagName) || !isset($this->blockElements[$tagName])) {
            // something went wrong => invalid tag
            $this->invalidTag();

            return false;
        }
        if ($this->noTagsInCode && end($this->openTags) == 'code' && !($tagName == 'code' && !$isStartTag)) {
            // we supress all HTML tags inside code tags
            $this->invalidTag();

            return false;
        }

        // get tag attributes
        /** TODO: in html 4 attributes do not need to be quoted **/
        $isEmptyTag = false;
        $attributes = array();
        $currAttrib = '';
        while (isset($this->html[$pos + 1])) {
            $pos++;
            // close tag
            if ($this->html[$pos] == '>' || $this->html[$pos] . $this->html[$pos + 1] == '/>') {
                if ($this->html[$pos] == '/') {
                    $isEmptyTag = true;
                    $pos++;
                }
                break;
            }

            $pos_ord = ord(strtolower($this->html[$pos]));
            if (($pos_ord >= static::$a_ord && $pos_ord <= static::$z_ord) || in_array($pos_ord, static::$special_ords)) {
                // attribute name
                $currAttrib .= $this->html[$pos];
            } elseif (in_array($this->html[$pos], array(' ', "\t", "\n"))) {
                // drop whitespace
            } elseif (in_array($this->html[$pos] . $this->html[$pos + 1], array('="', "='"))) {
                // get attribute value
                $pos++;
                $await = $this->html[$pos]; // single or double quote
                $pos++;
                $value = '';
                while (isset($this->html[$pos]) && $this->html[$pos] != $await) {
                    $value .= $this->html[$pos];
                    $pos++;
                }
                $attributes[$currAttrib] = $value;
                $currAttrib = '';
            } else {
                $this->invalidTag();

                return false;
            }
        }
        if ($this->html[$pos] != '>') {
            $this->invalidTag();

            return false;
        }

        if (!empty($currAttrib)) {
            // html 4 allows something like <option selected> instead of <option selected="selected">
            $attributes[$currAttrib] = $currAttrib;
        }
        if (!$isStartTag) {
            if (!empty($attributes) || $tagName != end($this->openTags)) {
                // end tags must not contain any attributes
                // or maybe we did not expect a different tag to be closed
                $this->invalidTag();

                return false;
            }
            array_pop($this->openTags);
            if (in_array($tagName, $this->preformattedTags)) {
                $this->keepWhitespace--;
            }
        }
        $pos++;
        $this->node = substr($this->html, 0, $pos);
        $this->html = substr($this->html, $pos);
        $this->tagName = $tagName;
        $this->tagAttributes = $attributes;
        $this->isStartTag = $isStartTag;
        $this->isEmptyTag = $isEmptyTag || in_array($tagName, $this->emptyTags);
        if ($this->isEmptyTag) {
            // might be not well formed
            $this->node = preg_replace('# */? *>$#', ' />', $this->node);
        }
        $this->nodeType = 'tag';
        $this->isBlockElement = $this->blockElements[$tagName];
        $this->isNextToInlineContext = $isStartTag && $this->isInlineContext;
        $this->isInlineContext = !$this->isBlockElement;
        return true;
    }

    /**
     * handle invalid tags
     *
     * @param void
     * @return void
     */
    protected function invalidTag()
    {
        $this->html = substr_replace($this->html, '&lt;', 0, 1);
    }

    /**
     * update all vars and make $this->html shorter
     *
     * @param string $type see description for $this->nodeType
     * @param int $pos to which position shall we cut?
     * @return void
     */
    protected function setNode($type, $pos)
    {
        if ($this->nodeType == 'tag') {
            // set tag specific vars to null
            // $type == tag should not be called here
            // see this::parseTag() for more
            $this->tagName = null;
            $this->tagAttributes = null;
            $this->isStartTag = null;
            $this->isEmptyTag = null;
            $this->isBlockElement = null;

        }
        $this->nodeType = $type;
        $this->node = substr($this->html, 0, $pos);
        $this->html = substr($this->html, $pos);
    }

    /**
     * check if $this->html begins with $str
     *
     * @param string $str
     * @return bool
     */
    protected function match($str)
    {
        return substr($this->html, 0, strlen($str)) == $str;
    }

    /**
     * truncate whitespaces
     *
     * @param void
     * @return void
     */
    protected function handleWhitespaces()
    {
        if ($this->keepWhitespace) {
            // <pre> or <code> before...

            return;
        }
        // truncate multiple whitespaces to a single one
        $this->node = preg_replace('#\s+#s', ' ', $this->node);
    }

    /**
     * normalize self::node
     *
     * @param void
     * @return void
     */
    protected function normalizeNode()
    {
        $this->node = '<';
        if (!$this->isStartTag) {
            $this->node .= '/' . $this->tagName . '>';

            return;
        }
        $this->node .= $this->tagName;
        foreach ($this->tagAttributes as $name => $value) {
            $this->node .= ' ' . $name . '="' . str_replace('"', '&quot;', $value) . '"';
        }
        if ($this->isEmptyTag) {
            $this->node .= ' /';
        }
        $this->node .= '>';
    }
}