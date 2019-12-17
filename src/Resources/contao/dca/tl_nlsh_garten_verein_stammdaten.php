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

use Contao\NlshGartenConfigModel;
use Contao\NlshGartenVereinStammdatenModel;
use Contao\NlshGartenGartenDataModel;
use Symfony\Component\VarDumper\VarDumper;

/*
 * Table tl_nlsh_garten_verein_stammdaten
 */

$GLOBALS['TL_DCA']['tl_nlsh_garten_verein_stammdaten'] = array(

     // Config.
    'config' => array
    (
        'dataContainer'             => 'Table',
        'enableVersioning'          => true,
        'ctable'                    => array('tl_nlsh_garten_garten_data', 'tl_nlsh_garten_config'),
        'onload_callback'           => array(array('tl_nlsh_garten_verein_stammdaten','newYear')),
        'onsubmit_callback'         => array(array('tl_nlsh_garten_verein_stammdaten','firstNewYear')),
        'sql'                       => array('keys' => array('id' => 'primary')),
    ),

     // List.
    'list' => array
    (
        'sorting' => array
        (
            'mode'                  => 1,
            'fields'                => array('jahr'),
            'disableGrouping'       => true,
            'panelLayout'           => 'search,sort,limit',
            'flag'                  => 12,
        ),
        'label' => array
        (
            'fields'                => array('jahr'),
            'format'                => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['stammdatenLabel'],
        ),
        'global_operations' => array
        (
             // Keine, nur neues Jahr anlegen und das kommt von alleine.
        ),
        'operations' => array
        (
            'editGarten' => array
            (
                'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['editGarten'],
                'href'              => 'table=tl_nlsh_garten_garten_data',
                'icon'              => 'edit.svg',
            ),
            'editheaderStammDaten' => array
            (
                'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['editheaderStammDaten'],
                'href'              => 'act=edit',
                'icon'              => 'header.svg',
            ),
            'configOutput' => array
            (
                'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['configOutput'],
                'href'              => 'act=edit&amp;table=tl_nlsh_garten_config',
                'icon'              => 'modules.svg',
                'button_callback'   => array('tl_nlsh_garten_verein_stammdaten', 'bottomConfigOutput'),
            ),
            'delete' => array
            (
                'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['delete'],
                'href'              => 'act=delete',
                'icon'              => 'delete.svg',
                'attributes'        => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['delete'][0] . '\'))return false;Backend.getScrollOffset()"',
                'button_callback'   => array('tl_nlsh_garten_verein_stammdaten', 'bottomDeletPeriode')
            ),
            'show' => array
            (
                'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['show'],
                'href'              => 'act=show',
                'icon'              => 'show.svg',
            ),
        ),
    ),

     // Palettes.
    'palettes' => array
    (
        '__selector__'  => array(''),
        'default'       =>      'jahr;
                            {Adresse_legend},
                                name,strasse,plzort;
                            {Kommunikation_legend},
                                telefon,email;
                            {Bank_legend:hide},
                                bankname,konto,blz,iban,bic;
                            {Pacht_Beitrag_legend},
                                pacht,beitrag;
                            {Strom_Wasser_legend},
                                strom,strom_grundpreis,wasser,wasser_grundpreis;
                            {Abrechnung_stammdaten_individuell_legend:hide},
                                abrechnung_stammdaten_individuell_01_name,
                                abrechnung_stammdaten_individuell_01_wert,
                                abrechnung_stammdaten_individuell_02_name,
                                abrechnung_stammdaten_individuell_02_wert,
                                abrechnung_stammdaten_individuell_03_name,
                                abrechnung_stammdaten_individuell_03_wert,
                                abrechnung_stammdaten_individuell_04_name,
                                abrechnung_stammdaten_individuell_04_wert;
                            {Landgrosse_legend},
                                landgrosse;
                            {Mitgliedergruppe_id_legend},
                                mitgliedergruppe_id;
                            {Rechtliches_legend},
                                vereinsvorsitzender,
                                finanzamt,steuernummer,
                                amtsgericht,
                                amtsgericht_nummer;',
    ),

     // Subpalettes.
    'subpalettes' => array('' => ''),

     // Fields.
    'fields' => array
    (
        'id' => array
        (
            'sql'               => 'int(10) unsigned NOT NULL auto_increment'
        ),
        'tstamp' => array
        (
            'sql'               => "int(10) unsigned NOT NULL default '0'"
        ),
        'jahr' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['jahr'],
            'inputType'         => 'select',
            'options_callback'  => array(
                                        'tl_nlsh_garten_verein_stammdaten',
                                        'optionStammdatenjahr',
                                   ),
            'eval'              => array(
                                       'mandatory' => true,
                                       'maxlength' => 4,
                                       'rgxp'      => 'digit',
                                       'unique'    => true,
                                       'tl_class'  => 'w50',
                                   ),
            'sql'               => "varchar(4) NOT NULL default ''",
        ),
        'name' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['name'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'maxlength' => 80,
                                        'tl_class'  => 'long',
                                    ),
            'sql'               => "varchar(80) NOT NULL default ''",
        ),
        'strasse' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['strasse'],
            'inputType'         > 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'maxlength' => 50,
                                        'tl_class'  => 'w50',
            ),
            'sql'               => "varchar(50) NOT NULL default ''",
        ),
        'plzort' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['plzort'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'maxlength' => 70,
                                        'rgxp'      => 'alnum',
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(70) NOT NULL default ''",
        ),
        'telefon' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['telefon'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => false,
                                        'maxlength' => 50,
                                        'rgxp'      => 'phone',
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(50) NOT NULL default ''",
        ),
        'email' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['email'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => false,
                                        'maxlength' => 60,
                                        'rgxp'      => 'email',
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(60) NOT NULL default ''",
        ),
        'bankname' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['bankname'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'maxlength' => 50,
                                    ),
            'sql'               => "varchar(50) NOT NULL default ''",
        ),
        'konto'  => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['konto'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'maxlength' => 50,
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(50) NOT NULL default ''",
        ),
        'blz' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['blz'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'maxlength' => 50,
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(50) NOT NULL default ''",
        ),
        'iban' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['iban'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'maxlength' => 50,
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(50) NOT NULL default ''",
        ),
        'bic' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['bic'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'maxlength' => 50,
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(50) NOT NULL default ''",
        ),
        'pacht' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['pacht'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'rgxp'      => 'digit',
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'beitrag' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['beitrag'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'rgxp'      => 'digit',
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'strom' => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['strom'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'rgxp'      => 'digit',
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'strom_grundpreis' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['stromGrundpreis'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'rgxp'      => 'digit',
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'wasser' => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['wasser'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'rgxp'      => 'digit',
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'wasser_grundpreis' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['wasserGrundpreis'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'rgxp'      => 'digit',
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'landgrosse' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['landgrosse'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'rgxp'      => 'digit',
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'abrechnung_stammdaten_individuell_01_name' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnungStammdatenIndi01Name'],
            'inputType'         => 'text',
            'eval'              => array('tl_class' => 'w50'),
            'sql'               => "varchar(80) NOT NULL default ''",
        ),
        'abrechnung_stammdaten_individuell_01_wert' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnungStammdatenIndi01Wert'],
            'inputType'         => 'text',
            'eval'              => array(
                                    'rgxp'     => 'digit',
                                    'tl_class' => 'w50',
                                   ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'abrechnung_stammdaten_individuell_02_name' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnungStammdatenIndi02Name'],
            'inputType'         => 'text',
            'eval'              => array('tl_class' => 'w50'),
            'sql'               => "varchar(80) NOT NULL default ''",
        ),
        'abrechnung_stammdaten_individuell_02_wert' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnungStammdatenIndi02Wert'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'rgxp'     => 'digit',
                                        'tl_class' => 'w50',
                                    ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'abrechnung_stammdaten_individuell_03_name' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnungStammdatenIndi03Name'],
            'inputType'         => 'text',
            'eval'              => array('tl_class' => 'w50'),
            'sql'               => "varchar(80) NOT NULL default ''",
        ),
        'abrechnung_stammdaten_individuell_03_wert' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnungStammdatenIndi03Wert'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'rgxp'     => 'digit',
                                        'tl_class' => 'w50',
                                    ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'abrechnung_stammdaten_individuell_04_name' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnungStammdatenIndi04Name'],
            'inputType'         => 'text',
            'eval'              => array('tl_class' => 'w50'),
            'sql'               => "varchar(80) NOT NULL default ''",
        ),
        'abrechnung_stammdaten_individuell_04_wert' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['abrechnungStammdatenIndi04Wert'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'rgxp'     => 'digit',
                                        'tl_class' => 'w50',
                                    ),
            'sql'               => "double NOT NULL default '0'",
        ),
        'mitgliedergruppe_id' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['mitgliedergruppeId'],
            'inputType'         => 'select',
            'foreignKey'        => 'tl_member_group.name',
            'eval'              => array(
                                        'mandatory'          => true,
                                        'includeBlankOption' => true,
                                    ),
            'sql'               => "int(11) NOT NULL default '0'",
        ),
        'vereinsvorsitzender' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['vereinsvorsitzender'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'mandatory' => true,
                                        'maxlength' => 30,
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(30) NOT NULL default ''",
        ),
        'finanzamt' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['finanzamt'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'maxlength' => 30,
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(30) NOT NULL default ''",
        ),
        'steuernummer' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['steuernummer'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'maxlength' => 30,
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(15) NOT NULL default ''",
        ),
        'amtsgericht' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['amtsgericht'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'maxlength' => 30,
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(30) NOT NULL default ''",
        ),
        'amtsgericht_nummer' => array
        (
            'label'             => &$GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['amtsgerichtNummer'],
            'inputType'         => 'text',
            'eval'              => array(
                                        'maxlength' => 15,
                                        'tl_class'  => 'w50',
                                    ),
            'sql'               => "varchar(15) NOT NULL default ''",
        ),
    ),
);


