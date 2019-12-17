<?php
/**
 * Erweiterung des tl_nlsh_garten_verein_stammdaten DCA`s
 *
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @author    Nils Heinold
 * @copyright Nils Heinold (c) 2019
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */

use Symfony\Component\VarDumper\VarDumper;
use Contao\NlshGartenVereinStammdatenModel;
use Contao\NlshGartenGartenDataModel;

/*
 * Table tl_nlsh_garten_garten_data
 */

$GLOBALS['TL_DCA']['tl_nlsh_garten_garten_data'] = array(
        // Config.
       'config' => array
       (
        'dataContainer'     => 'Table',
        'ptable'            => 'tl_nlsh_garten_verein_stammdaten',
        'enableVersioning'  => true,
        'onload_callback'   => array(
                                    array(
                                        'tl_nlsh_garten_garten_data',
                                        'nameReadonly',
                                    ),
        ),
        'sql' => array
        (
            'keys'          => array(
                                    'id'        => 'primary',
                                    'pid'       => 'index',
            )
        )
    ),

     // List.
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('round(nr)'),
            'flag'                    => 1,
            'disableGrouping'         => 'true',
            'panelLayout'             => 'search,limit',
            'headerFields'            => array(
                                            'jahr',
                                            'name',
                                            'vereinsvorsitzender',
            ),
            'child_record_callback'   => array(
                                           'tl_nlsh_garten_garten_data',
                                           'listGarten',
            )
        ),
        'global_operations' => array
        (
             // Keine, nur die automatisch erzeugten.
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'delete' => array
            (
                'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['delete'],
                'href'              => 'act=delete',
                'icon'              => 'delete.svg',
                'attributes'        => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['delete'][0] . '\'))return false;Backend.getScrollOffset()"',
                'button_callback'   => array('tl_nlsh_garten_garten_data', 'bottomDelete')
            ),
            'show' => array
            (
                'label'          => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['show'],
                'href'           => 'act=show',
                'icon'           => 'show.gif'
            ),
        )
    ),

     // Palettes.
    'palettes' => array
    (
        '__selector__'      => array(''),
        'default'           => '{gartennummer_legend},
                                    nr;
                                {groesse_legend},
                                    grosse;
                                {verbrauchsdaten_legend},
                                    strom,wasser,
                                    abrechnungVorjahre;
                                {gartenbesitzer_legend},
                                    nutzung_user_id;
                                {zaehler_legend},
                                    wasserzaehler_1,
                                    stromzaehler_1,
                                    wasserzaehler_2,
                                    stromzaehler_2;
                                {besonderheiten_legend:hide},
                                    pacht_ja_nein,
                                    beitrag_ja_nein,
                                    individuell_01_gartenstamm_ja_nein,
                                    individuell_02_gartenstamm_ja_nein,
                                    individuell_03_gartenstamm_ja_nein,
                                    individuell_04_gartenstamm_ja_nein;
                                {abrechnung_garten_individuell_legend:hide},
                                    abrechnung_garten_individuell_01_name,
                                    abrechnung_garten_individuell_01_wert,
                                    individuell_01_dauer,
                                    abrechnung_garten_individuell_02_name,
                                    abrechnung_garten_individuell_02_wert,
                                    individuell_02_dauer,
                                    abrechnung_garten_individuell_03_name,
                                    abrechnung_garten_individuell_03_wert,
                                    individuell_03_dauer,
                                    abrechnung_garten_individuell_04_name,
                                    abrechnung_garten_individuell_04_wert,
                                    individuell_04_dauer;'
    ),

     // Subpalettes.
    'subpalettes' => array
    (
        ''                     => ''
    ),

     // Fields.
    'fields' => array
    (
        'id' => array
        (
            'sql'              => "int(10) unsigned NOT NULL auto_increment",
        ),
        'pid' => array
        (
            'sql'              => "int(10) unsigned NOT NULL default '0'",
        ),
        'tstamp' => array
        (
            'sql'              => "int(10) unsigned NOT NULL default '0'",
        ),
        'nr' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['nr'],
            'exclude'          => true,
            'inputType'        => 'text',
            'search'           => true,
            'eval'             => array(
                                     'mandatory' => true,
                                     'maxlength' => 20,
                                     'unique' => true,
                                     'tl_class' => 'w50'
            ),
            'sql'              => "varchar(20) NOT NULL default ''",
        ),
        'grosse' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['grosse'],
            'exclude'          => true,
            'inputType'        => 'text',
            'eval'             => array('mandatory' => true, 'rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'",
        ),
        'strom' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['strom'],
            'exclude'          => true,
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50', 'rgxp' => 'digit'),
            'sql'              => "double NOT NULL default '0'",
        ),
        'wasser' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['wasser'],
            'exclude'          => true,
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50', 'rgxp' => 'digit'),
            'sql'              => "double NOT NULL default '0'",
        ),
        'abrechnungVorjahre' => array
        (
            'input_field_callback'  => array(
                                          'tl_nlsh_garten_garten_data',
                                          'getOutYears',
            ),
        ),
        'nutzung_user_id' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['nutzungUserId'],
            'exclude'          => true,
            'inputType'        => 'select',
            'options_callback' => array(
                                     'tl_nlsh_garten_garten_data',
                                     'holeNamen',
            ),
            'save_callback'    => array(
                                   array(
                                     'tl_nlsh_garten_garten_data',
                                     'saveNameKomplett',
                                   ),
            ),
            'eval'             => array(
                                     'alwaysSave' => true,
                                     'includeBlankOption' => true,
                                     'blankOptionLabel'   => $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['nichtVergeben'],
                                     'tl_class'           => 'w50',
            ),
            'sql'              => "int(11) NOT NULL default '0'",
        ),
        'name_komplett' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['nameKomplett'],
            'exclude'          => true,
            'inputType'        => 'text',
            'search'           => true,
            'sql'              => "varchar(512) NOT NULL default ''",
        ),
        'wasserzaehler_1' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['wasserzaehler1'],
            'inputType'        => 'text',
                'eval'             => array('maxlength' => 50, 'tl_class' => 'w50'),
            'sql'              => "varchar(50) NOT NULL default ''",
        ),
        'wasserzaehler_2' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['wasserzaehler2'],
            'inputType'        => 'text',
                'eval'             => array('maxlength' => 50, 'tl_class' => 'w50'),
            'sql'              => "varchar(50) NOT NULL default ''",
        ),
        'stromzaehler_1' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['stromzaehler1'],
            'inputType'        => 'text',
            'eval'             => array('maxlength' => 50, 'tl_class' => 'w50'),
            'sql'              => "varchar(50) NOT NULL default ''",
        ),
        'stromzaehler_2' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['stromzaehler2'],
            'inputType'        => 'text',
                'eval'             => array('maxlength' => 50, 'tl_class' => 'w50'),
            'sql'              => "varchar(50) NOT NULL default ''",
        ),
        'pacht_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['pachtJaNein'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'",
        ),
        'beitrag_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['beitragJaNein'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'",
        ),
        'individuell_01_gartenstamm_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['indi01GartenstammJaNein'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'",
        ),
        'individuell_02_gartenstamm_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['indi02GartenstammJaNein'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'",
        ),
        'individuell_03_gartenstamm_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['indi03GartenstammJaNein'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'",
        ),
        'individuell_04_gartenstamm_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['indi04GartenstammJaNein'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'",
        ),
        'abrechnung_garten_individuell_01_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnungGartenIndi01Name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "varchar(80) NOT NULL default ''",
        ),
        'abrechnung_garten_individuell_01_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnungGartenIndi01Wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'",
        ),
        'individuell_01_dauer' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['indi01Dauer'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '0'",
        ),
        'abrechnung_garten_individuell_02_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnungGartenIndi02Name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50 clr'),
            'sql'              => "varchar(80) NOT NULL default ''",
        ),
        'abrechnung_garten_individuell_02_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnungGartenIndi02Wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'",
        ),
        'individuell_02_dauer' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['indi02Dauer'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'eval'             => array('tl_class' => 'w50 clr'),
            'sql'              => "char(1) NOT NULL default '0'",
        ),
        'abrechnung_garten_individuell_03_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnungGartenIndi03Name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50 clr'),
            'sql'              => "varchar(80) NOT NULL default ''",
        ),
        'abrechnung_garten_individuell_03_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnungGartenIndi03Wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'",
        ),
        'individuell_03_dauer' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['indi03Dauer'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'eval'             => array('tl_class' => 'w50 clr'),
            'sql'              => "char(1) NOT NULL default '0'",
        ),
        'abrechnung_garten_individuell_04_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnungGartenIndi04Name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50 clr'),
            'sql'              => "varchar(80) NOT NULL default ''",
        ),
        'abrechnung_garten_individuell_04_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnungGartenIndi04Wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'",
        ),
        'individuell_04_dauer' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['indi04Dauer'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'eval'             => array('tl_class' => 'w50 clr'),
            'sql'              => "char(1) NOT NULL default '0'",
        ),
    )
);


