<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula Foundation - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\ThemeModule\Engine\Asset;

use Zikula\ThemeModule\Engine\AssetBag;

/**
 * Class JsResolver
 *
 * This class compiles all js page assets into proper html code for inclusion into a page header or footer
 */
class JsResolver implements ResolverInterface
{
    /**
     * @var AssetBag
     */
    private $bag;

    /**
     * @var MergerInterface
     */
    private $merger;

    /**
     * @var bool
     */
    private $combine;

    public function __construct(
        AssetBag $bag,
        MergerInterface $merger,
        string $env = 'prod',
        bool $combine = false
    ) {
        $this->bag = $bag;
        $this->merger = $merger;
        $this->combine = 'prod' === $env && $combine;
    }

    public function compile(): string
    {
        $assets = $this->bag->allWithWeight();
        if ($this->combine) {
            $assets = $this->merger->merge($assets);
        }
        $headers = '';
        foreach ($assets as $asset) {
            $headers .= '<script src="' . $asset . '"></script>' . "\n";
        }

        return $headers;
    }

    public function getBag(): AssetBag
    {
        return $this->bag;
    }
}
