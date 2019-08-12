<?php
/**
 * Class ModuleNlshGartenGesamtausgabe
 *
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @author    Nils Heinold
 * @copyright Nils Heinold (c) 2013
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */

/**
 * Namespace
 */
namespace Nlsh\KleingartenverwaltungBundle;


use Symfony\Component\VarDumper\VarDumper;

/**
 * Die Gesamtausgabe der Abrechnungsdaten erstellen
 *
 * Diese Klasse erzeugt die Gesamtausgabe der Auswertung
 */
class ModuleNlshGartenGesamtausgabe extends \Module
{

    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'mod_nlsh_gesamtausgabe';

    /**
     * Existing years
     *
     * @var array
     */
    public $arrYears = array();

    /**
     * Output year
     *
     * @var integer
     */
    public $intYear = 0;

    /**
     * All data
     *
     * @var array
     */
    public $dataOutput = array();

    /**
     * Wenn keine Auswertungsjahre vorhanden, dann nur Ausgabe einer Fehlermeldung
     *
     * @return string
     */
    public function generate()
    {
         // Im Backend lediglich eine Wildcard anzeigen.
        if (TL_MODE === 'BE') {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### Nlsh_Kleingartenverwaltung ###';
            $objTemplate->title    = $this->headline;
            $objTemplate->id       = $this->id;
            $objTemplate->link     = $this->name;
            $objTemplate->href     = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

         // Ausgabejahr und vorhandene Jahre holen.
        $getOutputTimes = $this->getTimes();

         // Wenn nicht vorhanden, dann Tschüß.
        if ($getOutputTimes === false) {
            return $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['nodata'];
        }

         // Jahr und Ausgabejahre übernehmen.
        $this->arrYears = $getOutputTimes['arrYears'];
        $this->intYear  = $getOutputTimes['outputYear'];

        return parent::generate();

    }//end generate()

    /**
     * Modul generieren
     *
     * @return void
     */
    protected function compile()
    {
         // Select- für Auswahl der Jahre zusammenbasteln und ins Template.
        $this->Template->formSelectYear = $this->createFormSelectYear();

         // Daten Array zusammenbasteln und nach $this->dataOutput übergeben
         // Gesamtausgabetabelle zusammenbasten.
        $this->dataOutput = $this->createArrayAllData();

        if ($this->dataOutput !== false) {
             // Daten in das Template, falls ein eigenes Template erstellt werden soll.
            $this->Template->dataOutput = $this->dataOutput;
        } else {
            $this->dataOutput['error'] = sprintf(
                '<p class = "error">%s</p>',
                $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['nodata']
            );
        }

         // Und Übergabe.
        $this->Template->gesamtAusgabe = $this->dataOutput;

        /*
         * Rechnung zusammenbasteln und ausgeben, wenn gewünscht
         * Bedingung: $_GET['rechnung'] mit id des Gartens
         * danach Abbruch der Ausgabe,
         * da Ausgabe in neuem Fenster, nur die Rechnung und nicht des Cores danach
         */

        $getRechnungen = \Input::get('rechnung');
        if (isset($getRechnungen) === true) {
            for ($i = 0, $count = count($this->dataOutput['garten_abrechnung']); $i < $count; $i++) {
                if ($this->dataOutput['garten_abrechnung'][$i]['id'] === $getRechnungen) {
                     // Neues Template initialisieren.
                    $objTemplate = new \FrontendTemplate('mod_nlsh_rechnungsausgabe');

                    $data = $this->dataOutput;
                    $data['garten_abrechnung'] = $this->dataOutput['garten_abrechnung'][$i];
                    $objTemplate->rg_outPut    = $data;

                    $this->Template->rg_outPut = $objTemplate->parse();
                }
            }

             // Wenn Daten vorhanden, dann ausgeben.
            if (isset($this->Template->rg_outPut) === true) {
                echo $this->Template->rg_outPut;
            } else {
                echo $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['nodata'];
            }

            die;
        }//end if

        /*
         * Buchungssatz zusammenbasteln und ausgeben, wenn gewünscht
         * Bedingung: $_GET['buchungssatz'] == TRUE
         * danach Abbruch der Ausgabe, da Ausgabe in neuem Fenster
         * nur der Buchungssätze und nicht des Cores danach
         */

        $getBuchungssatz = \Input::get('Buchungssatz');
        if (isset($getBuchungssatz) === true) {
             // Wenn keine Einstellungen für dieses Ausgabejahr vorhanden, dann Abbruch.
            if (empty($this->dataOutput['einstellungen']) === true) {
                echo $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['noconfig'];
                die;
            }

             // Wenn keine Gärten für dieses Ausgabejahr vorhanden, dann Abbruch.
            if (empty($this->dataOutput['garten_abrechnung']) === true) {
                echo $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['nogarten'];
                die;
            }

            // Opjekt für Stapelverarbeitung erzeugen.
            $objBuchungssatz = new NlshDatevDtvfStandardFormatCreater();

             // Jetzt die Ausgabe pro Garten.
            for ($i = 0, $count = count($this->dataOutput['garten_abrechnung']); $i < $count; $i++) {
                $arrGarten = array(
                    'ausgabejahr' => $this->dataOutput['ausgabejahr'],
                    'nr'          => $this->dataOutput['garten_abrechnung'][$i]['nr'],
                    'lastname'    => $this->dataOutput['garten_abrechnung'][$i]['member']['lastname'],
                    'firstname'   => $this->dataOutput['garten_abrechnung'][$i]['member']['firstname'],
                );

                 // Beitrag.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['beitrag']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['beitrag'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_beitrag'],
                        $arrGarten
                    );
                }

                 // Pacht.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['pacht']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['pacht'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_pacht'],
                        $arrGarten
                    );
                }