/**
 * DCA- Klassen der Tabelle tl_nlsh_garten_verein_stammdaten
 *
 * @package nlsh/nlsh_kleingartenverwaltung-bundle
 */

/**
 * Class tl_nlsh_garten_verein_stammdaten
 *
 * Enthält Funktionen einzelner Felder der Konfiguration
 */
class tl_nlsh_garten_verein_stammdaten extends Backend
{

    /**
     * Den Backenduser importieren
     *
     * Contao- Funktion
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');

    }//end __construct()

    /**
     * Daten für Neuanlage eines Jahres vortragen, wenn nicht das erste Jahr
     *
     * Beim Anlegen eines neuen Jahres die Daten vortragen,
     * aber nur dann, wenn es nicht das erste Jahr ist
     *
     * onload_callback des DataContainers
     *
     * @param \DataContainer $dc Contao- DataContainer- Objekt
     *                                   noch ungefüllt in $dc->activeRecord
     *                                   nur $dc->id.
     *
     * @return void
     */
    public function newYear(\DataContainer $dc)
    {
         // Neu angelegten Datensatz holen.
         // In ihm existiert nur die ID!
        $newStammdatenJahr = NlshGartenVereinStammdatenModel::findOneById($dc->id);

         // Neuanlage, wenn tstamp = 0, deshalb nur dann weiter.
        if ($newStammdatenJahr->tstamp === '0') {
             // Anzahl der Datensätze herausfinden.
            $arrAnz = NlshGartenVereinStammdatenModel::countAll();

             // Wenn mehr als ein Datensatz,
             // dann vorbelegen des neuen Jahres mit den Daten des alten Jahr.
            if ($arrAnz > 1) {
                 // Das höchste Jahr abfragen.
                $lastYear = $this->Database->prepare('SELECT MAX(jahr) FROM tl_nlsh_garten_verein_stammdaten')->execute()->fetchAssoc();

                $lastStammdatenJahr = NlshGartenVereinStammdatenModel::findOneByJahr($lastYear['MAX(jahr)']);

                 // Und Daten übernehmen.
                $newStammdatenJahr->jahr                = $lastStammdatenJahr->jahr + 1;
                $newStammdatenJahr->tstamp              = time();
                $newStammdatenJahr->name                = $lastStammdatenJahr->name;
                $newStammdatenJahr->vereinsvorsitzender = $lastStammdatenJahr->vereinsvorsitzender;
                $newStammdatenJahr->strasse             = $lastStammdatenJahr->strasse;
                $newStammdatenJahr->plzort              = $lastStammdatenJahr->plzort;
                $newStammdatenJahr->telefon             = $lastStammdatenJahr->telefon;
                $newStammdatenJahr->email               = $lastStammdatenJahr->email;
                $newStammdatenJahr->bankname            = $lastStammdatenJahr->bankname;
                $newStammdatenJahr->konto               = $lastStammdatenJahr->konto;
                $newStammdatenJahr->blz                 = $lastStammdatenJahr->blz;
                $newStammdatenJahr->iban                = $lastStammdatenJahr->iban;
                $newStammdatenJahr->bic                 = $lastStammdatenJahr->bic;
                $newStammdatenJahr->pacht               = $lastStammdatenJahr->pacht;
                $newStammdatenJahr->beitrag             = $lastStammdatenJahr->beitrag;
                $newStammdatenJahr->strom               = $lastStammdatenJahr->strom;
                $newStammdatenJahr->strom_grundpreis    = $lastStammdatenJahr->strom_grundpreis;
                $newStammdatenJahr->wasser              = $lastStammdatenJahr->wasser;
                $newStammdatenJahr->wasser_grundpreis   = $lastStammdatenJahr->wasser_grundpreis;
                $newStammdatenJahr->landgrosse          = $lastStammdatenJahr->landgrosse;
                $newStammdatenJahr->mitgliedergruppe_id = $lastStammdatenJahr->mitgliedergruppe_id;
                $newStammdatenJahr->finanzamt           = $lastStammdatenJahr->finanzamt;
                $newStammdatenJahr->steuernummer        = $lastStammdatenJahr->steuernummer;
                $newStammdatenJahr->amtsgericht         = $lastStammdatenJahr->amtsgericht;
                $newStammdatenJahr->amtsgericht_nummer  = $lastStammdatenJahr->amtsgericht_nummer;

                 // Und sichern.
                $newStammdatenJahr->save();

                 // Jetzt auch die Gärten vortragen, wenn vorhanden
                 // jetzt alle Gärten des Vorjahres auslesen.
                $garten = NlshGartenGartenDataModel::findByPid($lastStammdatenJahr->id);

                if ($garten !== null) {
                    // Jetzt speichern.
                    while ($garten->next()) {
                        $tempNewGarten = NlshGartenGartenDataModel::findOneBy('id', $garten->id);

                        $tempNewGarten = clone $tempNewGarten;

                        // Wenn dauerhaft, dann übernehmen.
                        if ($garten->individuell_01_dauer === '0') {
                            $tempNewGarten->abrechnung_garten_individuell_01_name = '';
                            $tempNewGarten->abrechnung_garten_individuell_01_wert = '';
                        }

                        if ($garten->individuell_02_dauer === '0') {
                            $tempNewGarten->abrechnung_garten_individuell_02_name = '';
                            $tempNewGarten->abrechnung_garten_individuell_02_wert = '';
                        }

                        if ($garten->individuell_03_dauer === '0') {
                            $tempNewGarten->abrechnung_garten_individuell_03_name = '';
                            $tempNewGarten->abrechnung_garten_individuell_03_wert = '';
                        }

                        if ($garten->individuell_04_dauer === '0') {
                            $tempNewGarten->abrechnung_garten_individuell_04_name = '';
                            $tempNewGarten->abrechnung_garten_individuell_04_wert = '';
                        }

                        $tempNewGarten->pid             = $dc->id;
                        $tempNewGarten->tstamp          = time();
                        $tempNewGarten->strom           = 0;
                        $tempNewGarten->wasser          = 0;
                        $tempNewGarten->pacht_ja_nein   = 1;
                        $tempNewGarten->beitrag_ja_nein = 1;
                        $tempNewGarten->individuell_01_gartenstamm_ja_nein = 1;
                        $tempNewGarten->individuell_02_gartenstamm_ja_nein = 1;
                        $tempNewGarten->individuell_03_gartenstamm_ja_nein = 1;
                        $tempNewGarten->individuell_04_gartenstamm_ja_nein = 1;

                        // Und speichern.
                        $tempNewGarten->save();
                    }//end while
                }//end if

                // Jetzt auch die Einstellung vortragen, wenn vorhanden, müsste aber, da Tochtertabelle
                // jetzt alle Einstellungen des Vorjahres auslesen.
                $einstellungen = NlshGartenConfigModel::findOneByPid($lastStammdatenJahr->id);

                if ($einstellungen !== null) {
                    $newEinstellungen         = clone $einstellungen;
                    $newEinstellungen->pid    = $dc->id;
                    $newEinstellungen->tstamp = time();
                    $newEinstellungen->jahr   = ($lastStammdatenJahr->jahr + 1);
                    $newEinstellungen->nlsh_rgvorbelegung_datum = '';

                    $newEinstellungen->save();
                }
            }//end if
        }//end if

    }//end newYear()

