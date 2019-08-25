<?php


use Contao\NlshGartenConfigModel;
use Contao\NlshGartenGartenDataModel;

/*
 * Erweiterung des tl_nlsh_garten_config DCA`s
 *
 * @copyright Nils Heinold (c) 2017
 * @author    Nils Heinold
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */


/*
 * Table tl_nlsh_garten_config
 */
$GLOBALS['TL_DCA']['tl_nlsh_garten_config'] = array(

     // Config
    'config'      => array(
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'onload_callback'  => array(array('tl_nlsh_garten_config', 'nameReadonly')),
        'sql'              => array(
            'keys' => array('id' => 'primary'),
        ),
    ),

     // List
    'list'        => array(
        'sorting'           => array(
            'mode'            => 1,
            'fields'          => array('jahr'),
            'disableGrouping' => true,
            'panelLayout'     => 'search,sort,filter,limit',
            'flag'            => 4,
        ),
        'label'             => array(
            'fields' => array('jahr'),
            'format' => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['config_label'],
        ),
        'global_operations' => array(
            'all' => array(
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"',
            ),
        ),
        'operations'        => array(
            'edit'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ),
            'copy'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ),
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ),
        ),
    ),

     // Edit
    'edit'        => array(
        'buttons_callback' => array(),
    ),

     // Palettes
    'palettes'    => array(
        '__selector__' => array(''),
        'default'      => ' jahr;
                                           {Vorschuss_beitrag_pacht_legend:hide},
                                                nlsh_garten_vorschuss_beitrag,
                                                nlsh_garten_vorschuss_pacht;
                                           {Verbrauchsdaten_vorjahr_legend:hide},
                                                nlsh_garten_verbrauchsdaten_vorjahr;
                                           {Rechnung_vorbelegung_legend:hide},
                                                nlsh_rgvorbelegung_datum,
                                                nlsh_garten_text_rg_verbrauchsdaten,
                                                nlsh_garten_text_rg_pacht_beitrag,
                                                nlsh_garten_text_rg_aufforder_zahlung,
                                                nlsh_garten_text_rg_hinweis;
                                           {DATEV_allgemein_legend:hide},
                                                nlsh_garten_beraternummer,
                                                nlsh_garten_mandantennummer,
                                                nlsh_garten_debitorenkonto;
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
                                           ',
    ),

     // Subpalettes
    'subpalettes' => array('' => ''),

     // Fields
    'fields'      => array(
        'id'                                           => array('sql' => 'int(10) unsigned NOT NULL auto_increment'),
        'tstamp'                                       => array('sql' => "int(10) unsigned NOT NULL default '0'"),
        'jahr'                                         => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['jahr'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'mandatory' => true,
                'maxlength' => 4,
                'rgxp'      => 'digit',
                'unique'    => true,
            ),
            'sql'       => "varchar(4) NOT NULL default ''",
        ),
        'nlsh_garten_vorschuss_beitrag'                => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_vorschuss_beitrag'],
            'inputType' => 'checkbox',
            'eval'      => array('tl_class' => 'w50'),
            'sql'       => "char(1) NOT NULL default '1'",
        ),
        'nlsh_garten_vorschuss_pacht'                  => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_vorschuss_pacht'],
            'inputType' => 'checkbox',
            'eval'      => array('tl_class' => 'w50'),
            'sql'       => "char(1) NOT NULL default '1'",
        ),
        'nlsh_garten_verbrauchsdaten_vorjahr'          => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_verbrauchsdaten_vorjahr'],
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default '0'",
        ),
        'nlsh_rgvorbelegung_datum'                     => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_rgvorbelegung_datum'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'       => 'date',
                'datepicker' => true,
                'tl_class'   => 'w50 wizard',
            ),
            'sql'       => "varchar(11) NOT NULL default ''",
        ),
        'nlsh_garten_text_rg_verbrauchsdaten'          => array(
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_text_rg_verbrauchsdaten'],
            'inputType'     => 'textarea',
            'load_callback' => array(array(
                'tl_nlsh_garten_config',
                'loadTextRgVerbrauchsdaten',
            ),
            ),
            'eval'          => array(
                'style'     => 'height:60px;',
                'maxlength' => 255,
                'allowHtml' => true,
                'rows'      => 3,
                'tl_class'  => 'clr long',
            ),
            'sql'           => "varchar(255) NOT NULL default ''",
        ),
        'nlsh_garten_text_rg_pacht_beitrag'            => array(
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_text_rg_pacht_beitrag'],
            'inputType'     => 'textarea',
            'load_callback' => array(array(
                'tl_nlsh_garten_config',
                'loadTextRgPachtBeitrag',
            ),
            ),
            'eval'          => array(
                'style'     => 'height:60px;',
                'maxlength' => 255,
                'allowHtml' => true,
                'rows'      => 3,
                'tl_class'  => 'long',
            ),
            'sql'           => "varchar(255) NOT NULL default ''",
        ),
        'nlsh_garten_text_rg_aufforder_zahlung'        => array(
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_text_rg_aufforder_zahlung'],
            'inputType'     => 'textarea',
            'load_callback' => array(array(
                'tl_nlsh_garten_config',
                'loadTextRgAufforderZahlung',
            ),
            ),
            'eval'          => array(
                'style'     => 'height:60px;',
                'maxlength' => 255,
                'allowHtml' => true,
                'rows'      => 3,
                'tl_class'  => 'long',
            ),
            'sql'           => "varchar(255) NOT NULL default ''",
        ),
        'nlsh_garten_text_rg_hinweis'                  => array(
            'label'         => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_text_rg_hinweis'],
            'inputType'     => 'textarea',
            'load_callback' => array(array(
                'tl_nlsh_garten_config',
                'loadTextRgHinweis',
            ),
            ),
            'eval'          => array(
                'style'     => 'height:60px;',
                'maxlength' => 255,
                'allowHtml' => true,
                'rows'      => 3,
                'tl_class'  => 'long',
            ),
            'sql'           => "varchar(255) NOT NULL default ''",
        ),
        'nlsh_garten_beraternummer'                    => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_beraternummer'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_mandantennummer'                  => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_mandantennummer'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_debitorenkonto'                   => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_debitorenkonto'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 5,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(5) NOT NULL default ''",
        ),
        'nlsh_garten_konto_beitrag'                    => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_beitrag'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_pacht'                      => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_pacht'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_strom'                      => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_strom'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_wasser'                     => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_wasser'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_individuell_01_gartenstamm' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_01_gartenstamm'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_individuell_02_gartenstamm' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_02_gartenstamm'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_individuell_03_gartenstamm' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_03_gartenstamm'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_individuell_04_gartenstamm' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_04_gartenstamm'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_individuell_01_garten'      => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_01_garten'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_individuell_02_garten'      => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_02_garten'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_individuell_03_garten'      => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_03_garten'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
        'nlsh_garten_konto_individuell_04_garten'      => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlsh_garten_konto_individuell_04_garten'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'      => 'digit',
                'maxlength' => 10,
                'tl_class'  => 'w50',
            ),
            'sql'       => "varchar(10) NOT NULL default ''",
        ),
    ),
);


