<?php
/**
 * Autolink2  Plugin
 *
 * @author Otto Vainio <otto@valjakko.net>
 * @author Michael Hamann <michael@content-space.de>
 */

if(!defined('DOKU_INC')) die();

/**
 * The action part of the autolink2 plugin
 */
class action_plugin_autolink2 extends DokuWiki_Action_Plugin {

    /**
     * Register its handlers with the DokuWiki's event controller
     */
    function register(Doku_Event_Handler $controller) {
        $controller->register_hook('INDEXER_PAGE_ADD', 'BEFORE',  $this, 'handle_indexer_page_add');
        $controller->register_hook('INDEXER_VERSION_GET', 'BEFORE',  $this, 'handle_indexer_version_get');
        $controller->register_hook('PARSER_CACHE_USE', 'BEFORE', $this, 'handle_parser_cache_use');
    }

    /**
     * Handle the indexer page add events, adds the autolink metdata to the index
     *
     * @param Doku_Event $event The event
     * @param mixed $param Possible parameters (ignored)
     */
    function handle_indexer_page_add(Doku_Event &$event, $param) {
        $meta = p_get_metadata($event->data['page'], 'plugin_autolink2_anchors', METADATA_RENDER_UNLIMITED | METADATA_RENDER_USING_CACHE);
        if (!is_null($meta)) {
            $event->data['metadata']['plugin_autolink2_anchors'] = $meta;
        }
    }

    /**
     * Handle the indexer_version_get event, adds the autolink index version to the indexer version
     *
     * @param Doku_Event $event The event object
     * @param mixed $param Possible parameters (ignored)
     */
    function handle_indexer_version_get(Doku_Event &$event, $param) {
        $event->data['plugin_autolink2'] = '0.1';
    }

    /**
     * Handle the parser_cache_use event, adds the autolink index as cache dependency
     *
     * @param Doku_Event $event The event object
     * @param mixed $param Possible paramters (ignored)
     * @return null
     */
    public function handle_parser_cache_use(Doku_Event &$event, $param) {
        global $conf;
        /** @var $cache cache_parser */
        $cache = $event->data;

        $cache->depends['files'][] = $conf['indexdir'].'/'.'plugin_autolink2_anchors_i.idx';
        return $event->result;
    }
}