/**
 * DCA- Klasser der Tabelle tl_nlsh_garten_garten_data
 *
 * @package nlsh/nlsh_kleingartenverwaltung-bundle
 */

/**
 * Class tl_nlsh_garten_garten_data
 *
 * Enthält Funktionen einzelner Felder der Konfiguration
 */
class tl_nlsh_garten_garten_data extends Backend
{
    /**
     * Den Backenduser importieren
     *
     * Contao Core Funktion
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');

    }//end __construct()

    /**
     * Feld 'name' auf 'readonly' => true setzen
     *
     * Sollte es sich nicht um eine Neuanlage eines Gartens handeln,
     * soll so verhindert werden, die Nr zu ändern
     *
     * onload_callback des DataContainers
     *
     * @param \DataContainer $dc Contao- DataContainer- Objekt.
     *
     * @return void
     */
    public function nameReadonly(\DataContainer $dc)
    {
         // Neuenlage eines Garten kontrollieren
         // dazu den tstamp des Datensatzes heraussuchen.
        $tstamp = $this->Database->prepare('
                                        SELECT  `tstamp`
                                        FROM    `tl_nlsh_garten_garten_data`
                                        WHERE   `id` = ?')
                    ->execute($dc->id);

         // Wenn tstamp != '0', dann Nr nur lesbar.
        if ($tstamp->tstamp !== '0') {
            $GLOBALS['TL_DCA']['tl_nlsh_garten_garten_data']['fields']['nr']['eval'] = array(
                                                                            'readonly' => true,
                                                                            'tl_class' => 'w50',
            );
        }

    }//end nameReadonly()

    /**
     * Ist ein button_callback: Ermöglicht das Löschen eines Gartens nur in der höchsten Periode
     * und wenn er erst im vorhgerigen Jahr angelegt.
     *
     * @param array  $arrRow     The current row.
     * @param string $href       The url of the embedded link of the button.
     * @param string $label      Label text for the button.
     * @param string $title      Title value for the button.
     * @param string $icon       Url of the image for the button.
     * @param string $attributes Additional attributes for the button (fetched from the array key "attributes" in the DCA).
     * @param string $strTable   The name of the current table.
     *
     * @return var
     */
    public function bottomDelete(array $arrRow, string $href, string $label, string $title, string $icon, string $attributes, string $strTable)
    {
         // Höchstest Jahr abfragen.
        $modelTopStammdatenYear = NlshGartenVereinStammdatenModel::findAll(array('order' => '`jahr` DESC'));

         // Wenn höchstest Jahr, Kontrolle, ob im vorherigem Jahr angelegt.
        if ($modelTopStammdatenYear->id === $arrRow['pid']) {
             // Zuerst Abfrage Stammdaten zwei Jahre vorher, wegen ID.
            $modelStammdaten2YearsBefore = NlshGartenVereinStammdatenModel::findOneByJahr(($modelTopStammdatenYear->jahr) - 2);

             // Abfrage Garten zwei Jahre vorher.
            $modelGarten2YearsBefore = NlshGartenGartenDataModel::findBy(array('nr=?', 'pid=?'), array($arrRow['nr'], $modelStammdaten2YearsBefore->id));

             // Kontrolle, ob Garten nicht existierte!
            if ($modelGarten2YearsBefore === null) {
                 // Wenn nicht, Löschen ermöglichen.
                return '<a href="' . $this->addToUrl($href . '&amp;id=' . $arrRow['id']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . \Image::getHtml($icon, $label) . '</a> ';
            }

            // Ansonsten kein löschen möglich.
            $attributes = 'onclick="confirm(\'' . $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['cantDelete'][1] . '\');return false;Backend.getScrollOffset()"';
            $title      = $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['cantDelete'][1];

            return '<a href="' . $this->addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Contao\Image::getHtml(preg_replace('/\.svg/i', '_.svg', $icon)) . '</a> ';
        }

    }//end bottomDelete()

    /**
     * Auflistung der Gärten in der Übersicht erzeugen
     *
     * Child_record_callback des List
     *
     * @param array $arrRow Mit kompletten Daten des aktuell anzuzeigendem Gartens.
     *
     * @return string  html- Text für Auflistung der Gärten
     */
    public function listGarten(array $arrRow)
    {
         // Gartennummer und Gartennutzer.
        $line  = '<div>';
        $line .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGarten'];
        $line .= '<span style ="display:inline-block; width:21em; margin-left: 1em;">';
        $line .= $arrRow['nr'] . '</span>';

         // Kompletten Namen des Gartenbesitzers, oder Text, Garten nicht vergeben.
        if (empty($arrRow['name_komplett']) === false) {
            $line .= '<span style =" margin-left: 1em;">';
            $line .= $arrRow['name_komplett'] . '</span>';
        } else {
            $line .= '<span style ="margin-left: 1em;color:red;">';
            $line .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['nichtVergeben'];
            $line .= '</span>';
        }

         // Ende Gartennummer und Gartennutzer.
         // Beginn Tabelle, ob Strom/ Wasser abgerechnet wurde.
        $line .= '<span style = "float:right">';

        if (empty($arrRow['strom']) === false) {
            $line .= '<span style = "padding-right: 15px; color: green">';
            $line .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenStrom'];
            $line .= '<img src="bundles/nlshkleingartenverwaltung/check-circle.svg" width="16" height="16" ';
            $line .= 'title="' . $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenAltImgStrom'] . '" ';
            $line .= 'alt="' . $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenAltImgStrom'] . '" />';
            $line .= '</span>';
        } else {
            $line .= '<span style = "padding-right: 15px; color: red">';
            $line .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenStrom'];
            $line .= '<img src="bundles/nlshkleingartenverwaltung/x-circle.svg" width="16" height="16" ';
            $line .= 'title="' . $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenAltImgNoStrom'] . '" ';
            $line .= 'alt="' . $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenAltImgNoStrom'] . '" />';
            $line .= '</span>';
        }//end if

         // Anzeige, ob Wasser abgerechnet wurde.
        if (empty($arrRow['wasser']) === false) {
            $line .= '<span style = "padding-right: 15px; color: green">';
            $line .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenWasser'];
            $line .= '<img src="bundles/nlshkleingartenverwaltung/check-circle.svg" width="16" height="16" ';
            $line .= 'title="' . $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenAltImgWasser'] . '" ';
            $line .= ' alt="' . $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenAltImgWasser'] . '" />';
            $line .= '</span>';
        } else {
            $line .= '<span style = "padding-right: 15px; color: red">';
            $line .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenWasser'];
            $line .= '<img src="bundles/nlshkleingartenverwaltung/x-circle.svg" width="16" height="16" ';
            $line .= 'title="' . $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenAltImgNoWasser'] . '" ';
            $line .= ' alt="' . $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenAltImgNoWasser'] . '" />';
            $line .= '</span>';
        }//end if

        $line .= '</span>';
        $line .= '</div>';
        return($line);

    }//end listGarten()

    /**
     * Array mit den Namen, Vornamen aller Mitglieder der Mitgliedergruppe
     * des entsprechenden Jahres erzeugen
     *
     * Options_callback des nutzung_user_id- Felder
     *
     * @param \DataContainer $dc Contao DataContainer- Objekt.
     *
     * @return array         Array mit Namen, Vornamen
     */
    public function holeNamen(\DataContainer $dc)
    {
        $couples = array();
        $jahr    = array();
        $gruppe  = array();

         // Tabelle der Gartenbesitzer auslesen
         // zuerst das Jahr holen.
        $jahr = $this->Database->prepare('
                                    SELECT      `jahr`
                                    FROM        `tl_nlsh_garten_verein_stammdaten`
                                    WHERE       `id` = ?')
                     ->execute($dc->activeRecord->pid);

         // Jetzt die Mitgliedergruppe des Jahres.
        $gruppe = $this->Database->prepare('
                                    SELECT      `mitgliedergruppe_id`
                                    FROM        `tl_nlsh_garten_verein_stammdaten`
                                    WHERE       `jahr` = ?')
                     ->execute($jahr->jahr);

         // Jetzt die Namen der Mitgliedergruppe.
        $objCouples = $this->Database->query('
                                    SELECT      *
                                    FROM        `tl_member`
                                    ORDER BY    lastname, firstname ASC');

         // Jetzt die Namen und Vornamen der Mitgliedergruppe zusammenbasteln.
        while ($objCouples->next()) {
            if (strpos($objCouples->groups, '"' . $gruppe->mitgliedergruppe_id . '";') == true) {
                $k = $objCouples->id;
                $v = $objCouples->lastname;
                if ($objCouples->firstname) {
                    $v .= ', ' . $objCouples->firstname;
                }

                $couples[$k] = $v;
            }
        }

        return $couples;

    }//end holeNamen()

    /**
     * Speichern von 'Nachname, Vorname' in seperatem Datenbankfeld
     *
     * Speichern im nicht sichtbaren Feld name_komplett,
     *
     * wird zum suchen gebraucht
     *
     * save_callback des Feldes nutzung_user_id
     *
     * @param string         $field ID des Gartennutzers, oder nichts.
     * @param \DataContainer $dc    Contao DataContainer- Objekt.
     *
     * @return string        ID des Gartennutzers, oder nichts
     */
    public function saveNameKomplett(string $field, \DataContainer $dc)
    {
        if ($field !== '') {
            $nameKomplett = $this->Database->prepare('SELECT * FROM `tl_member` WHERE `id` = ?')
                        ->execute($field);

            $nameKomplett = $nameKomplett->lastname . ', ' . $nameKomplett->firstname;

            $speichern = $this->Database->prepare('
                                    UPDATE      `tl_nlsh_garten_garten_data`
                                    SET         `name_komplett` = ?
                                    WHERE        tl_nlsh_garten_garten_data.`id` = ?')
                        ->execute($nameKomplett, $dc->id);
        } else {
            $speichern = $this->Database->prepare("
                                    UPDATE      `tl_nlsh_garten_garten_data`
                                    SET         `name_komplett` = ''
                                    WHERE       tl_nlsh_garten_garten_data.`id` = ?")
                        ->execute($dc->id);
        }

        return $field;

    }//end saveNameKomplett()

    /**
     * Vorhandene Jahresverbrauchsdaten zum Vergleich ausgeben
     *
     * Input_field_callback
     *
     * @param \DataContainer $dc Contao DataContainer- Objekt.
     *
     * @return string        HTML- Text der Vorjahre
     */
    public function getOutYears(\DataContainer $dc)
    {
         $objJahre = $this->Database->prepare('
                                     SELECT      `pid` ,
                                         (SELECT     `jahr`
                                          FROM       `tl_nlsh_garten_verein_stammdaten`
                                          WHERE      `id` = tl_nlsh_garten_garten_data.pid
                                     )
                                                 `jahr`,
                                                 `strom` ,
                                                 `wasser`
                                     FROM        `tl_nlsh_garten_garten_data`
                                     WHERE       (`nr` = ?)
                                             AND (`pid` != ?)
                                     ORDER BY    `pid` DESC')
                     ->execute($dc->activeRecord->nr, $dc->activeRecord->pid);

         $actYear = $this->Database->prepare('
                                     SELECT      `jahr`
                                     FROM        `tl_nlsh_garten_verein_stammdaten` WHERE `id` = ?')
                    ->execute($dc->activeRecord->pid);

         $arrOutYears[] = array(
             'jahr'    => $actYear->jahr,
             'wasser'  => $dc->activeRecord->wasser,
             'strom'   => $dc->activeRecord->strom,
             'tdClass' => 'style = "text-align: right; color:red;"',
         );

         while ($objJahre->next()) {
             $arrOutYears[] = array(
                 'jahr'    => $objJahre->jahr,
                 'wasser'  => $objJahre->wasser,
                 'strom'   => $objJahre->strom,
                 'tdClass' => 'style = "text-align: right;"',
             );
         }

          // Sortieren lassen.
         rsort($arrOutYears);

          // Ausgabe starten für Verbrauch vorhandene Jahre.
         $getOut  = '<div style="float:left; width:50%; margin: 0px 0 0px 2%;">';
         $getOut .= '<span style="font-weight:bold; display: block; margin-top:1em;">';
         $getOut .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['vorjahreswerte'] . '</span>';
         $getOut .= '<table style = "margin:10px 2px; text-align: right;">';
         $getOut .= '<colgroup><col /><col style = "width:2em"/><col /><col style = "width:2em"/><col /></colgroup>';
         $getOut .= '<thead><tr><th>';
         $getOut .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['jahr'];
         $getOut .= '</th><th>&nbsp;</th><th>';
         $getOut .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['verbrauchstrom'] . '</th>';
         $getOut .= '<th>&nbsp;</th><th>';
         $getOut .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['verbrauchwasser'];
         $getOut .= '</th></tr></thead>';
         $getOut .= '<tbody><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';

         $count = count($arrOutYears);
         for ($i = 0; $i < $count; $i++) {
             $getOut .= '<tr><td ' . $arrOutYears[$i]['tdClass'] . '>';
             $getOut .= $arrOutYears[$i]['jahr'] . '</td><td>&nbsp;</td>';
             $getOut .= '<td ' . $arrOutYears[$i]['tdClass'] . '>';
             $getOut .= number_format($arrOutYears[$i]['strom'], 2, ',', '.') . ' ';
             $getOut .= $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['strom_einheit'];
             $getOut .= '</td><td>&nbsp;</td>';
             $getOut .= '<td ' . $arrOutYears[$i]['tdClass'] . '>';
             $getOut .= number_format($arrOutYears[$i]['wasser'], 2, ',', '.') . ' ';
             $getOut .= $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['wasser_einheit'];
             $getOut .= '</td></tr>';
         }

         $getOut .= '</tbody>';
         $getOut .= '</table>';
         $getOut .= '</div>';

         return $getOut;

    }//end getOutYears()

}//end class
