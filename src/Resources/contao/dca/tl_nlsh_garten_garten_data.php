<?php


/**
 * Erweiterung des tl_nlsh_garten_garten_data DCA`s
 *
 * @copyright Nils Heinold (c) 2017
 * @author    Nils Heinold
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */


/**
 * Table tl_nlsh_garten_garten_data
 */
$GLOBALS['TL_DCA']['tl_nlsh_garten_garten_data'] = array
(

        // Config
       'config' => array
       (
        'dataContainer'     => 'Table',
        'ptable'            => 'tl_nlsh_garten_verein_stammdaten',
        'enableVersioning'  => TRUE,
        'onload_callback'   => array(
                                    array(
                                        'tl_nlsh_garten_garten_data',
                                        'loeschGartenNeuerGarten'
                                    ),
                                    array(
                                        'tl_nlsh_garten_garten_data',
                                        'nameReadonly'
                                    )
        ),
        'sql' => array
        (
            'keys'          => array(
                                    'id'        => 'primary',
                                    'pid'       => 'index'
            )
        )
    ),

     // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('round(nr)'),
            'flag'                    => 1,
            'disableGrouping'         => 'true',
            'panelLayout'             => 'search,sort,filter,limit',
            'headerFields'            => array(
                                            'jahr',
                                            'name',
                                            'vereinsvorsitzender'
            ),
            'child_record_callback'   => array(
                                           'tl_nlsh_garten_garten_data',
                                           'listGarten'
            )
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
                'label'               => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
        )
    ),

     // Edit
    'edit' => array
    (
        'buttons_callback' => array()
    ),

     // Palettes
    'palettes' => array
    (
        '__selector__'      => array(''),
        'default'           => '{gartennummer_legend},nr;
                               {groesse_legend},grosse;
                               {verbrauchsdaten_legend},strom,wasser,abrechnungVorjahre;
                               {gartenbesitzer_legend},nutzung_user_id;
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

     // Subpalettes
    'subpalettes' => array
    (
        ''                     => ''
    ),

     // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'              => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'sql'              => "int(10) unsigned NOT NULL default '0'",
        ),
        'tstamp' => array
        (
            'sql'              => "int(10) unsigned NOT NULL default '0'"
        ),
        'nr' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['nr'],
            'exclude'          => TRUE,
            'inputType'        => 'text',
            'search'           => TRUE,
            'eval'             => array(
                                     'mandatory' => TRUE,
                                     'maxlength' => 20,
                                     'unique' => TRUE
            ),
            'sql'              => "varchar(20) NOT NULL default ''"
        ),
        'grosse' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['grosse'],
            'exclude'          => TRUE,
            'inputType'        => 'text',
            'eval'             => array('mandatory' => TRUE, 'rgxp' => 'digit'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'strom' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['strom'],
            'exclude'          => TRUE,
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50', 'rgxp' => 'digit'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'wasser' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['wasser'],
            'exclude'          => TRUE,
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50', 'rgxp' => 'digit'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'abrechnungVorjahre' => array
        (
            'input_field_callback'  => array(
                                          'tl_nlsh_garten_garten_data',
                                          'getOutYears'
            ),
        ),
        'nutzung_user_id' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['nutzung_user_id'],
            'exclude'          => TRUE,
            'inputType'        => 'select',
            'options_callback' => array(
                                     'tl_nlsh_garten_garten_data',
                                     'holeNamen'
            ),
            'save_callback'    => array(
                                   array(
                                     'tl_nlsh_garten_garten_data',
                                     'saveNameKomplett'
                                   )
            ),
            'eval'             => array(
                                     'alwaysSave' => TRUE,
                                     'includeBlankOption' => TRUE,
                                     'blankOptionLabel'   => $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['nicht_vergeben']
            ),
            'sql'              => "int(11) NOT NULL default '0'"
        ),
        'name_komplett' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['name_komplett'],
            'exclude'          => TRUE,
            'inputType'        => 'text',
            'search'           => TRUE,
            'sql'              => "varchar(512) NOT NULL default ''"
        ),
        'pacht_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['pacht_ja_nein'],
            'inputType'        => 'checkbox',
            'exclude'          => TRUE,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'"
        ),
        'beitrag_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['beitrag_ja_nein'],
            'inputType'        => 'checkbox',
            'exclude'          => TRUE,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'"
        ),
        'individuell_01_gartenstamm_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['individuell_01_gartenstamm_ja_nein'],
            'inputType'        => 'checkbox',
            'exclude'          => TRUE,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'"
        ),
        'individuell_02_gartenstamm_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['individuell_02_gartenstamm_ja_nein'],
            'inputType'        => 'checkbox',
            'exclude'          => TRUE,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'"
        ),
        'individuell_03_gartenstamm_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['individuell_03_gartenstamm_ja_nein'],
            'inputType'        => 'checkbox',
            'exclude'          => TRUE,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'"
        ),
        'individuell_04_gartenstamm_ja_nein' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['individuell_04_gartenstamm_ja_nein'],
            'inputType'        => 'checkbox',
            'exclude'          => TRUE,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '1'"
        ),
        'abrechnung_garten_individuell_01_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnung_garten_individuell_01_name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "varchar(80) NOT NULL default ''"
        ),
        'abrechnung_garten_individuell_01_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnung_garten_individuell_01_wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'individuell_01_dauer' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['individuell_01_dauer'],
            'inputType'        => 'checkbox',
            'exclude'          => TRUE,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '0'"
        ),
        'abrechnung_garten_individuell_02_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnung_garten_individuell_02_name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50 clr'),
            'sql'              => "varchar(80) NOT NULL default ''"
        ),
        'abrechnung_garten_individuell_02_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnung_garten_individuell_02_wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'individuell_02_dauer' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['individuell_02_dauer'],
            'inputType'        => 'checkbox',
            'exclude'          => TRUE,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '0'"
        ),
        'abrechnung_garten_individuell_03_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnung_garten_individuell_03_name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50 clr'),
            'sql'              => "varchar(80) NOT NULL default ''"
        ),
        'abrechnung_garten_individuell_03_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnung_garten_individuell_03_wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'individuell_03_dauer' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['individuell_03_dauer'],
            'inputType'        => 'checkbox',
            'exclude'          => TRUE,
            'eval'             => array('tl_class' => 'w50 '),
            'sql'              => "char(1) NOT NULL default '0'"
        ),
        'abrechnung_garten_individuell_04_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnung_garten_individuell_04_name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50 clr'),
            'sql'              => "varchar(80) NOT NULL default ''"
        ),
        'abrechnung_garten_individuell_04_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['abrechnung_garten_individuell_04_wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'individuell_04_dauer' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['individuell_04_dauer'],
            'inputType'        => 'checkbox',
            'exclude'          => TRUE,
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "char(1) NOT NULL default '0'"
        ),
    )
);


