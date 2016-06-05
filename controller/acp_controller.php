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


class acp_controller
{

    /**
     * @var string
     */
    private $u_action;

    /**
     * acp_controller constructor.
     *
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function versions() {

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