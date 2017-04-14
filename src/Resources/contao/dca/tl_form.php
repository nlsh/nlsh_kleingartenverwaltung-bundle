<?php
/**
 * Erweiterung des tl_form DCA`s
 *
 * @copyright Nils Heinold (c) 2017
 * @author    Nils Heinold
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */


/**
 * Table tl_form
 */
 $GLOBALS['TL_DCA']['tl_form']['fields']['nlsh_ident'] = array(
                'sql' => "varchar(20) NOT NULL default ''"
 );