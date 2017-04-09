<?php

/**
 * Namespace
 */
namespace Contao;


/**
 * Class NlshGartenGartenDataModel
 *
 * @copyright Nils Heinold (c) 2013
 * @author    Nils Heinold
 * @package   nlsh_kleingartenverwaltung-bundle
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */
class NlshGartenGartenDataModel extends \Model
{

    /**
     * Name of the table
     * @var string
     */
    protected static $strTable = 'tl_nlsh_garten_garten_data';


    /**
     * Doppelt vergebene Gärten herausfinden
     *
     * @param   int    $gartenPid ID des Gartens
     *
     * @return  array  Array mit den doppelt vergebenen Gärten
     */
    public static function findDoubleGarten($gartenPid) {
        $gartenGartenData = static::findBy(
                                        'pid',
                                        $gartenPid,
                                        array('order' => '`nutzung_user_id` ASC, `nr` ASC'));

        while ($gartenGartenData->next()) {
            $newArr[] = $gartenGartenData->row();
        }

        $count = count($newArr);

        for ($i = 0; $i < $count; $i++) {
            if ((       $newArr[$i]['nutzung_user_id'] == $newArr[$i - 1]['nutzung_user_id'])
                    && ($newArr[$i]['nutzung_user_id'] == TRUE)) {
                $return[$newArr[$i]['nr']] = 1;
            }
        }

        return $return;
    }
}
