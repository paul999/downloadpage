<?php
/**
 *
 * Download Page extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2016 Paul Sohier <http://www.phpbbextensions.io>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge(
    $lang, array(
        'PHPBB_FREE_IN_USE'         => 'phpBB is vrij in gebruik, zolang je kennis hebt genomen van de <a href="http://www.phpbb.com/downloads/license/">licentie</a> waar de phpBB software onder valt.',
        'DOWNLOAD_PHPBB'            => 'Download phpBB',
        'UPDATE_PHPBB'              => 'Update phpBB',
        'USE_NEW_INSTALLATION'      => 'Gebruik dit pakket voor een nieuwe installatie.',
        'USE_UPDATE_INSTALLATION'   => '',
        'PREVIOUS_VERSIONS'         => 'Vorige versies',
        'ALWAYS_CURRENT'            => 'Installeer altijd de laatste versie. Mocht je toch op zoek zijn naar oudere versies, dan kan je deze hieronder downloaden',
        'LANG_PACKS'                => 'Nederlandse vertaling',

        'VERSION_EOL'       => 'Versie End of Life',
        'PHPBB_EOL'         => 'Let op: phpBB %s is End of Life en wordt niet meer ondersteund worden. Het is aangeraden om te upgraden naar 1 van de bovenstaande versies.',
        'RELEASED_AT'       => 'Vrijgegeven op %s',

        'FULL_PACKAGE'      => 'Volledig pakket',
        'UPDATE_PACKAGE'    => 'Automatische updater',
        'DOWNLOAD_PHPBB_HOME'   => 'phpBB is vrij in gebruik, zolang u kennis heeft genomen van de <a href="http://www.phpbb.com/downloads/license/">licentie</a> waar de phpBB software onder valt. <a href="%s">Meer downloads.</a>',
        'DOWNLOAD_PAGE'     => 'Downloads',
    )
);
