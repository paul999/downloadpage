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

use paul999\downloadpage\core\constants;
use phpbb\console\command\user\add;
use phpbb\db\driver\driver_interface;
use phpbb\exception\http_exception;
use phpbb\files\upload;
use phpbb\json_response;
use phpbb\language\language;
use phpbb\log\log_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;

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
     * @var language
     */
    private $lang;
    /**
     * @var log_interface
     */
    private $log;
    /**
     * @var user
     */
    private $user;

    /**
     * acp_controller constructor.
     * @param request_interface $request
     * @param driver_interface $db
     * @param template $template
     * @param upload $upload
     * @param language $lang
     * @param log_interface $log
     * @param user $user
     * @param string $downloads_table
     * @param string $versions_table
     * @param string $releases_table
     */
    public function __construct(request_interface $request, driver_interface $db, template $template, upload $upload, language $lang, log_interface $log, user $user, $downloads_table, $versions_table, $releases_table)
    {
        $this->request          = $request;
        $this->downloads_table  = $downloads_table;
        $this->versions_table   = $versions_table;
        $this->releases_table   = $releases_table;
        $this->db               = $db;
        $this->upload           = $upload;
        $this->template         = $template;
        $this->lang = $lang;
        $this->log = $log;
        $this->user = $user;
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
            case 'activate':
            case 'deactivate':
                $this->activate($id, $action == 'activate');
            break;
            case 'delete':
                $this->delete($id);
            break;
            case 'addRelease':
                $this->createNewRelease();
                return;
        }
        $this->versionsIndex();
    }

    public function releases() {
        $action = $this->request->variable('action', '');
        $id = $this->request->variable('id', 0);
    }

    /**
     * @param string $u_action
     */
    public function setUAction($u_action)
    {
        $this->u_action = $u_action;
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
                'NAME'                  => $row['name'],
                'ACTIVE'                => $row['active'],
                'COUNT'                 => $fetch,
                'L_ACTIVATE_DEACTIVATE' => $this->lang->lang($row['active'] ? 'DEACTIVATE' : 'ACTIVATE'),
                'U_ACTIVATE_DEACTIVATE'	=> $this->u_action . "&amp;id={$row['version_id']}&amp;action=" . ($row['active'] ? 'deactivate' : 'activate'),
                'U_DELETE'              => $this->u_action . '&amp;action=delete&amp;id=' . $row['version_id'],
                'U_NEW_RELEASE'         => $this->u_action . '&amp;action=addRelease&amp;version_id=' . $row['version_id'],
            ));
        }
        $this->db->sql_freeresult($result);

        $this->template->assign_vars(array(
            'VERSIONS_MAIN' => true,
            'U_ACTION'      => $this->u_action,
        ));

        add_form_key('download_page_version');
    }

    /**
     * @param int $id
     * @param boolean $up
     */
    private function orderVersions($id, $up) {

    }

    private function delete($id) {
        if (confirm_box(true))
        {
            $id = (int) $id;
            $this->db->sql_query('DELETE FROM ' . $this->versions_table . " WHERE version_id = $id");
            $this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_VERSION_DELETE', false, []);

            if ($this->request->is_ajax())
            {
                $json_response = new \phpbb\json_response;
                $json_response->send(array(
                    'MESSAGE_TITLE'	=> $this->lang->lang('INFORMATION'),
                    'MESSAGE_TEXT'	=> $this->lang->lang('VERSION_DELETED'),
                    'REFRESH_DATA'	=> array(
                        'time'	=> 3
                    )
                ));
            }
        }
        else
        {
            confirm_box(false, $this->lang->lang('CONFIRM_OPERATION'), build_hidden_fields(array(
                    'id'	    => $id,
                    'i'			=> '-paul999-downloadpage-acp-acp_module',
                    'mode'		=> 'versions',
                    'action'	=> 'delete'))
            );
        }
    }

    /**
     * @param int $id
     * @param boolean $active
     */
    private function activate($id, $active) {
        $sql = 'UPDATE ' . $this->versions_table . ' SET active = ' . (int)$active . ' WHERE version_id = ' . (int)$id;
        $this->db->sql_query($sql);

        $json_response = new \phpbb\json_response;
        $json_response->send(array(
            'text'	=> $this->lang->lang((($active) ? 'DE' : '') . 'ACTIVATE'),
        ));
    }

    /**
     *
     */
    private function addVersion() {
        if (!check_form_key('download_page_version')) {
            $this->template->assign_var('ERROR_MSG', $this->lang->lang('FORM_INVALID'));
            return;
        }
        $name = $this->request->variable('name', '');

        if (empty($name)) {
            $this->template->assign_var('ERROR_MSG', $this->lang->lang('MISSING_VERSION_NAME'));
            return;
        }
        $sql = 'SELECT MAX(sort) as mx FROM ' . $this->versions_table;
        $result = $this->db->sql_query($sql);
        $max = (int) $this->db->sql_fetchfield('mx', $result);
        $this->db->sql_freeresult($result);

        $sql_ary = [
            'active'        => false,
            'name'          => $name,
            'sort'          => $max + 1,
            'version_time'  => time(),
        ];
        $sql = 'INSERT INTO ' . $this->versions_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
        $this->db->sql_query($sql);

        $this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_VERSION_ADD', false, [$name]);
        redirect($this->u_action);
    }

    /**
     *
     */
    private function createNewRelease() {
        $version_id = $this->request->variable('version_id', 0);

        $sql = 'SELECT * FROM ' . $this->versions_table . ' WHERE version_id = ' . $version_id;
        $result = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($result);

        $this->db->sql_freeresult($result);

        if (!$row) {
            trigger_error('NO_VERSION_FOUND', E_USER_ERROR);
        }
        if ($this->request->is_set_post('submit')) {
            $this->handleCreateNewRelease($version_id);
        }

        $this->template->assign_vars([
            'ADD_NEW_RELEASE'   => true,
            'U_ACTION'          => $this->u_action . '&action=addRelease&amp;version_id=' . $version_id,
        ]);

    }

    private function handleCreateNewRelease($version_id)
    {
        $sql_ary = [
            'version_id'    => $version_id,
            'name'          => $this->request->variable('name', ''),
            'release_time'  => strtotime($this->request->variable('date', '')),
            'active'        => $this->request->is_set_post('active'),

        ];
        $sql = 'INSERT INTO ' . $this->releases_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
        $this->db->sql_query($sql);
        $id = (int)$this->db->sql_nextid();

        $sql_ary = [

        ];
        $error = [];
        $this->upload->set_allowed_extensions(['zip']);
        foreach ([constants::FULL_PACKAGE => 'full_package', constants::UPDATE_PACKAGE => 'update', constants::TRANSLATION => 'translation'] as $type => $name) {
            $result = $this->upload->handle_upload('files.types.form', $name . '_upload');
            $this->upload->common_checks($result);

            if (sizeof($result->error)) {
                array_merge($error, $result->error);
                continue;
            }
            $result->move_file('files/');


            $sql_ary = [
                'release_id'    => $id,
                'name'          => $this->request->variable($name . '_name', ''),
                'description'   => $this->request->variable($name . '_description', ''),
                'active'        => $this->request->is_set_post($name . '_active'),
                'filename'      => $result->get('uploadname'),
                'filelocation'  => $result->get('realname'),
                'type'          => $type,
            ];
            $this->db->sql_query('INSERT INTO ' . $this->downloads_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));
        }

        if ($error) {
            // TODO: Handle errors properly. NOTE: A release is arleady created!
            var_dump($error);
            exit;
        }

        trigger_error('RELEASE_CREATED', E_USER_NOTICE);
    }
}