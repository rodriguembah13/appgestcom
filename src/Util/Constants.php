<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Util;

/**
 * Some "very" global constants for Geste.
 */
class Constants
{
    /**
     * The current release version
     */
    public const VERSION = '1.2';
    /**
     * The current release status, either "stable" or "dev"
     */
    public const STATUS = 'stable';
    /**
     * The software name
     */
    public const SOFTWARE = 'Geste';
    /**
     * The release name, will only change for new major version
     */
    public const NAME = 'Mbah rodrigue';
    /**
     * Used in multiple views
     */
    public const GITHUB = '#';
    /**
     * Homepage, used in multiple views
     */
    public const HOMEPAGE = '#';
    /**
     * Application wide default locale.
     */
    public const DEFAULT_LOCALE = 'fr';
}
