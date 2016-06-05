<?php
/**
 *
 * Download Page extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2016 Paul Sohier <http://www.phpbbextensions.io>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace paul999\downloadpage\migrations;

class create_module extends \phpbb\db\migration\migration
{
    public function update_data()
    {
        return array(
            array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_DOWNLOADPAGE')),
            array('module.add', array(
                'acp', 'ACP_DOWNLOADPAGE', array(
                    'module_basename'	=> '\paul999\downloadpage\acp\acp_module',
                    'modes'				=> array('versions'),
                ),
            )),
            array('module.add', array(
                'acp', 'ACP_DOWNLOADPAGE', array(
                    'module_basename'	=> '\paul999\downloadpage\acp\acp_module',
                    'modes'				=> array('releases'),
                ),
            )),
        );
    }
}