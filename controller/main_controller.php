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


use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\exception\http_exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class main_controller
{
    /**
     * @var driver_interface
     */
    private $db;

    /**
     * @var string
     */
    private $downloads_table;

    /**
     * @var string
     */
    private $root_path;

    /**
     * @var helper
     */
    private $controller_helper;

    /**
     * @var string
     */
    private $versions_table;

    /**
     * @var string
     */
    private $releases_table;

    /**
     * main_controller constructor.
     * @param helper $controller_helper
     * @param driver_interface $db
     * @param string $downloads_table
     * @param string $versions_table
     * @param string $releases_table
     * @param string $root_path
     */
    public function __construct(helper $controller_helper, driver_interface $db, $downloads_table, $versions_table, $releases_table, $root_path)
    {
        $this->db = $db;
        $this->downloads_table = $downloads_table;
        $this->root_path = $root_path;
        $this->controller_helper = $controller_helper;
        $this->versions_table = $versions_table;
        $this->releases_table = $releases_table;
    }

    /**
     * @return Response
     */
    public function main()
    {
        $sql = 'SELECT * FROM ' . $this->versions_table . ' WHERE active = 1 ORDER BY sort';
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetchrow($result))
        {
            // Yes, we do a query in a loop here.
            // However, as the versions table should have <= 3 versions this should be fine.
            $sql_row = 'SELECT * FROM ' . $this->releases_table . ' WHERE version_id ' . $row['version_id'] . ' ORDER BY release_time';
            $result_row = $this->db->sql_query($sql_row);
        }
        $this->db->sql_freeresult($result);
        return $this->controller_helper->render('@paul999_downloadpage/download_main.html');
    }

    /**
     * Return a download.
     * @param int $id
     * @throws http_exception
     * @return BinaryFileResponse
     */
    public function download($id)
    {
        $sql = 'SELECT * FROM ' . $this->downloads_table . ' WHERE download_id = ' . (int)$id;
        $result = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);

        if (!$row && !file_exists($this->root_path . 'files/' . $row['filelocation'])) {
            throw new http_exception('DOWNLOAD_NOT_EXISTS');
        }

        $sql = 'UPDATE  ' . $this->downloads_table . ' SET downloads = downloads = 1 WHERE download_id = ' . (int)$id;
        $this->db->sql_query($sql);

        return new BinaryFileResponse($this->root_path . 'files/' . $row['filelocation']);
    }
}