/**
 * DCA- Klasser der Tabelle tl_nlsh_garten_config
 *
 * @package nlsh/nlsh_kleingartenverwaltung-bundle
 */

 /**
  * Class tl_nlsh_garten_config
  *
  * Enthält Funktionen einzelner Felder der Konfiguration
  *
  * @copyright Nils Heinold (c) 2017
  * @author    Nils Heinold
  * @package   nlsh/nlsh_kleingartenverwaltung-bundle
  * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
  * @license   LGPL
  */
class tl_nlsh_garten_config extends Backend
{

    /**
     * Feld 'name' auf 'readonly' => TRUE setzen
     *
     * Sollte es sich nicht um eine Neuanlage der Konfiguration handeln,
     * soll so verhindert werden, das Jahr zu ändern
     *
     * onload_callback des DataContainers
     *
     * @param \DataContainer $dc Contao- DataContainer- Objekt
     *
     * @return void
     */
    public function nameReadonly(\DataContainer $dc)
    {
        // Neuenlage einer Konfiguration kontrollieren
        // dazu den tstamp des Datensatzes heraussuchen
        $tstamp = NlshGartenConfigModel::findOneBy('id', $dc->id);

         // wenn tstamp = '0', dann Jahr veränderbar
        if ($tstamp->tstamp !== '0') {
            $GLOBALS['TL_DCA']['tl_nlsh_garten_config']['fields']['jahr']['eval'] = array('readonly' => true);
        }

    }//end nameReadonly()

    /**
     * Vorbelegung des Rechnungstextes für die Verbrauchsberechnung
     *
     * Sollte kein Text bereits gespeichert sein, wird vorbelegt
     *
     * load_callback des Feldes nlsh_garten_text_rg_verbrauch
     *
     * @param string $field Aktuelles Textfeld
     *
     * @return string  Text
     */
    public function loadTextRgVerbrauchsdaten($field)
    {
        if ($field == false) {
            $field = $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['rg_verbrauchsdaten'];
        }

        return ($field);

    }//end loadTextRgVerbrauchsdaten()

    /**
     * Vorbelegung des Rechnungstextes für die Beitragsabrechnung
     *
     * Sollte kein Text bereits gespeichert sein, wird vorbelegt
     *
     * load_callback des Feldes nlsh_garten_text_rg_pacht_beitrag
     *
     * @param string $field Aktuelles Textfeld
     *
     * @return string  Text
     */
    public function loadTextRgPachtBeitrag($field)
    {
        if ($field == false) {
            $field = $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['rg_pacht_beitrag'];
        }

        return ($field);

    }//end loadTextRgPachtBeitrag()

    /**
     * Vorbelegung des Rechnungstextes für die Aufforderung zur Zahlung
     *
     * Sollte kein Text bereits gespeichert sein, wird vorbelegt
     *
     * load_callback des Feldes nlsh_garten_text_rg_aufforder_zahlung
     *
     * @param string $field Aktuelles Textfeld
     *
     * @return string  Text
     */
    public function loadTextRgAufforderZahlung($field)
    {
        if ($field == false) {
            $field = $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['rg_aufforder_zahlung'];
        }

        return ($field);

    }//end loadTextRgAufforderZahlung()

    /**
     * Vorbelegung des Rechnungstextes für den Hinweis
     *
     * Sollte kein Text bereits gespeichert sein, wird vorbelegt
     *
     * load_callback des Feldes nlsh_garten_text_rg_hinweis
     *
     * @param string $field Aktuelles Textfeld
     *
     * @return string  Text
     */
    public function loadTextRgHinweis($field)
    {
        if ($field == false) {
            $field = $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['rg_hinweis'];
        }

        return ($field);

    }//end loadTextRgHinweis()

}//end class
