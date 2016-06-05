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
    public function update_schema()
    {
        return array(
            'add_tables' => array(
                $this->table_prefix . 'dp_versions' => array(
                    'COLUMNS'     => array(
                        'version_id'    => array('UINT', null, 'auto_increment'),
                        'name'          => array('VCHAR:255', 0),
                        'version_time'  => array('TIMESTAMP', 0),
                        'eol'           => array('VCHAR:255', ''),
                        'sort'          => array('TINT:1', ''),
                    ),
                    'PRIMARY_KEY' => 'version_id',
                    'KEYS'        => array(
                        'order' => array('INDEX', 'order'),
                    ),
                ),
                $this->table_prefix . 'dp_releases' => array(
                    'COLUMNS'     => array(
                        'release_id'    => array('UINT', null, 'auto_increment'),
                        'version_id'    => array('UINT', 0),
                        'name'          => array('VCHAR:255', 0),
                        'release_time'  => array('TIMESTAMP', 0),
                    ),
                    'PRIMARY_KEY' => 'release_id',
                    'KEYS'        => array(
                        'version_id' => array('INDEX', 'version_id'),
                    ),
                ),
                $this->table_prefix . 'dp_downloads' => array(
                    'COLUMNS'     => array(
                        'download_id'   => array('UINT', null, 'auto_increment'),
                        'release_id'    => array('UINT', 0),
                        'name'          => array('VCHAR:255', 0),
                        'downloads'     => array('UINT', 0),
                        'filename'      => array('VCHAR:255'),
                        'filelocation'  => array('VCHAR:255'),
                        'counter'       => array('UINT', 0),
                    ),
                    'PRIMARY_KEY' => 'release_id',
                    'KEYS'        => array(
                        'release_id' => array('INDEX', 'release_id'),
                    ),
                ),
            ),
        );
    }

    public function revert_schema()
    {
        return array(
            'drop_tables' => array(
                $this->table_prefix . 'dp_versions',
                $this->table_prefix . 'dp_releases',
                $this->table_prefix . 'dp_downloads',
            ),
        );
    }
}