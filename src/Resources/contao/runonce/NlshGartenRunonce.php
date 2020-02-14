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
        $this->updateTlNlshGartenConfig();

    }//end run()

    /**
     * Nach der Umstellung der Tabelle 'tl_nlsh_garten_config'
     * als Kind- Tabelle von 'tl_nlsh_garten_verein_stammdaten'
     * müssen die richtigen pid`s in 'tl_nlsh_garten_config'
     * eingetragen werden
     *
     * @return void
     */
    private function updateTlNlshGartenConfig()
    {
        if ($this->Database->fieldExists('pid', 'tl_nlsh_garten_config') !== true) {
            echo 'nils';
        }

    }//end updateTlNlshGartenConfig()

}//end class

/*
 * Controller initialisieren
 */

$objNlshGartenRunonce = new NlshGartenRunonce();
$objNlshGartenRunonce->run();