                 // Strom.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['strom_kosten']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['strom_kosten'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_strom'],
                        $arrGarten
                    );
                }

                 // Wasser.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['wasser_kosten']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['wasser_kosten'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_wasser'],
                        $arrGarten
                    );
                }

                 // Abrechnung_garten_individuell_01_wert.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['abrechnung_garten_individuell_01_wert']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['abrechnung_garten_individuell_01_wert'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_individuell_01_garten'],
                        $arrGarten
                    );
                }

                 // Abrechnung_garten_individuell_02_wert.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['abrechnung_garten_individuell_02_wert']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['abrechnung_garten_individuell_02_wert'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_individuell_02_garten'],
                        $arrGarten
                    );
                }

                 // Abrechnung_garten_individuell_03_wert.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['abrechnung_garten_individuell_03_wert']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['abrechnung_garten_individuell_03_wert'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_individuell_03_garten'],
                        $arrGarten
                    );
                }

                 // Abrechnung_garten_individuell_04_wert.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['abrechnung_garten_individuell_04_wert']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['abrechnung_garten_individuell_04_wert'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_individuell_04_garten'],
                        $arrGarten
                    );
                }

                 // Abrechnung_stammdaten_individuell_01_wert.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['abrechnung_stammdaten_individuell_01_wert']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['abrechnung_stammdaten_individuell_01_wert'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_individuell_01_gartenstamm'],
                        $arrGarten
                    );
                }

                 // Abrechnung_stammdaten_individuell_02_wert.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['abrechnung_stammdaten_individuell_02_wert']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['abrechnung_stammdaten_individuell_02_wert'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_individuell_02_gartenstamm'],
                        $arrGarten
                    );
                }

                 // Abrechnung_stammdaten_individuell_03_wert.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['abrechnung_stammdaten_individuell_03_wert']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['abrechnung_stammdaten_individuell_03_wert'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_individuell_03_gartenstamm'],
                        $arrGarten
                    );
                }

                 // Abrechnung_stammdaten_individuell_04_wert.
                if (empty($this->dataOutput['garten_abrechnung'][$i]['abrechnung_stammdaten_individuell_04_wert']) === false) {
                    $strBuchsatz .= $this->erstelleBuchungssatz(
                        $this->dataOutput['garten_abrechnung'][$i]['abrechnung_stammdaten_individuell_04_wert'],
                        $this->dataOutput['einstellungen']['nlsh_garten_konto_individuell_04_gartenstamm'],
                        $arrGarten
                    );
                }
            }//end for

             // Neues Template initialisieren.
            $objTemplate           = new \FrontendTemplate('mod_nlsh_buchungausgabe');
            $objTemplate->buchSatz = $strBuchsatz;

             // Und ausgeben.
            echo $objTemplate->parse();

            die;
        }//end if

    }//end compile()

    /**
     * Alle vorhandenen Jahre holen
     *
     * @return array|FALSE  Array mit vorhandenen Jahren, oder FALSE
     */
    protected function getTimes()
    {
        $objYears = \NlshGartenVereinStammdatenModel::findAll(array('order' => '`jahr` DESC'));

         // Wenn keine Daten vorhanden mit FALSE zurück.
        if ($objYears === null) {
            return false;
        }

         // Festlegung des Ausgabejahres, entweder $_GET oder höchstes Jahr.
        $outputYear = \Input::get('Ausgabejahr');

        if (empty($outputYear) === false) {
             // Kontrolle, ob Jahr auch vorhanden.
            $kontrolle = \NlshGartenVereinStammdatenModel::findOneBy('`jahr`', $outputYear);

            if ($kontrolle->jahr !== $outputYear) {
                $outputYear = $objYears->jahr;
            }
        } else {
             // Ansonsten höchstes Jahr.
            $outputYear = $objYears->jahr;
        }

        $return['arrYears']   = array_values($objYears->fetchEach('jahr'));
        $return['outputYear'] = $outputYear;

        return $return;

    }//end getTimes()

    /**
     * Erstellt ein Array mit allen Daten der Gartenabrechnung
     *
     * @return array|FALSE  Array mit der kompletten Abrechnung, oder FALSE
     */
    protected function createArrayAllData()
    {
         // Definitionen.
        $gartenGesamtAbrechnung = array();

         // Ausgabejahr in Array integrieren.
        $gartenGesamtAbrechnung['ausgabejahr'] = $this->intYear;
        $gartenGesamtAbrechnung['jahre']       = $this->arrYears;

         // Jetzt ziehen wir uns die Stammdaten rein.
        $gartenVereinStammdaten = \NlshGartenVereinStammdatenModel::findByJahr($gartenGesamtAbrechnung['ausgabejahr']);
        $gartenGesamtAbrechnung['stammdaten_verein'] = $gartenVereinStammdaten->row();

         // Stammdaten um formatierte Felder erweitern.
        $gartenGesamtAbrechnung['stammdaten_verein']['pacht_formated']             = $gartenGesamtAbrechnung['stammdaten_verein']['pacht'] . '&nbsp;' . $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['waehrung'] . '/' . $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['pacht_einheit'];
        $gartenGesamtAbrechnung['stammdaten_verein']['beitrag_formated']           = $this->formatedNumber(
            $gartenGesamtAbrechnung['stammdaten_verein']['beitrag'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['stammdaten_verein']['strom_formated']             = $gartenGesamtAbrechnung['stammdaten_verein']['strom'] . '&nbsp;' . $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['waehrung'] . '/' . $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['strom_einheit'];
        $gartenGesamtAbrechnung['stammdaten_verein']['strom_grundpreis_formated']  = $this->formatedNumber(
            $gartenGesamtAbrechnung['stammdaten_verein']['strom_grundpreis'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['stammdaten_verein']['wasser_formated']            = $gartenGesamtAbrechnung['stammdaten_verein']['wasser'] . '&nbsp;' . $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['waehrung'] . '/' . $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['wasser_einheit'];
        $gartenGesamtAbrechnung['stammdaten_verein']['wasser_grundpreis_formated'] = $this->formatedNumber(
            $gartenGesamtAbrechnung['stammdaten_verein']['wasser_grundpreis'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['stammdaten_verein']['landgrosse_formated']        = $this->formatedNumber(
            $gartenGesamtAbrechnung['stammdaten_verein']['landgrosse'],
            'grosse_einheit'
        );

         // Jetzt benötigen wir noch die Garten- pid.
        $gartenGesamtAbrechnung['garten_pid'] = $gartenVereinStammdaten->id;

         // Jetzt sind die Einstellungen dran.
        $gartenConfig = \NlshGartenConfigModel::findByJahr($gartenGesamtAbrechnung['ausgabejahr']);

        if ($gartenConfig !== null) {
            $gartenGesamtAbrechnung['einstellungen'] = $gartenConfig->row();

             // Abrechnungsjahr für Beitrag, Pacht und Verbrauchsdaten eintragen.
            if (isset($gartenGesamtAbrechnung['einstellungen']['nlsh_garten_vorschuss_beitrag']) === true) {
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_ausgabejahr_beitrag'] = ($gartenGesamtAbrechnung['ausgabejahr'] + 1);
            } else {
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_ausgabejahr_beitrag'] = $gartenGesamtAbrechnung['ausgabejahr'];
            }

            if (isset($gartenGesamtAbrechnung['einstellungen']['nlsh_garten_vorschuss_pacht']) === true) {
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_ausgabejahr_pacht'] = ($gartenGesamtAbrechnung['ausgabejahr'] + 1);
            } else {
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_ausgabejahr_pacht'] = $gartenGesamtAbrechnung['ausgabejahr'];
            }

            if (isset($gartenGesamtAbrechnung['einstellungen']['nlsh_garten_verbrauchsdaten_vorjahr']) === true) {
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_ausgabejahr_verbrauchsdaten'] = ($gartenGesamtAbrechnung['ausgabejahr'] - 1);
            } else {
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_ausgabejahr_verbrauchsdaten'] = $gartenGesamtAbrechnung['ausgabejahr'];
            }

             // Einstellungen um formatierte Felder erweitern.
            $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_ausgabejahr_verbrauchsdaten_formated'] = str_replace(
                '%jahr',
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_ausgabejahr_verbrauchsdaten'],
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_text_rg_verbrauchsdaten']
            );
            $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_text_rg_pacht_beitrag_formated']       = str_replace(
                '%jahrbeitrag',
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_ausgabejahr_beitrag'],
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_text_rg_pacht_beitrag']
            );
            $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_text_rg_pacht_beitrag_formated']       = str_replace(
                '%jahrpacht',
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_ausgabejahr_pacht'],
                $gartenGesamtAbrechnung['einstellungen']['nlsh_garten_text_rg_pacht_beitrag_formated']
            );
        } else {
            $gartenGesamtAbrechnung['einstellungen'] = false;
        }//end if

         // Jetzt ziehen wir uns alle Mitglieder rein.
        $member = \MemberModel::findAll();

         // Den zweiten von mehrfach belegten Gärten von einem Mitglied ermitteln.
        $doubleGarten = \NlshGartenGartenDataModel::findDoubleGarten(
            $gartenGesamtAbrechnung['garten_pid']
        );

         // Zur Optimierung
         // Erzeugung eines Array, welches indiziert wir mit seinem id
         // wird benutzt, um im $newArr jedem Garten sein Member- Array
         // zu übergeben, ohne bei jedem Durchlauf der while Schleife
         // auf die Datenbank zugreifen zu müssen.
        while ($member->next()) {
            $idIndiMember[$member->id] = $member->row();
        }

         // Model_Collection $member zurücksetzen.
        $member->reset();

         // Daten der Gärten holen.
        $gartenGartenData = \NlshGartenGartenDataModel::findBy(
            'pid',
            $gartenGesamtAbrechnung['garten_pid'],
            array('order' => '`nr` ASC')
        );

         // Jetzt lesen wir ein.
        while ($gartenGartenData->next()) {
            $newArr = array(
                'id'                                    => $gartenGartenData->id,
                'nr'                                    => trim($gartenGartenData->nr),
                'grosse'                                => $gartenGartenData->grosse,
                'strom'                                 => $gartenGartenData->strom,
                'wasser'                                => $gartenGartenData->wasser,
                'nutzung_user_id'                       => $gartenGartenData->nutzung_user_id,
                'pacht_ja_nein'                         => $gartenGartenData->pacht_ja_nein,
                'beitrag_ja_nein'                       => $gartenGartenData->beitrag_ja_nein,
                'individuell_01_gartenstamm_ja_nein'    => $gartenGartenData->individuell_01_gartenstamm_ja_nein,
                'individuell_02_gartenstamm_ja_nein'    => $gartenGartenData->individuell_02_gartenstamm_ja_nein,
                'individuell_03_gartenstamm_ja_nein'    => $gartenGartenData->individuell_03_gartenstamm_ja_nein,
                'individuell_04_gartenstamm_ja_nein'    => $gartenGartenData->individuell_04_gartenstamm_ja_nein,
                'abrechnung_garten_individuell_01_name' => $gartenGartenData->abrechnung_garten_individuell_01_name,
                'abrechnung_garten_individuell_01_wert' => $gartenGartenData->abrechnung_garten_individuell_01_wert,
                'abrechnung_garten_individuell_02_name' => $gartenGartenData->abrechnung_garten_individuell_02_name,
                'abrechnung_garten_individuell_02_wert' => $gartenGartenData->abrechnung_garten_individuell_02_wert,
                'abrechnung_garten_individuell_03_name' => $gartenGartenData->abrechnung_garten_individuell_03_name,
                'abrechnung_garten_individuell_03_wert' => $gartenGartenData->abrechnung_garten_individuell_03_wert,
                'abrechnung_garten_individuell_04_name' => $gartenGartenData->abrechnung_garten_individuell_04_name,
                'abrechnung_garten_individuell_04_wert' => $gartenGartenData->abrechnung_garten_individuell_04_wert,
                'beitrag'                               => 0,
                'pacht'                                 => 0,
            );
             // Gärten um formatierte Felder erweitern.
            $newArr['grosse_formated'] = $this->formatedNumber(
                $newArr['grosse'],
                'grosse_einheit'
            );
            $newArr['strom_formated']  = $this->formatedNumber(
                $newArr['strom'],
                'strom_einheit'
            );
            $newArr['wasser_formated'] = $this->formatedNumber(
                $newArr['wasser'],
                'wasser_einheit'
            );
            $newArr['abrechnung_garten_individuell_01_wert_formated'] = $this->formatedNumber(
                $newArr['abrechnung_garten_individuell_01_wert'],
                'waehrung'
            );
            $newArr['abrechnung_garten_individuell_02_wert_formated'] = $this->formatedNumber(
                $newArr['abrechnung_garten_individuell_02_wert'],
                'waehrung'
            );
            $newArr['abrechnung_garten_individuell_03_wert_formated'] = $this->formatedNumber(
                $newArr['abrechnung_garten_individuell_03_wert'],
                'waehrung'
            );
            $newArr['abrechnung_garten_individuell_04_wert_formated'] = $this->formatedNumber(
                $newArr['abrechnung_garten_individuell_04_wert'],
                'waehrung'
            );

             // Die Member- Tabelle hinzufügen.
            if (empty($newArr['nutzung_user_id']) === false) {
                $newArr['member'] = $idIndiMember[$newArr['nutzung_user_id']];

                 // Name zusammenschustern.
                $newArr['member']['name_komplett'] = $idIndiMember[$newArr['nutzung_user_id']]['lastname'] . ', ' . $idIndiMember[$newArr['nutzung_user_id']]['firstname'];
            }

             // Werte berechnen
             // Beitrag
             // wenn Beitrag aus Vereinstammdaten und Mitgliederstammdaten
             // berechnet werden soll, dann berechnen
             // nur, wenn Garten nicht leer.
            if ((empty($gartenGesamtAbrechnung['stammdaten_verein']['beitrag']) === false)
                && (empty($newArr['beitrag_ja_nein']) === false)
                && (empty($newArr['nutzung_user_id']) === false)
                && (empty($newArr['member']['nlsh_member_beitrag_ja_nein']) === false)
            ) {
                 // Jetzt die Kontrolle, ob der Garten ein doppelter ist,
                 // dann nämlich keinen Beitrag.
                if (isset($doubleGarten[$newArr['nr']]) === true) {
                    $newArr['beitrag'] = 0;
                } else {
                    $newArr['beitrag'] = $gartenGesamtAbrechnung['stammdaten_verein']['beitrag'];
                }

                $gesamtBeitrag = ($gesamtBeitrag + $newArr['beitrag']);
            }

             // Um formatiertes Feld erweitern.
            $newArr['beitrag_formated'] = $this->formatedNumber(
                $newArr['beitrag'],
                'waehrung'
            );

             // Pacht
             // wenn Pacht aus Vereinstammdaten und Mitgliederstammdaten
             // berechnet werden soll, dann berechnen
             // nur, wenn Garten nicht leer.
            if ((empty($gartenGesamtAbrechnung['stammdaten_verein']['pacht']) === false)
                && (empty($newArr['pacht_ja_nein']) === false)
                && (empty($newArr['nutzung_user_id']) === false)
                && (empty($newArr['member']['nlsh_member_pacht_ja_nein']) === false)
            ) {
                $newArr['pacht'] = round(($gartenGesamtAbrechnung['stammdaten_verein']['pacht'] * $newArr['grosse']), 2);
                $gesamtPacht     = ($gesamtPacht + $newArr['pacht']);
            }

             // Um formatiertes Feld erweitern.
            $newArr['pacht_formated'] = $this->formatedNumber(
                $newArr['pacht'],
                'waehrung'
            );

             // Stromabrechnung
             // nur wenn Garten nicht leer und Strom verbraucht wurde.
            if ((empty($newArr['nutzung_user_id']) === false) && (empty($newArr['strom']) === false)) {
                $newArr['strom_kosten'] = round((($gartenGesamtAbrechnung['stammdaten_verein']['strom'] * $newArr['strom']) + $gartenGesamtAbrechnung['stammdaten_verein']['strom_grundpreis']), 2);
                $gesamtStrom            = ($gesamtStrom + $newArr['strom']);
                $gesamtStromKosten      = ($gesamtStromKosten + $newArr['strom_kosten']);
            }

             // Um formatiertes Feld erweitern.
            $newArr['strom_kosten_formated'] = $this->formatedNumber(
                $newArr['strom_kosten'],
                'waehrung'
            );

             // Wasserabrechnung
             // nur wenn Garten nicht leer und Wasser verbraucht wurde.
            if ((empty($newArr['nutzung_user_id']) === false) && (empty($newArr['wasser']) === false)) {
                $newArr['wasser_kosten'] = round((($gartenGesamtAbrechnung['stammdaten_verein']['wasser'] * $newArr['wasser']) + $gartenGesamtAbrechnung['stammdaten_verein']['wasser_grundpreis']), 2);
                $gesamtWasser            = ($gesamtWasser + $newArr['wasser']);
                $gesamtWasserkosten      = ($gesamtWasserkosten + $newArr['wasser_kosten']);
            }

             // Um formatiertes Feld erweitern.
            $newArr['wasser_kosten_formated'] = $this->formatedNumber(
                $newArr['wasser_kosten'],
                'waehrung'
            );

             // Gesamt Größe der Gartenanlage
             // wird zu Kontrolle des Bestandes herangezogen.
            $gesamtGrosse = ($gesamtGrosse + $newArr['grosse']);

             // Summe der individuelle Abrechnungen des einzelnen Gartens
             // nur, wenn Garten nicht leer.
            if (empty($newArr['nutzung_user_id']) === false) {
                $gesamtAbrechnungGartenIndividuell = ($gesamtAbrechnungGartenIndividuell
                                                   + $newArr['abrechnung_garten_individuell_01_wert']
                                                   + $newArr['abrechnung_garten_individuell_02_wert']
                                                   + $newArr['abrechnung_garten_individuell_03_wert']
                                                   + $newArr['abrechnung_garten_individuell_04_wert']);
            }

             // Jetzt individuelle Abrechnung aus den Gartenstammdaten übernehmen
             // nur, wenn vorhanden und Garten nicht leer.
            if (empty($newArr['nutzung_user_id']) === false) {
                 // Erste individuelle Abrechnung
                 // wenn Garten nicht doppelt.
                if ((  (empty($gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_01_name']) === false)
                    || (empty($gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_01_wert']) === false))
                    && (empty($newArr['individuell_01_gartenstamm_ja_nein']) === false)
                    && (empty($doubleGarten[$newArr['nr']]) === true)
                ) {
                    $gesamtAbrechnungStammdatenIndividuell = ($gesamtAbrechnungStammdatenIndividuell + $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_01_wert']);

                    $newArr['abrechnung_stammdaten_individuell_01_name']          = $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_01_name'];
                    $newArr['abrechnung_stammdaten_individuell_01_wert']          = $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_01_wert'];
                    $newArr['abrechnung_stammdaten_individuell_01_wert_formated'] = $this->formatedNumber(
                        $newArr['abrechnung_stammdaten_individuell_01_wert'],
                        'waehrung'
                    );
                    $gesamtAbrechnungStammdatenIndividuell01 = ($gesamtAbrechnungStammdatenIndividuell01 + $newArr['abrechnung_stammdaten_individuell_01_wert']);
                }

                 // Zweite individuelle Abrechnung.
                if ((  (empty($gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_02_name']) === false)
                    || (empty($gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_02_wert']) === false))
                    && (empty($newArr['individuell_02_gartenstamm_ja_nein']) === false)
                    && (empty($doubleGarten[$newArr['nr']]) === true)
                ) {
                    $gesamtAbrechnungStammdatenIndividuell               = ($gesamtAbrechnungStammdatenIndividuell + $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_02_wert']);
                    $newArr['abrechnung_stammdaten_individuell_02_name'] = $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_02_name'];
                    $newArr['abrechnung_stammdaten_individuell_02_wert'] = $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_02_wert'];
                    $newArr['abrechnung_stammdaten_individuell_02_wert_formated'] = $this->formatedNumber(
                        $newArr['abrechnung_stammdaten_individuell_02_wert'],
                        'waehrung'
                    );
                    $gesamtAbrechnungStammdatenIndividuell02 = ($gesamtAbrechnungStammdatenIndividuell02 + $newArr['abrechnung_stammdaten_individuell_02_wert']);
                }

                 // Dritte individuelle Abrechnung.
                if ((  (empty($gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_03_name']) === false)
                    || (empty($gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_03_wert']) === false))
                    && (empty($newArr['individuell_03_gartenstamm_ja_nein']) === false)
                    && (empty($doubleGarten[$newArr['nr']]) === true)
                ) {
                    $gesamtAbrechnungStammdatenIndividuell               = ($gesamtAbrechnungStammdatenIndividuell + $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_03_wert']);
                    $newArr['abrechnung_stammdaten_individuell_03_name'] = $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_03_name'];
                    $newArr['abrechnung_stammdaten_individuell_03_wert'] = $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_03_wert'];
                    $newArr['abrechnung_stammdaten_individuell_03_wert_formated'] = $this->formatedNumber(
                        $newArr['abrechnung_stammdaten_individuell_03_wert'],
                        'waehrung'
                    );
                    $gesamtAbrechnungStammdatenIndividuell03 = ($gesamtAbrechnungStammdatenIndividuell03 + $newArr['abrechnung_stammdaten_individuell_03_wert']);
                }

                 // Vierte individuelle Abrechnung.
                if ((  (empty($gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_04_name']) === false)
                    || (empty($gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_04_wert']) === false))
                    && (empty($newArr['individuell_04_gartenstamm_ja_nein']) === false)
                    && (empty($doubleGarten[$newArr['nr']]) === true)
                ) {
                    $gesamtAbrechnungStammdatenIndividuell               = ($gesamtAbrechnungStammdatenIndividuell + $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_04_wert']);
                    $newArr['abrechnung_stammdaten_individuell_04_name'] = $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_04_name'];
                    $newArr['abrechnung_stammdaten_individuell_04_wert'] = $gartenGesamtAbrechnung['stammdaten_verein']['abrechnung_stammdaten_individuell_04_wert'];
                    $newArr['abrechnung_stammdaten_individuell_04_wert_formated'] = $this->formatedNumber(
                        $newArr['abrechnung_stammdaten_individuell_04_wert'],
                        'waehrung'
                    );
                    $gesamtAbrechnungStammdatenIndividuell04 = ($gesamtAbrechnungStammdatenIndividuell04
                                                                                  + $newArr['abrechnung_stammdaten_individuell_04_wert']);
                }
            }//end if

             // Jetzt noch die Gesamtkosten eines Gartens ermitteln.
            $newArr['gesamt_einzel_garten'] = ($newArr['beitrag']
                                            + $newArr['pacht']
                                            + $newArr['strom_kosten']
                                            + $newArr['wasser_kosten']
                                            + $newArr['abrechnung_garten_individuell_01_wert']
                                            + $newArr['abrechnung_garten_individuell_02_wert']
                                            + $newArr['abrechnung_garten_individuell_03_wert']
                                            + $newArr['abrechnung_garten_individuell_04_wert']
                                            + $newArr['abrechnung_stammdaten_individuell_01_wert']
                                            + $newArr['abrechnung_stammdaten_individuell_02_wert']
                                            + $newArr['abrechnung_stammdaten_individuell_03_wert']
                                            + $newArr['abrechnung_stammdaten_individuell_04_wert']);

             // Um formatiertes Feld erweitern.
            $newArr['gesamt_einzel_garten_formated'] = $this->formatedNumber(
                $newArr['gesamt_einzel_garten'],
                'waehrung'
            );

             // Gesamtkosten der Gartenanlage.
            $gesamtAlleGarten = ($gesamtAlleGarten + $newArr['gesamt_einzel_garten']);

             // Und rein damit.
            $gartenGesamtAbrechnung['garten_abrechnung'][] = $newArr;
        }//end while

        $gartenGesamtAbrechnung['gesamt_beitrag']          = $gesamtBeitrag;
        $gartenGesamtAbrechnung['gesamt_beitrag_formated'] = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_beitrag'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['gesamt_pacht']            = $gesamtPacht;
        $gartenGesamtAbrechnung['gesamt_pacht_formated']   = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_pacht'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['gesamt_grosse']           = $gesamtGrosse;
        $gartenGesamtAbrechnung['gesamt_grosse_formated']  = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_grosse'],
            'grosse_einheit'
        );
        $gartenGesamtAbrechnung['gesamt_abrechnung_garten_individuell']          = $gesamtAbrechnungGartenIndividuell;
        $gartenGesamtAbrechnung['gesamt_abrechnung_garten_individuell_formated'] = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_abrechnung_garten_individuell'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['gesamt_strom']                 = $gesamtStrom;
        $gartenGesamtAbrechnung['gesamt_strom_formated']        = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_strom'],
            'strom_einheit'
        );
        $gartenGesamtAbrechnung['gesamt_stromkosten']           = $gesamtStromKosten;
        $gartenGesamtAbrechnung['gesamt_stromkosten_formated']  = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_stromkosten'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['gesamt_wasser']                = $gesamtWasser;
        $gartenGesamtAbrechnung['gesamt_wasser_formated']       = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_wasser'],
            'wasser_einheit'
        );
        $gartenGesamtAbrechnung['gesamt_wasserkosten']          = $gesamtWasserkosten;
        $gartenGesamtAbrechnung['gesamt_wasserkosten_formated'] = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_wasserkosten'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_01']          = $gesamtAbrechnungStammdatenIndividuell01;
        $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_01_formated'] = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_01'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_02']          = $gesamtAbrechnungStammdatenIndividuell02;
        $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_02_formated'] = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_02'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_03']          = $gesamtAbrechnungStammdatenIndividuell03;
        $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_03_formated'] = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_03'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_04']          = $gesamtAbrechnungStammdatenIndividuell04;
        $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_04_formated'] = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell_04'],
            'waehrung'
        );
        $gartenGesamtAbrechnung['gesamt_abrechnung_stammdaten_individuell']             = $gesamtAbrechnungStammdatenIndividuell;
        $gartenGesamtAbrechnung['gesamt_alle_garten']          = $gesamtAlleGarten;
        $gartenGesamtAbrechnung['gesamt_alle_garten_formated'] = $this->formatedNumber(
            $gartenGesamtAbrechnung['gesamt_alle_garten'],
            'waehrung'
        );

         // Fehlermeldung erzeugen, wenn Summe der einzelnen Gärten
         // mehr als 1qm von der im Gartenstamm angegebenen Größe abweicht.
        if ((   $gartenGesamtAbrechnung['gesamt_grosse'] - $gartenGesamtAbrechnung['stammdaten_verein']['landgrosse'] > 1)
            || ($gartenGesamtAbrechnung['gesamt_grosse'] - $gartenGesamtAbrechnung['stammdaten_verein']['landgrosse'] < -1)
        ) {
             // Fehlertext übernehmen.
            $gartenGesamtAbrechnung['error'] = $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['fehlergroesse'];

            $gartenGesamtAbrechnung['error'] = str_replace(
                '%1',
                $this->formatedNumber(
                    $gartenGesamtAbrechnung['gesamt_grosse'],
                    'grosse_einheit'
                ),
                $gartenGesamtAbrechnung['error']
            );
            $gartenGesamtAbrechnung['error'] = str_replace(
                '%2',
                $this->formatedNumber(
                    $gartenGesamtAbrechnung['stammdaten_verein']['landgrosse'],
                    'grosse_einheit'
                ),
                $gartenGesamtAbrechnung['error']
            );
        }//end if

        if (count($gartenGesamtAbrechnung['garten_abrechnung']) > 0) {
             // Temp_garten_nutzer_array sortieren lassen.
            foreach ($gartenGesamtAbrechnung['garten_abrechnung'] as $key => $row) {
                $nr[$key] = $row['nr'];
            }

            array_multisort($nr, SORT_ASC, $gartenGesamtAbrechnung['garten_abrechnung']);

            return $gartenGesamtAbrechnung;
        }

        return false;

    }//end createArrayAllData()

    /**
     * HTML- Code für die Auswahl des Jahres erzeugen
     *
     * @return string  HTML- String des select- Feldes
     */
    protected function createFormSelectYear()
    {
        $selectJahre     = $this->arrYears;
        $intSelectedYear = $this->intYear;

         // Array für Option erzeugen
         // wenn ausgewähltes Jahr, dann 'default' Key erzeugen
         // damit vom Contao- Widget ein 'selected' erzeugt wird.
        foreach ($selectJahre as $value) {
            if ($value === $intSelectedYear) {
                $arrOptions[] = array(
                    'value'   => $value,
                    'label'   => $value,
                    'default' => 1,
                );
            } else {
                $arrOptions[] = array(
                    'value' => $value,
                    'label' => $value,
                );
            }
        }

         // Tabelle tl_form abfragen.
        $formModel = \FormModel::findOneByNlsh_ident('formSelectYear');

         // Wenn nicht vorhanden, dann neu erzeugen und in DB eintragen.
        if ($formModel === null) {
             // Tabelle tl_form erzeugen.
            $formModel             = new \FormModel;
            $formModel->nlsh_ident = 'formSelectYear';
            $formModel->save();
        }

         // Tabelle tl_form_field abfragen.
        $formFieldModel = \FormFieldModel::findOneByPid($formModel->id);

        if ($formFieldModel === null) {
             // Tabelle tl_formfield erzeugen.
            $formFieldModel = new \FormFieldModel;
            $formFieldModel->save();
        }

         // Tabelle tl_form- Eintrag vervollständigen.
        $formModel->title      = $GLOBALS['TL_LANG']['MSC']['nlsh_htmlSelect']['formTitle'];
        $formModel->alias      = $GLOBALS['TL_LANG']['MSC']['nlsh_htmlSelect']['formAlias'];
        $formModel->jumpTo     = $GLOBALS['objPage']->id;
        $formModel->format     = 'raw';
        $formModel->method     = 'GET';
        $formModel->attributes = serialize(
            array(
                'ausgabejahr',
            // CSS- ID des Formulares.
                '',
            // CSS- Klasse des Formulares.
            )
        );
        $formModel->tableless = 1;
        $formModel->save();

        $formFieldModel->pid      = $formModel->id;
        $formFieldModel->type     = 'select';
        $formFieldModel->name     = 'Ausgabejahr';
        $formFieldModel->label    = $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe']['auswahljahr'];
        $formFieldModel->options  = $arrOptions;
        $formFieldModel->class    = '';
        $formFieldModel->onchange = 'this.form.submit()';
        $formFieldModel->save();

         // HTML für Formulat erzeugen.
        $return = $this->getform($formModel);

        return $return;

    }//end createFormSelectYear()

    /**
     * Buchungssatz erstellen
     *
     * @param integer $intWert    Zahlenwert des Buchungssatzes.
     * @param string  $gegenKonto Das Gegenkonto.
     * @param array   $arrGarten  Array mit Daten des Gartens.
     *
     * @return string  Buchungssatz nach DATEV
     */
    protected function erstelleBuchungssatz($intWert, string $gegenKonto, array $arrGarten)
    {
         // Betrag.
        $strBuchsatz .= str_replace('.', ',', $intWert) . ';';

         // Währung
         // Soll oder Haben.
        if ($intWert > 0) {
            $strBuchsatz .= 'S;';
        } else {
            $strBuchsatz .= 'H;';
        }

         // WKZ Umsatz;Kurs;Basis-Umsatz;WKZ Basis-Umsatz.
        $strBuchsatz .= '"";;;"";';

         // Kontonummer Debitor.
        $strBuchsatz .= '6' . $arrGarten['ausgabejahr'] . ';';

         // Gegenkonto.
        $strBuchsatz .= $gegenKonto . ';';

         // BU-Schlüssel.
        $strBuchsatz .= ';';

         // Belegdatum.
        $strBuchsatz .= date('dm') . ';';

         // Beleg1.
        $strBuchsatz .= $arrGarten['ausgabejahr'] . '/' . $arrGarten['nr'] . ';';

         // Beleg 2.
        $strBuchsatz .= ';';

         // Skonto.
        $strBuchsatz .= ';';

         // Buchungstext.
        $strBuchsatz .= $arrGarten['lastname'] . ', ' . $arrGarten['firstname'] . ';';

         // Rest.
        $strBuchsatz .= ';"";;;;"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";;"";;"";';
        $strBuchsatz .= ';;;;;"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";';
        $strBuchsatz .= '"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";"";;;;"";;;;"";"";0;';
        $strBuchsatz .= '"";;;0;"RE";"";;""';
        $strBuchsatz .= '<br />';

        return ($strBuchsatz);

    }//end erstelleBuchungssatz()

    /**
     * Zahlen mit zwei Nachkommastellen und Einheit darstellen
     *
     * @param integer|float $number Wert, der konvertiert werden soll.
     * @param string        $unit   Array- Key für die Maßeinheit in
     *                              $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe'][key].
     *
     * @return string     formatierter String
     */
    public function formatedNumber($number, string $unit)
    {
        $return = number_format(
            $number,
            2,
            $GLOBALS['TL_LANG']['MSC']['decimalSeparator'],
            $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']
        );

        $return .= '&nbsp;';
        $return .= $GLOBALS['TL_LANG']['MSC']['nlsh_gesamtausgabe'][$unit];

        return $return;

    }//end formatedNumber()

}//end class
