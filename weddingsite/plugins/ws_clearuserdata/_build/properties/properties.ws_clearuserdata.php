<?php
/**
 * Wedding site Clear User Data  plugin
 *
 * @author    S. Hamblett <steve.hamblett@linux.com>
 * @copyright 2011 S. Hamblett
 * @license   GPLv3 http://www.gnu.org/licenses/gpl.html
 */

$properties = array(
    array(
        'name' => 'reallyClear',
        'desc' => 'If set really removes the user data, otherwise archives it',
        'type' => 'textfield',
        'options' => '',
        'value' => '1',
    )
    );

return $properties;
