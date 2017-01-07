<?php
/**
 *
 * Download Page extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2016 Paul Sohier <http://www.phpbbextensions.io>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\downloadpage\event;


use paul999\downloadpage\core\constants;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\language\language;
use phpbb\user;

class main_listener implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    /**
     * @var \phpbb\template\template
     */
    private $template;
    /**
     * @var helper
     */
    private $helper;
    /**
     * @var driver_interface
     */
    private $db;
    /**
     * @var
     */
    private $downloads_table;
    /**
     * @var
     */
    private $versions_table;
    /**
     * @var
     */
    private $releases_table;
    /**
     * @var language
     */
    private $lang;
    /**
     * @var user
     */
    private $user;

    /**
     * main_listener constructor.
     * @param \phpbb\template\template $template
     * @param helper $helper
     * @param driver_interface $db
     * @param language $lang
     * @param user $user
     * @param $downloads_table
     * @param $versions_table
     * @param $releases_table
     */
    public function __construct(\phpbb\template\template $template, helper $helper, driver_interface $db, language $lang, user $user, $downloads_table, $versions_table, $releases_table)
    {
        $this->template = $template;
        $this->helper = $helper;
        $this->db = $db;
        $this->downloads_table = $downloads_table;
        $this->versions_table = $versions_table;
        $this->releases_table = $releases_table;
        $this->lang = $lang;
        $this->user = $user;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            'core.page_header'						=> 'show_page_links',
            'phpbbnl.homepage'                      => 'homepage',
        );
    }

    public function show_page_links() {
        $this->template->assign_vars([
            'U_DOWNLOAD'			=> $this->helper->route('paul999_downloadpage_main'),
        ]);
    }

    public function homepage() {
        $this->template->assign_vars([
            'S_DOWNLOAD_PAGE' => true,
            'DOWNLOAD_PHPBB_HOME' => $this->lang->lang('DOWNLOAD_PHPBB_HOME', $this->helper->route('paul999_downloadpage_main')),
        ]);
        $this->lang->add_lang('common', 'paul999/downloadpage');
        $sql = 'SELECT * FROM ' . $this->versions_table . ' WHERE active = 1 AND eol = \'\' ORDER BY sort DESC';

        $result = $this->db->sql_query($sql);

        while ($row = $this->db->sql_fetchrow($result))
        {
            $this->template->assign_block_vars('releases', array(
                'NAME'                       => $row['name'],
            ));

            // Yes, we do a queries in a loop here.
            // However, as the versions table should have <= 3 versions this should be fine.
            $sql_row = 'SELECT * FROM ' . $this->releases_table . ' WHERE version_id = ' . $row['version_id'] . ' AND active = 1 ORDER BY release_time DESC';
            $result_row = $this->db->sql_query_limit($sql_row, 1);

            while ($row_row = $this->db->sql_fetchrow($result_row))
            {
                $this->template->assign_block_vars('releases.versions', array(
                    'RELEASED_AT'   => $this->lang->lang('RELEASED_AT', $this->user->format_date($row_row['release_time'])),
                ));

                $sql = 'SELECT * FROM ' . $this->downloads_table . ' WHERE active = 1 AND release_id = ' . (int)$row_row['release_id'] . ' AND type = ' . constants::FULL_PACKAGE;

                $int_result = $this->db->sql_query($sql);

                while($int_row = $this->db->sql_fetchrow($int_result))
                {
                    $this->template->assign_block_vars('releases.versions.downloads', array(
                        'U_DOWNLOAD'            => $this->helper->route('paul999_downloadpage_download', array(
                            'id' => $int_row['download_id'],
                            'name'  => str_replace('.zip', '', $int_row['filename']),
                        )),
                        'NAME'                  => $int_row['name'],
                        'S_FULL_PACKAGE'        => $int_row['type'] == constants::FULL_PACKAGE,
                    ));
                }
                $this->db->sql_freeresult($int_result);
            }
            $this->db->sql_freeresult($result_row);
        }
        $this->db->sql_freeresult($result);
    }
}