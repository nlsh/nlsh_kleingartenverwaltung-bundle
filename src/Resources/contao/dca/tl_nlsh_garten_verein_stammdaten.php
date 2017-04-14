<?php


/**
 * Erweiterung des tl_nlsh_garten_verein_stammdaten DCA`s
 *
 * @copyright Nils Heinold (c) 2017
 * @author    Nils Heinold
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */


/**
 * Table tl_nlsh_garten_verein_stammdaten
 */
$GLOBALS['TL_DCA']['tl_nlsh_garten_verein_stammdaten'] = array
(

     // Config
    'config' => array
    (
        'dataContainer'        => 'Table',
        'enableVersioning'     => TRUE,
        'ctable'               => array('tl_nlsh_garten_garten_data'),
        'onload_callback'      => array(
                                     array(
                                         'tl_nlsh_garten_verein_stammdaten',
                                         'neuesJahr')),
        'sql'                  => array('keys' => array('id' => 'primary'))
    ),

     // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'             => 1,
            'fields'           => array('jahr'),
            'disableGrouping'  => TRUE,
            'panelLayout'      => 'search,sort,filter,limit',
            'flag'             => 12,
        ),
        'label' => array
        (
            'fields'           => array('jahr'),
            'format'           => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['stammdaten_label']
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'        => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'         => 'act=select',
                'class'        => 'header_edit_all',
                'attributes'   => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'        => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['edit'],
                'href'         => 'table=tl_nlsh_garten_garten_data',
                'icon'         => 'edit.gif'
            ),
            'show' => array
            (
                'label'        => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['show'],
                'href'         => 'act=show',
                'icon'         => 'show.gif'
            )
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
        '__selector__'     => array(''),
        'default'          => 'jahr;
                              {Adresse_legend},name,strasse,plzort;
                              {Kommunikation_legend},telefon,email;
                              {Bank_legend:hide},bankname,konto,blz,iban,bic;
                              {Pacht_Beitrag_legend},pacht,beitrag;
                              {Strom_Wasser_legend},strom,strom_grundpreis,wasser,wasser_grundpreis;
                              {Abrechnung_stammdaten_individuell_legend:hide},
                                abrechnung_stammdaten_individuell_01_name,
                                abrechnung_stammdaten_individuell_01_wert,
                                abrechnung_stammdaten_individuell_02_name,
                                abrechnung_stammdaten_individuell_02_wert,
                                abrechnung_stammdaten_individuell_03_name,
                                abrechnung_stammdaten_individuell_03_wert,
                                abrechnung_stammdaten_individuell_04_name,
                                abrechnung_stammdaten_individuell_04_wert;
                              {Landgrosse_legend},landgrosse;
                              {Mitgliedergruppe_id_legend},mitgliedergruppe_id;
                              {Rechtliches_legend},
                                vereinsvorsitzender,
                                finanzamt,steuernummer,
                                amtsgericht,
                                amtsgericht_nummer;'
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
        'tstamp' => array
        (
            'sql'              => "int(10) unsigned NOT NULL default '0'"
        ),
        'jahr' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['jahr'],
            'inputType'        => 'select',
            'options_callback' => array(
                                    'tl_nlsh_garten_verein_stammdaten',
                                    'optionStammdatenjahr'
            ),
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'maxlength' => 4,
                                    'rgxp'      => 'digit',
                                    'unique'    => TRUE,
            ),
                'sql'              => "varchar(4) NOT NULL default ''"
        ),
        'name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['name'],
            'inputType'        => 'text',
            'eval'             => array('mandatory' => TRUE, 'maxlength' => 80,'tl_class' => 'long'),
            'sql'              => "varchar(80) NOT NULL default ''"
        ),
        'strasse' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['strasse'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'maxlength' => 50,
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "varchar(50) NOT NULL default ''"
        ),
    'plzort' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['plzort'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'maxlength' => 70,
                                    'rgxp'      => 'alnum',
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "varchar(70) NOT NULL default ''"
        ),
        'telefon' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['telefon'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => FALSE,
                                    'maxlength' => 50,
                                    'rgxp'      => 'phone',
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "varchar(50) NOT NULL default ''"
        ),
        'email' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['email'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => FALSE,
                                    'maxlength' => 60,
                                    'rgxp'      => 'email',
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "varchar(60) NOT NULL default ''"
        ),
        'bankname' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['bankname'],
            'inputType'        => 'text',
            'eval'             => array('mandatory' => TRUE, 'maxlength' => 50),
            'sql'              => "varchar(50) NOT NULL default ''"
        ),
        'konto' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['konto'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'maxlength' => 50,
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "varchar(50) NOT NULL default ''"
        ),
        'blz' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['blz'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'maxlength' => 50,
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "varchar(50) NOT NULL default ''"
        ),
        'iban' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['iban'],
            'inputType'        => 'text',
            'eval'             => array('maxlength' => 50, 'tl_class' => 'w50'),
            'sql'              => "varchar(50) NOT NULL default ''"
        ),
        'bic' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['bic'],
            'inputType'        => 'text',
            'eval'             => array('maxlength' => 50, 'tl_class' => 'w50'),
            'sql'              => "varchar(50) NOT NULL default ''"
        ),
        'pacht' => array
        (
        'label'                => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['pacht'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'rgxp'      => 'digit',
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "double NOT NULL default '0'"
        ),
        'beitrag' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['beitrag'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'rgxp'      => 'digit',
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "double NOT NULL default '0'"
        ),
        'strom' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['strom'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'rgxp'      => 'digit',
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "double NOT NULL default '0'"
        ),
        'strom_grundpreis' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['strom_grundpreis'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'rgxp'      => 'digit',
                                    'tl_class'   => 'w50'
            ),
            'sql'              => "double NOT NULL default '0'"
        ),
        'wasser' => array
            (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['wasser'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'rgxp'      => 'digit',
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "double NOT NULL default '0'"
        ),
        'wasser_grundpreis' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['wasser_grundpreis'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'rgxp'      => 'digit',
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "double NOT NULL default '0'"
        ),
        'landgrosse' => array
        (   'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['landgrosse'],
            'inputType'         => 'text',
            'eval'              => array('mandatory' => TRUE, 'rgxp' => 'digit'),
            'sql'               => "double NOT NULL default '0'"
        ),
        'abrechnung_stammdaten_individuell_01_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnung_stammdaten_individuell_01_name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "varchar(80) NOT NULL default ''"
        ),
        'abrechnung_stammdaten_individuell_01_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnung_stammdaten_individuell_01_wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'abrechnung_stammdaten_individuell_02_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnung_stammdaten_individuell_02_name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "varchar(80) NOT NULL default ''"
        ),
        'abrechnung_stammdaten_individuell_02_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnung_stammdaten_individuell_02_wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'abrechnung_stammdaten_individuell_03_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnung_stammdaten_individuell_03_name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "varchar(80) NOT NULL default ''"
        ),
        'abrechnung_stammdaten_individuell_03_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnung_stammdaten_individuell_03_wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'abrechnung_stammdaten_individuell_04_name' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnung_stammdaten_individuell_04_name'],
            'inputType'        => 'text',
            'eval'             => array('tl_class' => 'w50'),
            'sql'              => "varchar(80) NOT NULL default ''"
        ),
        'abrechnung_stammdaten_individuell_04_wert' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnung_stammdaten_individuell_04_wert'],
            'inputType'        => 'text',
            'eval'             => array('rgxp' => 'digit', 'tl_class' => 'w50'),
            'sql'              => "double NOT NULL default '0'"
        ),
        'mitgliedergruppe_id' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['mitgliedergruppe_id'],
            'inputType'        => 'select',
            'foreignKey'       => 'tl_member_group.name',
            'eval'             => array('mandatory' => TRUE, 'includeBlankOption' => TRUE ),
            'sql'              => "int(11) NOT NULL default '0'"
        ),
        'vereinsvorsitzender' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['vereinsvorsitzender'],
            'inputType'        => 'text',
            'eval'             => array(
                                    'mandatory' => TRUE,
                                    'maxlength' => 30,
                                    'tl_class'  => 'w50'
            ),
            'sql'              => "varchar(30) NOT NULL default ''"
        ),
        'finanzamt' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['finanzamt'],
            'inputType'        => 'text',
            'eval'             => array('maxlength' => 30, 'tl_class' => 'w50'),
            'sql'              => "varchar(30) NOT NULL default ''"
        ),
        'steuernummer' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['steuernummer'],
            'inputType'        => 'text',
            'eval'             => array('maxlength' => 30, 'tl_class' => 'w50'),
            'sql'              => "varchar(15) NOT NULL default ''"
        ),
        'amtsgericht' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['amtsgericht'],
            'inputType'        => 'text',
            'eval'             => array('maxlength' => 30, 'tl_class' => 'w50'),
            'sql'              => "varchar(30) NOT NULL default ''"
        ),
        'amtsgericht_nummer' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['amtsgericht_nummer'],
            'inputType'        => 'text',
            'eval'             => array('maxlength' => 15, 'tl_class' => 'w50'),
            'sql'              => "varchar(15) NOT NULL default ''"
        )
    )
);