/**
 * DCA- Klasser der Tabelle tl_nlsh_garten_garten_data
 *
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 */

/**
 * Class tl_nlsh_garten_garten_data
 *
 * Enthält Funktionen einzelner Felder der Konfiguration
 *
 * @copyright Nils Heinold (c) 2017
 * @author    Nils Heinold
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */
class tl_nlsh_garten_garten_data extends Backend
{


    /**
     * Den Backenduser importieren
     *
     * Contao Core Funktion
     */
    public function __construct() {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }


    /**
     * Feld 'name' auf 'readonly' => TRUE setzen
     *
     * Sollte es sich nicht um eine Neuanlage eines Gartens handeln,
     * soll so verhindert werden, die Nr zu ändern
     *
     * onload_callback des DataContainers
     *
     * @param \DataContainer  $dc Contao- DataContainer- Objekt
     *
     * @return void
     */
    public function nameReadonly(\DataContainer $dc) {
         // Neuenlage eines Garten kontrollieren
         // dazu den tstamp des Datensatzes heraussuchen
        $tstamp = $this->Database->prepare("
                                        SELECT  `tstamp`
                                        FROM    `tl_nlsh_garten_garten_data`
                                        WHERE   `id` = ?")
                    ->execute($dc->id);

         // wenn tstamp vorhanden, dann Nr nicht veränderbar
        if ($tstamp->tstamp == TRUE) {
            $GLOBALS['TL_DCA']['tl_nlsh_garten_garten_data']['fields']['nr']['eval'] = array(
                                                                            'readonly' => TRUE
            );
        }
    }


    /**
     * DCA- Aufbau korrigieren
     *
     * - Löschsymbol anzeigen ja/nein
     *
     *      da das Löschen eines Gartens nur im höchsten Jahr möglich sein soll,
     *      wird das Symbol zum löschen auch nur dann angezeigt
     *
     * - Neuanlage eines Gartens erlauben
     *
     *      Die Neuanlage eines Gartens ist ebenfalls nur im höchsten Jahr möglich,
     *      ansonsten Fehlermeldung und Abbruch
     *
     * onload_callback des DataContainers
     *
     * @param   \DataContainer  $dc  Contao DataContainer- Objekt
     *
     * @return  void
     */
    public function loeschGartenNeuerGarten(\DataContainer $dc) {
         // Kontrolle, ob auch nur in der Übersicht der tl_nlsh_garten_garten
        if ($this->Input->get('act') != 'edit') {
            $aktJahr = 0;
            $topJahr = 0;

             // das Jahr holen
            $aktJahr = $this->Database->prepare("
                                    SELECT      `jahr`
                                    FROM        `tl_nlsh_garten_verein_stammdaten`
                                    WHERE `id` = ?")
                    ->execute($dc->id);

            $aktJahr = $aktJahr->jahr;

             // jetzt das höchste Jahr der Gärten ermittel
            $topJahr = $this->Database->query('
                                    SELECT      `jahr`
                                    FROM        `tl_nlsh_garten_verein_stammdaten`
                                    ORDER BY    `jahr` DESC'
            );

            $topJahr = $topJahr->jahr;

             // wenn gleich, dann Löschsymbol anzeigen
            if ($aktJahr == $topJahr) {
                $GLOBALS['TL_DCA']['tl_nlsh_garten_garten_data']['list']['operations']['delete'] = array
                 (
                    'label'      => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['delete'],
                    'href'       => 'act=delete',
                    'icon'       => 'delete.gif',
                    'attributes' => 'onclick="if (!confirm(\'' .
                                                  $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] .
                                                  '\')) return false; Backend.getScrollOffset();"'
                );
            };

             // damit die Reihenfolge stimmt, jetzt die Ausgabe des Info- Icons
            $GLOBALS['TL_DCA']['tl_nlsh_garten_garten_data']['list']['operations']['show'] = array
            (
                'label'          => &$GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['show'],
                'href'           => 'act=show',
                'icon'           => 'show.gif'
            );
        }

         // Jetzt Neuanlage verhindern
         // wenn das aktuelle Jahr kleiner als das höchste Jahr ist
        if ($this->Input->get('act') === 'create' && $aktJahr < $topJahr) {
            $this->addErrorMessage($GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['neuanlage_nicht']);
            $this->redirect("contao/main.php?do=Garten_garten&table=tl_nlsh_garten_garten_data&amp;id=" . $dc->id);
        }
    }


    /**
     * Auflistung der Gärten in der Übersicht erzeugen
     *
     * Child_record_callback des List
     *
     * @param  array   $arrRow Mit kompletten Daten des aktuell anzuzeigendem Gartens
     *
     * @return string  html- Text für Auflistung der Gärten
     */
    public function listGarten(array $arrRow) {
         // Gartennummer
        $line  = '<span style = "float:left;">';
        $line .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGarten'];
        $line .= '</span>';
        $line .= '<strong><span style ="width:20em; float:left;margin-left: 1em;">';
        $line .= $arrRow['nr'] . '</span>&nbsp;&nbsp;';

         // kompletten Namen des Gartenbesitzers, oder Text, Garten nicht vergeben
        if ($arrRow['name_komplett'] == TRUE) {
            $line .= '<span style ="float:left;margin-left: 1em;">';
            $line .= $arrRow['name_komplett'] . '</span>';
        } else {
            $line .= '<span style ="float:left;margin-left: 1em;color:red;">';
            $line .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['nicht_vergeben'];
            $line .= '</span>';
        }

        $line .= '</strong>';

         // Anzeige, ob Strom oder Wasser abgerechnet wurden
        if (($arrRow['strom'] == TRUE) || ( $arrRow['wasser'])) {
            $line .= '<span style = "float: right; padding-right: 15px;">';
            $line .= '<img src="bundles/nlshkleingartenverwaltung/accept.png"';
            $line .= ' width="16" height="16" title="';
            $line .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenAltImg'] . '"';
            $line .= ' alt="';
            $line .= $GLOBALS['TL_LANG']['tl_nlsh_garten_garten_data']['listGartenAltImg'];
            $line .= '" /></span>';
        }

        return($line);
    }


    /**
     * Array mit den Namen, Vornamen aller Mitglieder der Mitgliedergruppe
     * des entsprechenden Jahres erzeugen
     *
     * Options_callback des nutzung_user_id- Felder
     *
     * @param   \DataContainer  $dc Contao DataContainer- Objekt
     *
     * @return  array           Array mit Namen, Vornamen
     */
    public function holeNamen(\DataContainer $dc) {
        $couples = array();
        $jahr    = array();
         $gruppe  = array();

         // Tabelle der Gartenbesitzer auslesen
         // zuerst das Jahr holen
        $jahr = $this->Database->prepare("
                                    SELECT      `jahr`
                                    FROM        `tl_nlsh_garten_verein_stammdaten`
                                    WHERE       `id` = ?")
                     ->execute($dc->activeRecord->pid);

         // jetzt die Mitgliedergruppe des Jahres
        $gruppe = $this->Database->prepare("
                                    SELECT      `mitgliedergruppe_id`
                                    FROM        `tl_nlsh_garten_verein_stammdaten`
                                    WHERE       `jahr` = ?")
                     ->execute($jahr->jahr);

         // jetzt die Namen der Mitgliedergruppe
        $objCouples = $this->Database->query('
                                    SELECT      *
                                    FROM        `tl_member`
                                    ORDER BY    lastname, firstname ASC');

         // jetzt die Namen und Vornamen der Mitgliedergruppe zusammenbasteln
        while ($objCouples->next()) {
            if (strpos($objCouples->groups, '"' . $gruppe->mitgliedergruppe_id . '";') == TRUE) {
                {
                    $k = $objCouples->id;
                    $v = $objCouples->lastname;
                     if ($objCouples->firstname) {
                        $v .= ', ' . $objCouples->firstname;
                     }

                    $couples[$k] = $v;
                }
            }
        }

        return $couples;
    }


    /**
     * Speichern von 'Nachname, Vorname' in seperatem Dantenbankfeld
     *
     * Speichern im nicht sichtbaren Feld name_komplett,
     *
     * wird zum suchen gebraucht
     *
     * save_callback des Feldes nutzung_user_id
     *
     * @param  string          $field  ID des Gartennutzers, oder nichts
     * @param  \DataContainer  $dc     Contao DataContainer- Objekt
     *
     * @return string          ID des Gartennutzers, oder nichts
     */
    public function saveNameKomplett($field, \DataContainer $dc) {
         if ($field == TRUE) {
             $nameKomplett = $this->Database->prepare("SELECT * FROM `tl_member` WHERE `id` = ?")
                         ->execute($field);

             $nameKomplett = $nameKomplett->lastname . ', ' . $nameKomplett->firstname;

             $speichern = $this->Database->prepare("
                                     UPDATE      `tl_nlsh_garten_garten_data`
                                     SET         `name_komplett` = ?
                                     WHERE        tl_nlsh_garten_garten_data.`id` = ?")
                         ->execute($nameKomplett, $dc->id);
         } else {
             $speichern = $this->Database->prepare("
                                     UPDATE      `tl_nlsh_garten_garten_data`
                                     SET         `name_komplett` = ''
                                     WHERE       tl_nlsh_garten_garten_data.`id` = ?")
                         ->execute($dc->id);
         }

     return $field;
    }


    /**
     * Vorhandene Jahresverbrauchsdaten zum Vergleich ausgeben
     *
     * Input_field_callback
     *
     * @param  \DataContainer  $dc Contao DataContainer- Objekt
     *
     * @return string          HTML- Text der Vorjahre
     */
    public function getOutYears(\DataContainer $dc) {
         $objJahre = $this->Database->prepare("
                                     SELECT      `pid` ,
                                         (SELECT     jahr
                                          FROM       tl_nlsh_garten_verein_stammdaten
                                          WHERE      id = tl_nlsh_garten_garten_data.pid
                                     )
                                                 `jahr`,
                                                 `strom` ,
                                                 `wasser`
                                     FROM        `tl_nlsh_garten_garten_data`
                                     WHERE       (`nr` = ?)
                                             AND (pid != ?)
                                     ORDER BY    `pid` DESC")
                     ->execute($dc->activeRecord->nr, $dc->activeRecord->pid);

         $actYear = $this->Database->prepare("
                                     SELECT      jahr
                                     FROM        tl_nlsh_garten_verein_stammdaten WHERE id = ?")
                    ->execute($dc->activeRecord->pid);

         $arrOutYears[] = array
                       (
                         jahr    => $actYear->jahr,
                         wasser  => $dc->activeRecord->wasser,
                         strom   => $dc->activeRecord->strom,
                         tdClass => 'style = "text-align: right; color:red;"'
                       );

         while ($objJahre->next()) {
             $arrOutYears[] = array
                           (
                             jahr    => $objJahre->jahr,
                             wasser  => $objJahre->wasser,
                             strom   => $objJahre->strom,
                             tdClass => 'style = "text-align: right;"'
                           );
         }

          // sortieren lassen
         rsort($arrOutYears);

          // Ausgabe starten für Verbrauch vorhandene Jahre
         $getOut  = '<div style="float:left; width:50%">';
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

     }
}