<?php
/**
 * Erweiterung des tl_member DCA`s
 *
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @author    Nils Heinold
 * @copyright Nils Heinold (c) 2019
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
