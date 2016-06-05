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

class create_table extends \phpbb\db\migration\migration
{
    static public function depends_on()
    {
        return array(
            '\phpbb\db\migration\data\v320\v320b2',
            '\paul999\downloadpage\migrations\create_table',
            '\paul999\downloadpage\migrations\create_module',
        );
    }
    public function update_data()
    {
        return array(
            array('config.update', array('paul999.downloadpage.version', '1.0.0')),
        );
    }
}