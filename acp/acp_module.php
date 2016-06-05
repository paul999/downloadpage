<?php
/**
 *
 * Download Page extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2016 Paul Sohier <http://www.phpbbextensions.io>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\downloadpage\acp;

use paul999\downloadpage\controller\acp_controller;

class acp_module {
    /** @var  string */
    public $u_action;

    /** @var   */
    public $page_title;

    /** @var   */
    public $tpl_name;

    public function main($id, $mode)
    {
        global $phpbb_container;
        /** @var acp_controller $controller */
        $controller = $phpbb_container->get('paul999.downloadpage.acp_controller');
        $controller->setUAction($this->u_action);

        $this->tpl_name = 'acp_downloads';

        switch($mode) {
            case 'versions':
                $controller->versions();
                $this->page_title = 'ACP_DOWNLOADPAGE_VERSIONS';
            break;

            case 'releases':
                $controller->releases();
                $this->page_title = 'ACP_DOWNLOADPAGE_DOWNLOADS';
            break;
        }
    }
}