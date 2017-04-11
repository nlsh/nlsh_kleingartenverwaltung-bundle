<?php


/**
 * Erweiterung des tl_nlsh_garten_config DCA`s
 *
 * @copyright Nils Heinold (c) 2017
 * @author    Nils Heinold
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */


/**
 * Table tl_nlsh_garten_config
 */
$GLOBALS['TL_DCA']['tl_nlsh_garten_config'] = array
(

     // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => TRUE,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

     // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('jahr'),
            'disableGrouping'         => TRUE,
            'panelLayout'             => 'search,sort,filter,limit',
            'flag'                    => 4
        ),
        'label' => array
        (
            'fields'                  => array('jahr'),
            'format'                  => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['config_label']
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

     // Edit
    'edit' => array
    (
        'buttons_callback'            => array()
    ),

     // Palettes
    'palettes' => array
    (
        '__selector__'                => array(''),
        'default'                     => ' jahr;
                                           {Vorschuss_beitrag_pacht_legend:hide},
                                                nlsh_garten_vorschuss_beitrag,
                                                nlsh_garten_vorschuss_pacht;
                                           {Verbrauchsdaten_vorjahr_legend:hide},
                                                nlsh_garten_verbrauchsdaten_vorjahr;
                                           {Rechnung_texte_legend},
                                                nlsh_garten_text_rg_verbrauchsdaten,
                                                nlsh_garten_text_rg_pacht_beitrag,
                                                nlsh_garten_text_rg_aufforder_zahlung,
                                                nlsh_garten_text_rg_hinweis;
                                           {Kontenrahmen_legend:hide},
                                                nlsh_garten_konto_pacht,
                                                nlsh_garten_konto_beitrag,
                                                nlsh_garten_konto_strom,
                                                nlsh_garten_konto_wasser;
                                           {Individuell_gartenstamm_legend:hide},
                                                nlsh_garten_konto_individuell_01_gartenstamm,
                                                nlsh_garten_konto_individuell_02_gartenstamm,
                                                nlsh_garten_konto_individuell_03_gartenstamm,
                                                nlsh_garten_konto_individuell_04_gartenstamm;
                                           {Individuell_garten_legend:hide},
                                                nlsh_garten_konto_individuell_01_garten,
                                                nlsh_garten_konto_individuell_02_garten,
                                                nlsh_garten_konto_individuell_03_garten,
                                                nlsh_garten_konto_individuell_04_garten,
                                           '
    ),

     // Subpalettes
    'subpalettes' => array
    (
        ''                            => ''
    ),

     // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'jahr' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['jahr'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                'mandatory' => TRUE,
                                'maxlength' => 4,
                                'rgxp' => 'digit',
                                'unique' => TRUE,
            ),
                'sql'           => "varchar(4) NOT NULL default ''"
        ),
        'nlsh_garten_vorschuss_beitrag' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_vorschuss_beitrag'],
            'inputType'     => 'checkbox',
            'eval'          => array('tl_class' => 'w50'),
            'sql'           => "char(1) NOT NULL default '1'"
        ),
        'nlsh_garten_vorschuss_pacht' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_vorschuss_pacht'],
            'inputType'     => 'checkbox',
            'eval'          => array('tl_class' => 'w50'),
            'sql'           => "char(1) NOT NULL default '1'"
        ),
        'nlsh_garten_verbrauchsdaten_vorjahr' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_verbrauchsdaten_vorjahr'],
            'inputType'     => 'checkbox',
            'sql'           => "char(1) NOT NULL default '0'"
        ),
        'nlsh_garten_text_rg_verbrauchsdaten' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_text_rg_verbrauchsdaten'],
            'inputType'     => 'textarea',
            'load_callback' => array(array(
                                      'tl_nlsh_garten_config',
                                      'loadTextRgVerbrauchsdaten')
                               ),
            'eval'          => array(
                                  'style'     => 'height:60px;',
                                  'maxlength' => 255,
                                  'allowHtml' => TRUE,
                                  'rows'      => 3,
                                  'tl_class'  => 'long'
            ),
            'sql'           => "varchar(255) NOT NULL default ''"
        ),
        'nlsh_garten_text_rg_pacht_beitrag' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_text_rg_pacht_beitrag'],
            'inputType'     => 'textarea',
            'load_callback' => array(array(
                                      'tl_nlsh_garten_config',
                                      'loadTextRgPachtBeitrag')
                               ),
            'eval'          => array(
                                  'style'     => 'height:60px;',
                                  'maxlength' => 255,
                                  'allowHtml' => TRUE,
                                  'rows'      => 3,
                                  'tl_class'  => 'long'
            ),
            'sql'           => "varchar(255) NOT NULL default ''"
        ),
        'nlsh_garten_text_rg_aufforder_zahlung' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_text_rg_aufforder_zahlung'],
            'inputType'     => 'textarea',
            'load_callback' => array(array(
                                      'tl_nlsh_garten_config',
                                      'loadTextRgAufforderZahlung')
                               ),
            'eval'          => array(
                                  'style' => 'height:60px;',
                                  'maxlength' => 255,
                                  'allowHtml' => TRUE,
                                  'rows'      => 3,
                                  'tl_class'  => 'long'
            ),
            'sql'           => "varchar(255) NOT NULL default ''"
        ),
        'nlsh_garten_text_rg_hinweis' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_text_rg_hinweis'],
            'inputType'     => 'textarea',
            'load_callback' => array(array(
                                      'tl_nlsh_garten_config',
                                      'loadTextRgHinweis')
                               ),
            'eval'          => array(
                                  'style'     => 'height:60px;',
                                  'maxlength' => 255,
                                  'allowHtml' => TRUE,
                                  'rows'      => 3,
                                  'tl_class'  => 'long'
            ),
            'sql'           => "varchar(255) NOT NULL default ''"
        ),
        'nlsh_garten_konto_beitrag' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_beitrag'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_pacht' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_pacht'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_strom' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_strom'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_wasser' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_wasser'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_individuell_01_gartenstamm' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_01_gartenstamm'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_individuell_02_gartenstamm' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_02_gartenstamm'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_individuell_03_gartenstamm' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_03_gartenstamm'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_individuell_04_gartenstamm' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_04_gartenstamm'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_individuell_01_garten' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_01_garten'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_individuell_02_garten' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_02_garten'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_individuell_03_garten' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_03_garten'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        ),
        'nlsh_garten_konto_individuell_04_garten' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_04_garten'],
            'exclude'       => TRUE,
            'inputType'     => 'text',
            'eval'          => array(
                                  rgxp        => 'digit',
                                  'maxlength' => 10,
                                  'tl_class'  => 'w50'
            ),
            'sql'           => "varchar(10) NOT NULL default ''"
        )
    )
);