/**
 * DCA- Klasser der Tabelle tl_nlsh_garten_verein_stammdaten
 *
 * @package   nlshKleingartenverwaltung
 */

/**
 * Class tl_nlsh_garten_verein_stammdaten
 *
 * Enthält Funktionen einzelner Felder der Konfiguration
 *
 * @copyright Nils Heinold (c) 2013
 * @author    Nils Heinold
 * @package   nlshKleingartenverwaltung
 * @link      https://github.com/nlsh/nlsh_Kleingartenverwaltung
 * @license   LGPL
 */
class tl_nlsh_garten_verein_stammdaten extends Backend{


    /**
     * Den Backenduser importieren
     *
     * Contao- Funktion
     */
    public function __construct() {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }


    /**
     * Daten für Neuanlage eines Jahres vortragen
     *
     * Beim Anlegen eines neuen Jahres die Daten vortragen,
     * aber nur dann, wenn es nicht das erste Jahr ist
     *
     * onload_ callback des DataContainers
     *
     * @param   \DataContainer  $dc Contao- DataContainer- Objekt
     *
     * @return  void
     */
    public function neuesJahr(\DataContainer $dc) {
         // neu angelegten Datensatz holen
        $newStammdatenJahr = NlshGartenVereinStammdatenModel::findOneBy('id', $dc->id);

         // Neuanlage, wenn tstamp = 0, deshalb nur dann weiter
        if ($newStammdatenJahr->tstamp == FALSE) {
             // Anzahl der Datensätze herausfinden
            $arrAnz = $this->Database->execute('
                                            SELECT  `id`
                                            FROM    `tl_nlsh_garten_verein_stammdaten`'
            );

            $arrAnz = $arrAnz->numRows;

             // Wenn mehr als ein Datensatz,
             // dann vorbelegen des neuen Jahres mit den Daten des alten Jahr
            if (($arrAnz > 1) && ($dc->id == TRUE)) {
                 // Daten des letzen Jahres auslesen
                $lastStammdatenJahr = Contao\NlshGartenVereinStammdatenModel::findAll(array(
                                                                    'order' => '`jahr` DESC')
                );

                 // und Daten übernehmen
                $newStammdatenJahr->jahr                 = $lastStammdatenJahr->jahr + 1;
                $newStammdatenJahr->tstamp               = time();
                $newStammdatenJahr->name                 = $lastStammdatenJahr->name;
                $newStammdatenJahr->vereinsvorsitzender  = $lastStammdatenJahr->vereinsvorsitzender;
                $newStammdatenJahr->strasse              = $lastStammdatenJahr->strasse;
                $newStammdatenJahr->plzort               = $lastStammdatenJahr->plzort;
                $newStammdatenJahr->telefon              = $lastStammdatenJahr->telefon;
                $newStammdatenJahr->email                = $lastStammdatenJahr->email;
                $newStammdatenJahr->bankname             = $lastStammdatenJahr->bankname;
                $newStammdatenJahr->konto                = $lastStammdatenJahr->konto;
                $newStammdatenJahr->blz                  = $lastStammdatenJahr->blz;
                $newStammdatenJahr->iban                 = $lastStammdatenJahr->iban;
                $newStammdatenJahr->bic                  = $lastStammdatenJahr->bic;
                $newStammdatenJahr->pacht                = $lastStammdatenJahr->pacht;
                $newStammdatenJahr->beitrag              = $lastStammdatenJahr->beitrag;
                $newStammdatenJahr->strom                = $lastStammdatenJahr->strom;
                $newStammdatenJahr->strom_grundpreis     = $lastStammdatenJahr->strom_grundpreis;
                $newStammdatenJahr->wasser               = $lastStammdatenJahr->wasser;
                $newStammdatenJahr->wasser_grundpreis    = $lastStammdatenJahr->wasser_grundpreis;
                $newStammdatenJahr->landgrosse           = $lastStammdatenJahr->landgrosse;
                $newStammdatenJahr->mitgliedergruppe_id  = $lastStammdatenJahr->mitgliedergruppe_id;
                $newStammdatenJahr->finanzamt            = $lastStammdatenJahr->finanzamt;
                $newStammdatenJahr->steuernummer         = $lastStammdatenJahr->steuernummer;
                $newStammdatenJahr->amtsgericht          = $lastStammdatenJahr->amtsgericht;
                $newStammdatenJahr->amtsgericht_nummer   = $lastStammdatenJahr->amtsgericht_nummer;

                 // und sichern
                $newStammdatenJahr->save();

                 // jetzt auch die Gärten vortragen
                 // jetzt alle Gärten des Vorjahres auslesen
                $garten = Contao\NlshGartenGartenDataModel::findByPid($lastStammdatenJahr->id);

                 // jetzt speichern
                while ($garten->next()) {
                    $tempNewGarten = Contao\NlshGartenGartenDataModel::findOneBy('id', $garten->id);
                    $tempNewGarten = clone $tempNewGarten;

                     // wenn dauerhaft, dann übernehmen
                    if ($garten->individuell_01_dauer == FALSE) {
                        $tempNewGarten->abrechnung_garten_individuell_01_name = '';
                        $tempNewGarten->abrechnung_garten_individuell_01_wert = '';
                    }

                    if ($garten->individuell_02_dauer == FALSE) {
                        $tempNewGarten->abrechnung_garten_individuell_02_name = '';
                        $tempNewGarten->abrechnung_garten_individuell_02_wert = '';
                    }

                    if ($garten->individuell_03_dauer == FALSE) {
                        $tempNewGarten->abrechnung_garten_individuell_03_name = '';
                        $tempNewGarten->abrechnung_garten_individuell_03_wert = '';
                    }

                    if ($garten->individuell_04_dauer == FALSE) {
                        $tempNewGarten->abrechnung_garten_individuell_04_name = '';
                        $tempNewGarten->abrechnung_garten_individuell_04_wert = '';
                    }

                    $tempNewGarten->pid                                = $dc->id;
                    $tempNewGarten->tstamp                             = time();
                    $tempNewGarten->strom                              = 0;
                    $tempNewGarten->wasser                             = 0;
                    $tempNewGarten->pacht_ja_nein                      = 1;
                    $tempNewGarten->beitrag_ja_nein                    = 1;
                    $tempNewGarten->individuell_01_gartenstamm_ja_nein = 1;
                    $tempNewGarten->individuell_02_gartenstamm_ja_nein = 1;
                    $tempNewGarten->individuell_03_gartenstamm_ja_nein = 1;
                    $tempNewGarten->individuell_04_gartenstamm_ja_nein = 1;

                     // und speichern
                    $tempNewGarten->save();
                }
            }
        }
    }


