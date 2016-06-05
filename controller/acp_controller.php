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

        switch ($action) {
            case 'order':
                $this->orderVersions();
                break;
            case 'add':
                $this->addVersion();
                break;
            case 'addNew':
                $this->addNewVersion();
                break;
            default:
                $this->versionsIndex();
        }
    }

    /**
     *
     */
    private function versionsIndex() {

    }

    /**
     *
     */
    private function orderVersions() {

    }

    /**
     *
     */
    private function addVersion() {

    }

    /**
     *
     */
    private function addNewVersion() {

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