<?php
   /**
    * NlshDatevDtvfStandardFormatCreater
    *
    * Erzeugt eine DATEV DTVF - Standard Datei zum Einlesen
    * über die Stapelverarbeitung in das
    * Rechnungswesenprogramm der DATEV e.G.
    *
    * @package   nlsh_DatevDtvfStandardFormatCreator
    * @author    Nils Heinold
    * @copyright Nils Heinold (c) 2018
    * @link      https://github.com/nlsh/nlsh_DatevDtvfStandardFormatCreator
    * @license   LGPL
    */

   /**
    * Namespace
    */
namespace Nlsh\KleingartenverwaltungBundle;


/**
 * Erzeugt ein Objekt zur Ausgabe einer DATEV Stapelverarbeitungsdatei
 *
 * Diese Klasse erzeugt ein Objekt zur Ausgabe einer Datei
 * im DATEV DTVF- Standard Format zum Einlesen über die
 * Stapelverarbeitung im Rechnungswesenprogramm der DATEV e.G.
 */
class NlshDatevDtvfStandardFormatCreater
{

    /**
     * Definition der ersten Zeile
     *
     * @var array Enthält die Felder der ersten Zeile
     */
    public $arrFirstLine = array(
        'DatevKz'                   => 'DTVF',
        'Versionsnummer'            => 510,
            // TemplateId
            // 21 -> Buchungsstapel;
            // 16 -> Debitoren/Kreditoren;
            // 20 -> Kontenbeschriftungen usw...
        'TemplateId'                => 21,
        'Formatname'                => 'Buchungsstapel',
        'Formatversion'             => 8,
        'Erzeugt am'                => 0,
        'Importiert am'             => 0,
        'HK	Text'                   => 'NLSH',
        'Exportiert von'            => '',
        'Importiert von'            => '',
        'Berater'                   => 0,
        'Mandant'                   => 0,
        'WjBeginn'                  => 0,
        'Kontolänge'                => 4,
        'Datum von'                 => 0,
        'Datum bis'                 => 0,
        'Bezeichnung'               => '',
        'Diktatkürzel'              => '',
            // Buchungstyp
            // 1 Fibu
            // 2 -> Jahresabschluss.
        'Buchungstyp'               => 1,
        'Bereich'                   => 0,
            // Festschreibung
            // 0 -> nein
            // 1 -> ja.
        'Festschreibeinformationen' => 0,
        'WKZ Text'                  => 'EUR',
        'Kurs'                      => 0,
        'Derivatskennzeichen'       => '',
        'KostSystemnummer'          => 0,
        'Prüfziffer'                => 0,
        'skr Text'                  => '',
        'Branchenlösung'            => 0,
        'DataNumeric'               => 0,
        'DataAlphaNumeric'          => '',
        'Anwendungsinformation'     => '',
    );

    /**
     * Zweite Zeile der DTVF- Datei
     *
     * @var string Enthält die zweite Zeile der DTVF- Datei
     */
    public $strSecondLine = '';

    /**
     * XML Beschreibung
     *
     * @var array XML Beschreibung in ein Array eingelesen
     */
    public $arrDefFromXmlFile = array();

    /**
     * Vorlage der Datenzeile
     *
     * @var array Vorlage für die Erstellung der Datenzeile
     *            Indiziert mit der 'FieldId' der Datev- Definition
     */
    public $arrDataRowTemplate = array();

    /**
     * Daten
     *
     * @var array Datenzeilen
     */
    public $arrData = array();

    /**
     * Klasse initialisieren
     *
     * Den Grundaufbau der Klasse erzeugen
     *
     * @param string $strDefXmlFile Dateiname der zu ladenen Version.
     */
    public function __construct(string $strDefXmlFile='config/V8_DatevFelder.xml')
    {
         // Pfad zur Notfalldefinition setzen.
        if ($strDefXmlFile === 'config/V8_DatevFelder.xml') {
            $strDefXmlFile = rtrim(dirname(__FILE__), 'classes') . $strDefXmlFile;
        }

         // Datev- Felder einlesen.
        $this->initializeArrDefFromXmlFile($strDefXmlFile);

         // Zweite Zeile erzeugen.
        $this->initializeSecondLine();

         // Array für Aufnahme der Daten erzeugen.
        $this->initializeDataRowTemplate();

    }//end __construct()