    /**
     * Konfiguration für Neuanlage des ertsen Jahres erzeugen
     *
     * Neuanlage des Eintrages in der 'tl_nlsh_garten_config' beim ersten Datensatz,
     * ab dem zweiten Jahr erledigt dies der onload_Callback
     *
     * onsubmit_callback des DataContainers
     *
     * @param \DataContainer $dc Contao- DataContainer- Objekt
     *                           jetzt gefüllt in $dc->activeRecord mit allen Daten.
     *
     * @return void
     */
    public function firstNewYear(\DataContainer $dc)
    {
         // Kontrolle, ob es einen Eintrag mit der aktuellen ID gibt.
        $modelGartenConfig = NlshGartenConfigModel::findOneByPid($dc->id);

         // Wenn nicht, Neuanlage und Vorbelegung.
        if ($modelGartenConfig === null) {
            $newModelStammdatenConfig         = new NlshGartenConfigModel();
            $newModelStammdatenConfig->pid    = $dc->id;
            $newModelStammdatenConfig->tstamp = time();
            $newModelStammdatenConfig->jahr   = $dc->activeRecord->jahr;
            $newModelStammdatenConfig->nlsh_garten_text_rg_verbrauchsdaten = $GLOBALS['TL_LANG']['tl_nlsh_garten_config']['nlshGartenTextRgVerbrauchsdaten'];

            $newModelStammdatenConfig->save();
        }

    }//end firstNewYear()

