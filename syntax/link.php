<?php
/**
 * Allows definition of autolink which is then shown using wikilink tag throughout the pages:
 * Example:
 * On the page wanted to be autolinked. {{autolink>anchors|separated by|}}
 * On the pages where autolink is wanted to insert the whole page around <autolink> and </autolink>
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if (!defined('DOKU_INC')) die('');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_autolink2_link extends DokuWiki_Syntax_Plugin {
    private $pattern = null;

    /** @var helper_plugin_autolink2 $helper */
    private $helper = null;

    /**
     * What kind of syntax are we?
     */
    function getType(){return 'substition';}
    function getPType() {return 'normal';}
    function getSort() {return 999;}


    function connectTo($mode) {
        if (is_null($this->pattern)) {
            if (empty($this->helper))
                $this->helper = plugin_load('helper', 'autolink2', false);
            $anchors = $this->helper->getAnchors();
            if (empty($anchors)) {
                $this->pattern = '';
            } else {
                $this->pattern = '(?:'.implode('|', array_map('preg_quote', array_keys($anchors), array_fill(0, count($anchors), '/'))).')';
            }
        }
        if (!empty($this->pattern))
            $this->Lexer->addSpecialPattern($this->pattern, $mode, 'plugin_autolink2_link');
    }


    function handle($match, $state, $pos, Doku_Handler &$handler){
        if (empty($this->helper))
            $this->helper = plugin_load('helper', 'autolink2', false);

        if ($this->helper->autolinkEnabled()) {
            $anchors = $this->helper->getAnchors();
            return array($anchors[$match], $match);
        } else {
            $handler->_addCall('cdata', array($match), $pos);
            return false;
        }
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer &$renderer, $data) {
        list($id, $name) = $data;

        if (page_exists($id)) {
            $renderer->internallink(':'.$id, $name);
        } else {
            $renderer->cdata($name);
        }
    }
}
