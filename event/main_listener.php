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


use phpbb\controller\helper;

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
     * main_listener constructor.
     * @param \phpbb\template\template $template
     * @param helper $helper
     */
    public function __construct(\phpbb\template\template $template, helper $helper)
    {
        $this->template = $template;
        $this->helper = $helper;
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
        );
    }

    public function show_page_links() {
        $this->template->assign_vars([
            'U_DOWNLOAD'			=> $this->helper->route('paul999_downloadpage_main'),
        ]);
    }
}