    /**
     * Vorbelegung für Auswahl des Stammdatenjahres
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
     * @param \DataContainer $dc Contao- DataContainer- Objekt.
     *
     * @return array          Array mit Jahr der Stammdaten
     */
    public function optionStammdatenjahr(\DataContainer $dc)
    {
         // Wenn Eintrag vorhanden, dann diesen wieder zurück.
        $jahr = array();

        if (empty($dc->activeRecord->jahr) !== true) {
            $jahr[] = $dc->activeRecord->jahr;
        } else {
             // Wenn keiner vorhanden, dann Kontrolle ob Stammdaten schon vorhanden
             // dummerweise ist immer mindestens ein Wert vorhanden,
             // wenn die Dateneingabe aufgerufen wird
             // daher nur wenn einer (der Erste) vorhanden, dann Jahr wählbar
             // else brauch ich nicht, da wenn mehr als zwei Einträge,
             // schlägt der onload_callback zu.
            $arrAnz = NlshGartenVereinStammdatenModel::countAll();

             // Wenn nur ein Eintrag, dann ab aktuellem Jahr bis -5 Jahre wählbar.
            if ($arrAnz === 1) {
                $datum   = getdate();
                $aktJahr = $datum['year'];
                $jahr    = array(
                    $aktJahr,
                    $aktJahr - 1,
                    $aktJahr - 2,
                    $aktJahr - 3,
                    $aktJahr - 4,
                    $aktJahr - 5,
                );
            }
        }//end if

        return $jahr;

    }//end optionStammdatenjahr()

