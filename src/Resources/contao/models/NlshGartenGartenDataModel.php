<?php
/**
 * Class NlshGartenGartenDataModel
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
namespace Contao;

/**
 * Class NlshGartenGartenDataModel
 */
class NlshGartenGartenDataModel extends \Model
{

    /**
     * Name of the table
     *
     * @var string
     */
    protected static $strTable = 'tl_nlsh_garten_garten_data';

    /**
     * Doppelt vergebene Gärten herausfinden
     *
     * @param integer $gartenPid ID des Gartens.
     *
     * @return array  Array mit den doppelt vergebenen Gärten
     */
    public static function findDoubleGarten(int $gartenPid)
    {
        $return           = array();
        $gartenGartenData = static::findBy(
            'pid',
            $gartenPid,
            array('order' => '`nutzung_user_id` ASC, `nr` ASC')
        );
        if ($gartenGartenData !== null) {
            while ($gartenGartenData->next()) {
                $newArr[] = $gartenGartenData->row();
            }

            $count = count($newArr);

            for ($i = 0; $i < $count; $i++) {
                if ((       $newArr[$i]['nutzung_user_id'] === $newArr[($i - 1)]['nutzung_user_id'])
                    && ($newArr[$i]['nutzung_user_id'] !== '0')
                ) {
                    $return[$newArr[$i]['nr']] = 1;
                }
            }
        }

        return $return;

    }//end findDoubleGarten()

}//end class
