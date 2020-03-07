<?php
   /**
    * NlshGartenRunonce
    *
    * Einmalige Anpassung an eine neue Version
    *
    * @package   nlsh/nlsh_kleingartenverwaltung-bundle
    * @author    Nils Heinold
    * @copyright Nils Heinold (c) 2020
    * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
    * @license   LGPL
    */

   /**
    * Namespace
    */
namespace Nlsh\KleingartenverwaltungBundle;

use Contao\System;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Validator\Constraints\Time;
use Contao\Controller;

   /**
    * Einmalige Anpassung an eine neue Version
    */
class NlshGartenRunonce extends Controller
{

    /**
     * Objekt initialisieren
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('Database');

    }//end __construct()

    /**
     * Controller starten
     *
     * @return void
     */
    public function run()
    {
        // Update der neuen Kindstabelle 'tl_nlsh_garten_config'.
        $this->updateNlshGartenConfig();

    }//end run()

    /**
     * Nach der Umstellung der Tabelle 'tl_nlsh_garten_config'
     * als Kind- Tabelle von 'tl_nlsh_garten_verein_stammdaten'
     * müssen die richtigen pid`s in 'tl_nlsh_garten_config'
     * eingetragen werden
     *
     * @return void
     */
    private function updateNlshGartenConfig()
    {
         // Kontrolle, ob Elterntabelle vorhanden (Neuinstallation).
        if ($this->Database->tableExists('tl_nlsh_garten_config') === true) {
             // Wenn die Spalte 'pid' nicht vorhanden, dann Update.
            if ($this->Database->fieldExists('pid', 'tl_nlsh_garten_config') !== true) {
                 // Spalte 'pid' anlegen .
                $this->Database->execute('ALTER TABLE tl_nlsh_garten_config ADD pid INT UNSIGNED DEFAULT 0 NOT NULL  AFTER `id`');

                 // Indizieren.
                $this->Database->execute('CREATE INDEX pid ON tl_nlsh_garten_config (pid)');

                 // Eltern- Tabelle 'tl_nlsh_garten_verein_stammdaten' einlesen.
                $objGartenVereinStammdaten = $this->Database->execute('SELECT * FROM `tl_nlsh_garten_verein_stammdaten` ORDER BY `tl_nlsh_garten_verein_stammdaten`.`jahr` DESC ');

                 // Kinds- Tabelle 'tl_nlsh_garten_config' mit Eltern- Tabelle `tl_nlsh_garten_verein_stammdaten` synchronisieren.
                while ($objGartenVereinStammdaten->next()) {
                    // Kontrolle, ob Eintrag in 'tl_nlsh_garten_config' für das Stammdatenjahr existiert.
                    $objGartenConfig      = $this->Database->prepare('SELECT * FROM `tl_nlsh_garten_config` WHERE `jahr` LIKE ? ORDER BY `jahr` ASC ')->execute($objGartenVereinStammdaten->jahr);
                    $objGartenConfigCount = $objGartenConfig->count();

                     // Wenn Datensatz vorhanden.
                    if ($objGartenConfigCount > 0) {
                        $this->Database->prepare('UPDATE `tl_nlsh_garten_config` SET `pid` = ? WHERE `tl_nlsh_garten_config`.`id` = ?; ')->execute($objGartenVereinStammdaten->id, $objGartenConfig->id);
                    } else {
                        $this->Database->prepare('INSERT INTO `tl_nlsh_garten_config` (`id`, `pid`, `tstamp`, `jahr`) VALUES (NULL, ?, ?, ?)')->execute($objGartenVereinStammdaten->id, time(), $objGartenVereinStammdaten->jahr);
                    }
                }
            }//end if
        }//end if

    }//end updateNlshGartenConfig()

}//end class

/*
 * Controller initialisieren
 */

$objNlshGartenRunonce = new NlshGartenRunonce();
$objNlshGartenRunonce->run();
