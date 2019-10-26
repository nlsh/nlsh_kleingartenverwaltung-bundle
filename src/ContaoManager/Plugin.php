<?php

/*
 * This file is part of nlsh/nlsh_kleingartenverwaltung-bundle.
 * (c) Nils Heinold
 * @license LGPL-3.0-or-later
 */

namespace Nlsh\KleingartenverwaltungBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

/**
 * Plugin for the Contao Manager.
 *
 * @author Andreas Schempp <https://github.com/aschempp>
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create('Nlsh\KleingartenverwaltungBundle\NlshKleingartenverwaltungBundle')
                ->setLoadAfter(['Contao\CoreBundle\ContaoCoreBundle'])
                ->setReplace(['kleingartenverwaltung']),
        ];
    }
}
