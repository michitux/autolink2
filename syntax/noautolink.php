<?php
/**
 * Disable autolink generation for the page
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if (!defined('DOKU_INC')) die('Must be run inside DokuWiki');

/**
 * Syntax component for disabling autolinks
 */
class syntax_plugin_autolink2_noautolink extends DokuWiki_Syntax_Plugin {

    function getType(){ return 'substition'; }
    function getSort(){ return 304; }
    function getPType(){ return 'block';}

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        // {{autolink>Anchor text}}
        // '\{\{tag>.*?\}\}'
        $this->Lexer->addSpecialPattern('~~noautolink~~',$mode,'plugin_autolink2_noautolink');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler &$handler){
        /** @var helper_plugin_autolink2 $helper */
        $helper = plugin_load('helper', 'autolink2');
        $helper->disableAutolink();
        return false;
    }


    /**
     * Create output
     */
    function render($mode, Doku_Renderer &$renderer, $data) {
        return false;
    }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
