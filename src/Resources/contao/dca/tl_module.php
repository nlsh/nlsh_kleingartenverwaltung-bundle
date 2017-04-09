<?php

/**
 * Erweiterung des tl_module DCA`s
 *
 * @copyright Nils Heinold (c) 2017
 * @author    Nils Heinold
 * @package   nlsh_kleingartenverwaltung-bundle
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['nlsh_gesamtausgabe'] = '{title_legend},
                                                                        name,
                                                                        headline,
                                                                        type;
                                                                     {protected_legend:hide},
                                                                        protected;
                                                                     {expert_legend:hide},
                                                                        guests,
                                                                        cssID,
                                                                        space';