    /**
     * XML Beschreibung in ein Array einlesen
     *
     * Liest die Daten der XML Beschreibung in ein Array
     * und legt diese in $this->arrDefFromXmlFile ab
     *
     * @param string $strDefXmlFile Dateiname mit Pfad, der zu XML Datei.
     *
     * @throws \InvalidArgumentException Abbruch mit Fehlermessage.
     * @return void
     */
    private function initializeArrDefFromXmlFile(string $strDefXmlFile)
    {
        if (file_exists($strDefXmlFile) === true) {
             // Fehlermeldung ausschalten, um eigenen Fehler zu generieren.
            libxml_use_internal_errors(true);

             // Wenn die Datei existiert, einlesen.
            $objXml = simplexml_load_file($strDefXmlFile);

            if ($objXml !== false) {
                $jsonString              = json_encode($objXml);
                $this->arrDefFromXmlFile = json_decode($jsonString, true);
            } else {
                 // Wenn keine xml- Datei, dann mit Exception und Meldung aussteigen.
                throw new \InvalidArgumentException('Die zu öffnende Datei ' . $strDefXmlFile . ' konnte nicht als xml- Datei eingelesen werden!');
            }
        } else {
            // Wenn Datei nicht existert, dann mit Exception und Meldung aussteigen.
             throw new \InvalidArgumentException('Die zu öffnende Datei ' . $strDefXmlFile . ' konnte nicht gefunden werden!');
        }//end if

    }//end initializeArrDefFromXmlFile()

    /**
     * Zweite Zeile aus der geladenen Definition erzeugen
     *
     * Erzeugt die zweite Zeile der DTVF- Standard- Datei
     * und legt sie in $this->strSecondLine ab
     *
     * @return void
     */
    private function initializeSecondLine()
    {
        foreach ($this->arrDefFromXmlFile['Field'] as $value) {
            $this->strSecondLine .= $value['Label'] . ';';
        }

         // Letztes Semikolon abschneiden.
        $this->strSecondLine = substr($this->strSecondLine, 0, -1);

         // Zeilenende einfügen.
        $this->strSecondLine = $this->strSecondLine . PHP_EOL;

    }//end initializeSecondLine()

    /**
     * Erzeugt die Vorlage für die Erstellung der Datenzeile
     *
     * Erzeugt die Vorlage zur Erstellung der einzelnen Datenzeilen
     *
     * @return void
     */
    private function initializeDataRowTemplate()
    {
        foreach ($this->arrDefFromXmlFile['Field'] as $value) {
             // Feld um den Key des Wertes erweitern.
            $value['value'] = false;
            $this->arrDataRowTemplate[$value['FieldId']] = $value;
        }

    }//end initializeDataRowTemplate()

    /**
     * Erste Zeile bearbeiten
     *
     * Erste Zeile  bearbeiten
     *
     * @param string $key   Name des zu ändernden Key`s.
     * @param mixed  $value Wert des Key`s.
     *
     * @return true | string $return Wenn Fehler, dann mit Fehlermeldung zurück.
     */
    public function editFirstLine(string $key, $value)
    {
         // Kontrolle, ob $key überhaupt existiert.
        if (array_key_exists($key, $this->arrFirstLine) === true) {
            $this->arrFirstLine[$key] = $value;
            return (true);
        } else {
            return ('Der Key ' . $key . ' existiert nicht in der ersten Zeile!');
        }

    }//end editFirstLine()

    /**
     * Erste Zeile bearbeiten
     *
     * Erste Zeile durch Übergabe eines Arrays bearbeiten
     *
     * @param array $arrEditFirstLine Array, indiziert mit dem Key des zu verändernden Key und dem dazugehörigen Wert.
     *
     * @return true | array  Alles ok, oder wenn Fehler, dann mit Fehlermeldungen zurück.
     */
    public function editFirstLineWithArray(array $arrEditFirstLine)
    {
        $return = array();
        foreach ($arrEditFirstLine as $key => $value) {
            $insert = $this->editFirstLine($key, $value);
            if ($insert !== true) {
                $return[] = array(
                    0 => array(
                        $key,
                        $value,
                    ),
                    1 => $insert,
                );
            }
        }

         // Prüfen, ob Fehler existieren.
        if (empty($return) === false) {
            return ($return);
        } else {
            return (true);
        }

    }//end editFirstLineWithArray()

    /**
     * Eine Buchungszeile einlesen
     *
     * Einlesen einer Buchungszeile, wenn alle Felder auch vorhanden sind
     *
     * array(
     *     FieldId => '22,50,
     *     FieldId => 'S'
     * )
     *
     * @param array $arrDataLine Array mit der Datev - 'FieldId' und dem dazugehörigem Wert.
     *
     * @return true | array Alles ok, oder wenn Fehler, dann mit Array aus Fehlermeldungen zurück.
     */
    public function insertDataLine(array $arrDataLine)
    {
        $return = array();

         // Mit Template vorbelegen.
        $arrDataRowTemplate = $this->arrDataRowTemplate;

         // Felder kontrollieren und eintragen.
        foreach ($arrDataLine as $key => $value) {
             // Kontrolle, ob 'FieldId' im Template existiert.
            if (array_key_exists($key, $arrDataRowTemplate) === false) {
                $return[] = 'FieldId ' . $key . ' existiert nicht!';
            }

             // Korrekturen für das Datev Format
             // Prüfen, ob Fehler existieren.
            if (empty($return) === true) {
                 // Da in PHP Zahlen zum Rechnen int oder float (im englischen Format mit Punkt als Dezimaltrenner
                 // weitergegeben werden), müssen diese jetzt ins Deutsche, da DATEV ein Komma möchte.
                if ($arrDataRowTemplate[$key]['FormatType'] === 'Betrag') {
                     // Wenn String ('5.5' ist ein String) zu Sicherheit Punkt mit Komma ersetzen.
                    if (is_string($value) === true) {
                        $value = str_replace('.', ',', $value);
                    }

                     // Float, sind Zahlen mit Punkt, die in String in Komma umwandeln.
                    if (is_float($value) === true) {
                        $value = number_format($value, 2, ',', '');
                    }
                }
            }

             // Jetzt den Wert in das Vorgabe- Template eintragen.
            $arrDataRowTemplate[$key]['value'] = $value;
        }//end foreach

         // Prüfen, ob Fehler existieren.
        if (empty($return) === false) {
            return ($return);
        }

             // Erst wenn keine Fehler, dann Zeile eintragen!
            $this->arrData[] = $arrDataRowTemplate;

            return (true);

    }//end insertDataLine()

