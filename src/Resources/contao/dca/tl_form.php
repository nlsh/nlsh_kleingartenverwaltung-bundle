<?php
/**
 * Erweiterung des tl_form DCA`s
 *
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @author    Nils Heinold
 * @copyright Nils Heinold (c) 2019
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */

/**
 * Table tl_form
 */
 $GLOBALS['TL_DCA']['tl_form']['fields']['nlsh_ident'] = array('sql' => "varchar(20) NOT NULL default ''");
