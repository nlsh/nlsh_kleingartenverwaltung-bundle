<?php
/**
 * Erweiterung des tl_member DCA`s
 *
 * @copyright Nils Heinold (c) 2017
 * @author    Nils Heinold
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */


/**
 * Table tl_member
 */
$GLOBALS['TL_DCA']['tl_member']['palettes']['__selector__'][] = 'nlsh_member_sonderangaben';

foreach ($GLOBALS['TL_DCA']['tl_member']['palettes'] as $k => $v) {
        if ($k != '__selector__') {
                if (strstr($v, '{groups_legend')) {
                        $GLOBALS['TL_DCA']['tl_member']['palettes'][$k] = str_replace(
                                    '{groups_legend',
                                    '{nhls_member_legend},
                                        nlsh_member_anrede,
                                        nlsh_member_anrede_2,
                                        nlsh_member_pacht_ja_nein,
                                        nlsh_member_beitrag_ja_nein;
                                    {groups_legend',
                                    $v
                        );
                } elseif (strstr($v, 'urchinId;')) {
                        $GLOBALS['TL_DCA']['tl_member']['palettes'][$k] = str_replace(
                                    'urchinId;',
                                    'urchinId;
                                    {nhls_member_legend},
                                        nlsh_member_anrede,
                                        nlsh_member_anrede_2,
                                        nlsh_member_pacht_ja_nein,
                                        nlsh_beitrag_ja_nein;',
                                    $v
                        );
                } else {
                        $GLOBALS['TL_DCA']['tl_member']['palettes'][$k] = str_replace(
                                    'urchinId,',
                                    'urchinId;
                                    {nhls_member_legend},
                                        nlsh_member_anrede,
                                        nlsh_member_anrede_2,
                                        nlsh_member_pacht_ja_nein,
                                        nlsh_beitrag_ja_nein;',
                                    $v
                        );
                }
        }
}


/**
 * Add fields to tl_member
 */
$GLOBALS['TL_DCA']['tl_member']['fields']['nlsh_member_anrede'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_member']['nlsh_member_anrede'],
    'inputType'        => 'select',
    'options'          => $GLOBALS['TL_LANG']['tl_member']['nlsh_member_anrede_kurz'],
    'save_callback'    => array(array(
                                'tl_member_nlsh_garten',
                                'saveAnrede2')
                          ),
    'eval'             => array(
                                'tl_class'         => 'w50',
                                'mandatory'        => TRUE,
                                'maxlength'        => 40,
                                'submitOnChange'   => 'TRUE'
                         ),
    'sql'              => "varchar(40) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['nlsh_member_anrede_2'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_member']['nlsh_member_anrede_2'],
    'inputType'        => 'text',
    'eval'             => array('tl_class' => 'w50', 'maxlength' => 40, 'readonly' => TRUE),
    'sql'              => "varchar(40) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['nlsh_member_pacht_ja_nein'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_member']['nlsh_member_pacht_ja_nein'],
    'inputType'        => 'checkbox',
    'eval'             => array('tl_class' => 'w50'),
    'sql'              => "char(1) NOT NULL default '1'"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['nlsh_member_beitrag_ja_nein'] = array
(
    'label'            => &$GLOBALS['TL_LANG']['tl_member']['nlsh_member_beitrag_ja_nein'],
    'inputType'        => 'checkbox',
    'eval'             => array('tl_class' => 'w50'),
    'sql'              => "char(1) NOT NULL default '1'"
);


/**
 * DCA- Klasser der Tabelle tl_member_nlsh_garten
 *
 * @package   nlshKleingartenverwaltung
 */

/**
 * Class tl_member_nlsh_garten
 *
 * @copyright Nils Heinold (c) 2013
 * @author    Nils Heinold
 * @package   nlshKleingartenverwaltung
 * @link      https://github.com/nlsh/nlsh_Kleingartenverwaltung
 * @license   LGPL
 */
class tl_member_nlsh_garten extends \Backend
{


    /**
     * Den Backenduser importieren
     *
     * Contao- Core Funktion
     */
    public function __construct() {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }


    /**
     * Ausführliche Anrede in seperatem Feld speichern
     *
     *  Erweiterung der Anrede in einem seperaten Datenbankfeld speichern:
     *
     * ( aus Frau wird -> Sehr geehrte Frau)
     *
     * ( aus Herr wird -> Sehr geehrter Herr, )
     *
     * save_callback des Feldes Anrede
     *
     * @param  string          $field Gewählte Anrede
     * @param  \DataContainer  $dc Contao DataContainer- Objekt
     *
     * @return string          gewählte Anrede
     */
    public function saveAnrede2($field, \DataContainer $dc) {
        if ($field == $GLOBALS['TL_LANG']['tl_member']['nlsh_member_anrede_kurz'][0]) {
            $text = $GLOBALS['TL_LANG']['tl_member']['nlsh_member_anrede_lang'][0];
        }

        if ($field == $GLOBALS['TL_LANG']['tl_member']['nlsh_member_anrede_kurz'][1]) {
            $text = $GLOBALS['TL_LANG']['tl_member']['nlsh_member_anrede_lang'][1];
        }

        if ($field == $GLOBALS['TL_LANG']['tl_member']['nlsh_member_anrede_kurz'][2]) {
            $text = $GLOBALS['TL_LANG']['tl_member']['nlsh_member_anrede_lang'][2];
        }

        $this->Database->prepare("
                                    UPDATE      `tl_member`
                                    SET         `nlsh_member_anrede_2` = ?
                                    WHERE       `tl_member`.`id` = ?")
                    ->execute($text, $dc->id);

        return $field;
    }
}