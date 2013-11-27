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
class syntax_plugin_autolink2_show extends DokuWiki_Syntax_Plugin {
 
    /**
     * What kind of syntax are we?
     */
    function getType(){return 'baseonly';}
    function getPType() {return 'stack';}
    function getSort() {return 999;}

    /**
     * Get the allowed types, this plugin allows everything
     *
     * @return array The allowed types
     */
    function getAllowedTypes() {
        return array('container', 'baseonly', 'formatting', 'substition', 'protected', 'disabled', 'paragraphs');
    }

	function connectTo($mode) { 
      $this->Lexer->addEntryPattern('<autolink>(?=.*?\x3C/autolink\x3E)',$mode,'plugin_autolink2_show'); 
    }
    function postConnect() { 
      $this->Lexer->addExitPattern('</autolink>','plugin_autolink2_show'); 
    }
    

    function handle($match, $state, $pos, Doku_Handler &$handler){
        /** @var helper_plugin_autolink2 $helper */
        $helper = plugin_load('helper', 'autolink2', false);
        switch ($state) {
            case DOKU_LEXER_ENTER:
                $helper->enterAutolink();
                break;
            case DOKU_LEXER_EXIT:
                $helper->exitAutolink();
                break;
            case DOKU_LEXER_UNMATCHED:
                $handler->_addCall('cdata', array($match), $pos);
                break;
        }

       return false;
    }
 
    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {
        return false;
    }
}
