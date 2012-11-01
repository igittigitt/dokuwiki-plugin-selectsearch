<?php
if(!defined('DOKU_INC')) die();

class action_plugin_selectsearch extends DokuWiki_Action_Plugin {

    /**
     * Register its handlers with the DokuWiki's event controller
     */
    function register(&$controller) {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER',  $this, '_fixquery');
    }

    /**
     * Put namespace into search
     */
    function _fixquery(&$event, $param) {
        global $QUERY;
        global $ACT;

        if($ACT != 'search'){
            $QUERY = '';
            return;
        }

        if(trim($_REQUEST['namespace'])){
            $QUERY .= ' @'.trim($_REQUEST['namespace']);
        }
    }

    function tpl_searchform($namespaces) {
        global $QUERY;
        $cur_val = isset($_REQUEST['namespace']) ? $_REQUEST['namespace'] : '';

        echo '<form method="post" action="" accept-charset="utf-8">';
        echo '<select class="selectsearch_namespace" name="namespace">';
        foreach ($namespaces as $ns => $displayname){
            echo '<option value="'.hsc($ns).'"'.($cur_val === $ns ? ' selected="selected"' : '').'>'.hsc($displayname).'</option>';
        }
        echo '</select>';
        echo '<input type="hidden" name="do" value="search" />';
        echo '<input type="hidden" id="qsearch__in"/>';
        echo '<input class="query" id="selectsearch__input" type="text" name="id" autocomplete="off" value="'.hsc(preg_replace('/ ?@\S+/','',$QUERY)).'" accesskey="f" />';
        echo '<input class="submit" type="submit" name="submit" value="Search" />';
        echo '</form>';
    }
}