    /**
     * Ist ein button_callback: Ermöglicht individuelle Navigationssymbole
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
    public function bottomConfigOutput(array $arrRow, string $href, string $label, string $title, string $icon, string $attributes, string $strTable)
    {
        // ID der Konfiguration suchen, sollte ja die gleiche pid haben.
        $tableConfig = NlshGartenConfigModel::findOneByPid($arrRow['id']);

        // Und Link zusammenbasteln.
        return '<a href="' . $this->addToUrl($href . '&amp;id=' . $tableConfig->id) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . \Image::getHtml($icon, $label) . '</a> ';

    }//end bottomConfigOutput()

    /**
     * Ist ein button_callback: Ermöglicht das Löschen einer Abrechnungsperiode nur im höchsten Jahr
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
    public function bottomDeletPeriode(array $arrRow, string $href, string $label, string $title, string $icon, string $attributes, string $strTable)
    {
         // Höchsten Datensatz herauslesen.
        $objStammdatenJahr = NlshGartenVereinStammdatenModel::findAll(array('order' => '`jahr` DESC'));

        if ($objStammdatenJahr->jahr === $arrRow['jahr']) {
            // Und Link zusammenbasteln.
            return '<a href="' . $this->addToUrl($href . '&amp;id=' . $arrRow['id']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . \Image::getHtml($icon, $label) . '</a> ';
        }

         // Ansonsten kein löschen möglich.
        $attributes = 'onclick="confirm(\'' . $GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['cantDelete'][1] . '\');return false;Backend.getScrollOffset()"';
        $title      = $GLOBALS['TL_LANG']['tl_nlsh_garten_verein_stammdaten']['cantDelete'][1];

        return '<a href="' . $this->addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Contao\Image::getHtml(preg_replace('/\.svg/i', '_.svg', $icon)) . '</a> ';

    }//end bottomDeletPeriode()

}//end class
