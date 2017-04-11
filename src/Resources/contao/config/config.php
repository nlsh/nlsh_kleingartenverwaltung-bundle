<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2017 Leo Feyer
 *
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @author    Nils Heinold
 * @link      http://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 * @copyright Nils Heinold 2017
 */


/**
 * Für das Backend eine .css- Datei hinzufügen,
 * um ein Symbol für die "Kleingartenverwaltung" zu erzeugen
 */

if (TL_MODE === 'BE') {
    $GLOBALS['TL_CSS'][] = 'bundles/nlshkleingartenverwaltung/backend.css';
}


/**
 * BACK END MODULES
 */
array_insert($GLOBALS['BE_MOD'], 0, array
(
    'Kleingartenverwaltung' => array
    (
        'Garten_garten' => array
        (
            'tables'       => array('tl_nlsh_garten_verein_stammdaten','tl_nlsh_garten_garten_data'),
        ),
        'Garten_config' => array
        (
            'tables'       => array ('tl_nlsh_garten_config'),
        )
    )
));


/**
 * FRONT END MODULES
 */
array_insert($GLOBALS['FE_MOD']['garten_auswertungen'], 0, array
(
    'nlsh_gesamtausgabe'        => 'Nlsh\KleingartenverwaltungBundle\ModuleNlshGartenGesamtausgabe',

));