    /**
     * Vorbelegung Stammdatenjahr
     *
     * Kontrolle, ob Stammdaten im aktuellen Jahr schon vorhanden sind
     *      <ul>
     *          <li>wenn ja, dann nur dieses wieder zurück</li>
     *          <li>wenn nein, Auswahl des Startjahres möglich
     *              ( jetziges Jahr, oder -5 Jahre) </li>
     *      </ul>
     *
     * options_callback des Feldes jahr
     *
     * @param  \DataContainer  $dc Contao- DataContainer- Objekt
     *
     * @return array          Array mit Jahr der Stammdaten
     */
    public function optionStammdatenjahr(\DataContainer $dc) {
         // Wenn Eintrag vorhanden, dann diesen wieder zurück
        $jahr = array();

        if ($dc->activeRecord->jahr == TRUE) {
            $jahr[] = $dc->activeRecord->jahr;
        } else {
             // Wenn keiner vorhanden, dann Kontrolle ob Stammdaten schon vorhanden
            $arrAnz = $this->Database
                           ->execute('
                                        SELECT  *
                                        FROM    `tl_nlsh_garten_verein_stammdaten`
                           ');

             // dummerweise ist immer mindestens ein Wert vorhanden,
             // wenn die Dateneingabe aufgerufen wird
             // daher nur wenn einer (der Erste) vorhanden, dann Jahr wählbar
             // else brauch ich nicht, da wenn mehr als zwei Einträge,
             //                        schlägt der onload_callback zu

            $arrAnz = $arrAnz->numRows;

             // wenn nur ein Eintrag, dann ab aktuellem Jahr bis -5 Jahre wählbar
            if ($arrAnz == 1) {
                $datum   = getdate();
                $aktJahr = $datum[year];
                $jahr    = array(
                                    $aktJahr,
                                    $aktJahr - 1,
                                    $aktJahr - 2,
                                    $aktJahr - 3,
                                    $aktJahr - 4,
                                    $aktJahr - 5
                );
            }
        }

        return $jahr;
    }
}