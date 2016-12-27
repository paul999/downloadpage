<?php
/**
 *
 * Download Page extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2016 Paul Sohier <http://www.phpbbextensions.io>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\downloadpage\controller;

use phpbb\db\driver\driver_interface;
use phpbb\files\upload;
use phpbb\json_response;
use phpbb\request\request_interface;
use phpbb\template\template;

class acp_controller
{
    /**
     * @var request_interface
     */
    private $request;

    /**
     * @var driver_interface
     */
    private $db;

    /**
     * @var template
     */
    private $template;

    /**
     * @var upload
     */
    private $upload;

    /**
     * @var string
     */
    private $u_action;

    /**
     * @var string
     */
    private $downloads_table;

    /**
     * @var string
     */
    private $versions_table;

    /**
     * @var string
     */
    private $releases_table;

    /**
     * acp_controller constructor.
     * @param request_interface $request
     * @param driver_interface $db
     * @param template $template
     * @param upload $upload
     * @param string $downloads_table
     * @param string $versions_table
     * @param string $releases_table
     */
    public function __construct(request_interface $request, driver_interface $db, template $template, upload $upload, $downloads_table, $versions_table, $releases_table)
    {
        $this->request          = $request;
        $this->downloads_table  = $downloads_table;
        $this->versions_table   = $versions_table;
        $this->releases_table   = $releases_table;
        $this->db               = $db;
        $this->upload           = $upload;
        $this->template         = $template;
    }

    /**
     * @return void
     */
    public function versions() {
        $action = $this->request->variable('action', '');
        $id = $this->request->variable('id', 0);

        switch ($action) {
            case 'order':
                $this->orderVersions($id, $this->request->variable('up', false));
                break;
            case 'add':
                $this->addVersion();
                break;
            case 'active':
                $this->activate($id, $this->request->is_set('active'));
                break;
            case 'addRelease':
                $this->createNewRelease();
                break;
            default:
                $this->versionsIndex();
        }
    }

    /**
     *
     */
    private function versionsIndex() {
        $sql = 'SELECT * FROM ' . $this->versions_table . ' ORDER BY sort DESC';
        $result = $this->db->sql_query($sql);

        while($row = $this->db->sql_fetchrow($result))
        {
            $sql = 'SELECT COUNT(version_id) as number FROM ' . $this->releases_table . ' WHERE version_id = ' . $row['version_id'];
            $int_result = $this->db->sql_query($sql);
            $fetch = $this->db->sql_fetchfield('number', $int_result);
            $this->db->sql_freeresult($int_result);

            $this->template->assign_block_vars('versions', array(
                'NAME'      => $row['name'],
                'ACTIVE'    => $row['active'],
                'COUNT'     => $fetch,
            ));
        }
        $this->db->sql_freeresult($result);

        $this->template->assign_vars(array(
            'VERSIONS_MAIN' => true,
        ));
    }

    /**
     * @param int $id
     * @param boolean $up
     */
    private function orderVersions($id, $up) {

    }

    /**
     * @param int $id
     * @param boolean $active
     */
    private function activate($id, $active) {
        $sql = 'UPDATE ' . $this->versions_table . ' SET active = ' . (int)$active . ' WHERE release_id = ' . (int)$id;
        $this->db->sql_query($sql);

        $result = new json_response();
        $result->send(array('result' => true));
    }

    /**
     *
     */
    private function addVersion() {

    }

    /**
     *
     */
    private function createNewRelease() {

    }

    /**
     *
     */
    public function releases() {

    }

    /**
     * @param string $u_action
     */
    public function setUAction($u_action)
    {
        $this->u_action = $u_action;
    }
}