    /**
     * Mehrere Buchungszeilen einlesen
     *
     * Einlesen mehrere Buchungszeilen, wenn alle Buchungszeilen korrekt
     *
     * array(
     *  array( FieldId => '22,50,
     *         FieldId => 'S',
     *         ...,
     *         ),
     *  array( FieldId => '19,89',
     *         FieldId => 'H',
     *         ...,
     *         ),
     * )
     *
     * @param array $arrDataArray Array mit Daten mehrer Buchungszeilen.
     *
     * @return true | array Alles ok, oder wenn Fehler, dann mit Fehlermeldungen zurück.
     */
    public function insertDataArray(array $arrDataArray)
    {
        $return    = array();
        $arrInsert = array();

        foreach ($arrDataArray as $value) {
            $insert = $this->insertDataLine($value);
            if ($insert !== true) {
                $return[] = array(
                    0 => array($value),
                    1 => $insert,
                );
            } else {
                $arrInsert[] = $insert;
            }
        }

             // Prüfen, ob Fehler existieren.
        if (empty($return) === false) {
            return ($return);
        }

        return (true);

    }//end insertDataArray()

    /**
     * Die DTVF- Datei ausgeben
     *
     * Gibt die DTVF- Daten aus, entweder als String zurück oder true, wenn als Download
     *
     * @param string $name Teil des Namens der Datei DTVF_Name_.csv, wenn Download,
     *                     leer, wenn Inhalt als String zurück.
     *
     * @return true | string true, wenn wenn Download geklappt,
     *                       String, mit Inhalt der DTVF- Datei.
     */
    public function getOutData(string $name='')
    {
         // Erste Zeile.
        $strReturn = $this->strPutCsv(array($this->arrFirstLine));

         // Zweite Zeile.
        $strReturn .= $this->strSecondLine;

         // Daten eintragen.
         // Jede Daten- Zeile.
        foreach ($this->arrData as $value) {
            $arrRowData = array();

             // Jeden Wert einer Datenzeile einlesen.
            foreach ($value as $wert) {
                 $arrRowData[] = $wert['value'];
            }

            $strReturn .= $this->strPutCsv(array($arrRowData));
        }

         // Wenn als String zurück.
        if ($name === '') {
            return ($strReturn);
        }

          // Download.
         $name = 'DTVF_' . $name . '.csv';

         $this->downloadString($strReturn, $name);
        return(true);

    }//end getOutData()

    /**
     * Datei downloaden
     *
     * @param string $string   Zu downloadender String.
     * @param string $filename Name der Download- Datei.
     *
     * @return false
     */
    private function downloadString(string $string, string $filename)
    {
        if ($string === false) {
            return (false);
        }

         // Nach ANSI wandeln.
        $string = utf8_decode($string);

         // Header schreiben.
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename = "' . $filename . '"');
        header('Content-Length: ' . strlen($string));

         // Und ausgaben.
        echo $string;

         // Und Schluss.
        exit;

    }//end downloadString()

    /**
     * Konvertiert ein Array zu einer CSV Zeile
     *
     * Konvertiert ein Array in eine CSV- gerechte Zeile
     * Der Zeilenumbruch wird auf das abfragende System korrigiert
     *
     * @param array  $data      The array of data.
     * @param string $delimiter Delimiter.
     *
     * @link https://coderwall.com/p/zvzwwa/array-to-comma-separated-string-in-php
     *
     * @return string       CSV text
     */
    private function strPutCsv(array $data, string $delimiter=';')
    {
         // File- Handle im PHP- Speicher öffnen.
        $fh = fopen('php://memory', 'rw');

         // Daten aus Array hineinschreiben.
        foreach ($data as $row) {
             fputcsv($fh, $row, $delimiter);
        }

         // Dateizeiger auf Anfang setzen.
        rewind($fh);

         // Datei einlesen.
        $csv = stream_get_contents($fh);

         // File- Handle wieder schließen.
        fclose($fh);

        // Zeilenumbruch abschneiden.
        $csv = trim($csv);

         // Zeilenumbruch auf System setzen.
        $csv = $csv . PHP_EOL;

        return $csv;

    }//end strPutCsv()

}//end class
