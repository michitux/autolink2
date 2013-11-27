<?php
/**
 * Allows definition of autolink which is then shown using wikilink tag throughout the pages:
 * Example:
 * On the page wanted to be autolinked. {{autolink>anchors|separated by|}}
 * On the pages where autolink is wanted to insert the whole page around <autolink> and </autolink>
 * or by setting option 'autoautolink' to 1 links are set in avery page. You can prevent page from
 * autoimatically  setting links by setting ~~noautolink~~ in the start of the page
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if (!defined('DOKU_INC')) die('Must be run inside DokuWiki');

/**
 * Syntax component for defining autolinks
 */
class syntax_plugin_autolink2_add extends DokuWiki_Syntax_Plugin {

    function getType(){ return 'substition'; }
    function getSort(){ return 304; }
    function getPType(){ return 'block';}

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        // {{autolink>Anchor text}}
        // '\{\{tag>.*?\}\}'
        $this->Lexer->addSpecialPattern('\{\{autolink>.*?\}\}',$mode,'plugin_autolink2_add');
    }

    /**
     * Handle the match
     */

    function handle($match, $state, $pos, Doku_Handler &$handler){
        return explode('|', substr($match, 11, -2)); // strip markup and split tags
    }


    /**
     * Create output
     */
    function render($mode, Doku_Renderer &$renderer, $data) {
        if ($mode == 'metadata'){
            /** @var $renderer Doku_Renderer_metadata */
            $renderer->meta['plugin_autolink2_anchors'] = $data;
            return true;
        }
        return false;
    }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
