<?php
/**
 * This file is part of nlsh/nlsh_kleingartenverwaltung-bundle.
 * (c) Nils Heinold
 *
 * @package   nlsh/nlsh_kleingartenverwaltung-bundle
 * @author    Nils Heinold
 * @copyright Nils Heinold (c) 2020
 * @link      https://github.com/nlsh/nlsh_kleingartenverwaltung-bundle
 * @license   LGPL
 */

namespace Nlsh\KleingartenverwaltungBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

/**
 * Plugin for the Contao Manager.
 */
class Plugin implements BundlePluginInterface
{

    /**
     * {@inheritdoc}
     *
     * @return none
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create('Nlsh\KleingartenverwaltungBundle\NlshKleingartenverwaltungBundle')
                ->setLoadAfter(['Contao\CoreBundle\ContaoCoreBundle'])
                ->setReplace(['kleingartenverwaltung']),
        ];

    }//end getBundles()

}//end class
