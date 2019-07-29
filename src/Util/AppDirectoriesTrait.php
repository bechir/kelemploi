<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Util;

use Symfony\Component\HttpKernel\KernelInterface;

trait AppDirectoriesTrait
{
    private $rootDir;

    public function __construct(KernelInterface $kernelInterface)
    {
        $this->rootDir = $kernelInterface->getProjectDir();
    }

    public function getRootDir()
    {
        return $this->rootDir;
    }

    public function getVarApp()
    {
        return $this->rootDir . '/var/app';
    }
}
