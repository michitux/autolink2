<?php
/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Otto Vainio <oiv-plugins@valjakko.net>
 * @author     Michael Hamann <michael@content-space.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

/**
 * Helper part of the autolink2 plugin
 */
class helper_plugin_autolink2 extends DokuWiki_Plugin {
    private $in_autolink = false;
    private $autolink_disabled = '';
    private $anchors = null;

    /**
     * Get a description of all public methods of this helper plugin
     *
     * @return array The list of methods.
     */
    function getMethods(){
        $result = array();
        $result[] = array(
            'name'   => 'getAnchors',
            'desc'   => 'returns an array with the anchors as keys and the ids as values',
            'return' => array('anchor => id' => 'array'),
        );
        return $result;
    }


    /**
     * Get the list of all defined autolink anchors
     *
     * @return array The list of anchors in the form anchor => id
     */
    function getAnchors() {
        if (is_null($this->anchors)) {
            $indexer = new helper_plugin_autolink2_indexer();
            $index = $indexer->getAllPages('plugin_autolink2_anchors');
            // $index is array('key' => array('page1', 'page2', ...), ...)
            $this->anchors = array_map('current', $index);
        }
        return $this->anchors;
    }

    /**
     * This can be used in the parser to check if autolinks are enabled currently
     *
     * @return bool If autolinks are currently enabled
     */
    public function autolinkEnabled() {
        global $ID;
        return $this->in_autolink || ($this->getConf('autoautolink') && $this->autolink_disabled != $ID);
    }

    /**
     * Entering the autolink area
     */
    public function enterAutolink() {
        $this->in_autolink = true;
    }

    /**
     * Exiting the autolink area
     */
    public function exitAutolink() {
        $this->in_autolink = false;
    }

    /**
     * Disable automatic autolinks for the rest of the page
     */
    public function disableAutolink() {
        global $ID;
        $this->autolink_disabled = $ID;
    }
}

/**
 * Adapted indexer for the autolink2 plugin
 */
class helper_plugin_autolink2_indexer extends Doku_Indexer {
    /**
     * Get all keys and their pages for the specified metadata key
     *
     * @param string $key The metadata key
     * @return array The array in the form word => array of pages
     */
    public function getAllPages($key) {

        $metaname = idx_cleanName($key);
        $page_idx = $this->getIndex('page', '');

        if ($metaname == 'title') {
            $words = $this->getIndex('title', '');
            return array_combine($words, $page_idx);
        } else {
            $pages = array();
            $lines = $this->getIndex($metaname.'_i', '');
            foreach ($lines as $i => $line) {
                $pages[$i] = array_keys($this->parseTuples($page_idx, $line));
            }
            unset($lines);
            unset($page_idx);

            $words = $this->getIndex($metaname.'_w', '');
            return array_filter(array_combine($words, $pages));
        }
    }
}