/**
 * DCA- Klasser der Tabelle tl_nlsh_garten_config
 *
 * @package   nlshKleingartenverwaltung
 */

 /**
  * Class tl_nlsh_garten_config
  *
  * Enthält Funktionen einzelner Felder der Konfiguration
  *
  * @copyright Nils Heinold (c) 2013
  * @author    Nils Heinold
  * @package   nlshKleingartenverwaltung
  * @link      https://github.com/nlsh/nlsh_Kleingartenverwaltung
  * @license   LGPL
  */
class tl_nlsh_garten_config extends Backend
{


    /**
     * Vorbelegung des Rechnungstextes für die Verbrauchsberechnung
     *
     * Sollte kein Text bereits gespeichert sein, wird vorbelegt
     *
     * load_callback des Feldes nlsh_garten_text_rg_verbrauch
     *
     * @param  string  $field Aktuelles Textfeld
     *
     * @return string  Text
     */
    public function loadTextRgVerbrauchsdaten($field) {
        if ($field == FALSE) {
            $field = $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['rg_verbrauchsdaten'];
        }

        return ($field);
    }


    /**
     * Vorbelegung des Rechnungstextes für die Beitragsabrechnung
     *
     * Sollte kein Text bereits gespeichert sein, wird vorbelegt
     *
     * load_callback des Feldes nlsh_garten_text_rg_pacht_beitrag
     *
     * @param  string  $field Aktuelles Textfeld
     *
     * @return string  Text
     */
    public function loadTextRgPachtBeitrag($field) {
        if ($field == FALSE) {
            $field = $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['rg_pacht_beitrag'];
        }

        return ($field);
    }


    /**
     * Vorbelegung des Rechnungstextes für die Aufforderung zur Zahlung
     *
     * Sollte kein Text bereits gespeichert sein, wird vorbelegt
     *
     * load_callback des Feldes nlsh_garten_text_rg_aufforder_zahlung
     *
     * @param  string  $field Aktuelles Textfeld
     *
     * @return string  Text
     */
    public function loadTextRgAufforderZahlung($field) {
        if ($field == FALSE) {
            $field = $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['rg_aufforder_zahlung'];
        }

        return ($field);
    }


    /**
     * Vorbelegung des Rechnungstextes für den Hinweis
     *
     * Sollte kein Text bereits gespeichert sein, wird vorbelegt
     *
     * load_callback des Feldes nlsh_garten_text_rg_hinweis
     *
     * @param  string  $field Aktuelles Textfeld
     *
     * @return string  Text
     */
    public function loadTextRgHinweis($field) {
        if ($field == FALSE) {
            $field = $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['rg_hinweis'];
        }

        return ($field);
    }
}