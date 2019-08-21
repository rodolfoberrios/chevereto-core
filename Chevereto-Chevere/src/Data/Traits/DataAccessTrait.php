<?php

declare(strict_types=1);

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevereto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chevere\Data\Traits;

use Chevere\Data\Data;
use Chevere\Contracts\DataContract;

trait DataAccessTrait
{
    /** @var DataContract */
    private $data;

    public function data(): Data
    {
        return $this->data;
    }
}