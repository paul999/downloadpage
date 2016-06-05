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

class acp_info {
    public function module()
    {
        return array(
            'filename'	=> '\paul999\downloadpage\acp\acp_module',
            'title'		=> 'ACP_DOWNLOADPAGE',
            'modes'		=> array(
                'versions'	=> array(
                    'title' => 'ACP_DOWNLOADPAGE_VERSIONS',
                    'auth' => 'ext_paul999/downloadpage && acl_a_board',
                    'cat' => array('ACP_DOWNLOADPAGE'),
                ),
                'releases' => array(
                    'title' => 'ACP_DOWNLOADPAGE_RELEASES',
                    'auth' => 'ext_paul999/downloadpage && acl_a_board',
                    'cat'   => array('ACP_DOWNLOADPAGE'),
                )
            ),
        );
    }
}