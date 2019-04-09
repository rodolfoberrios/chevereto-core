<?php

declare(strict_types=1);

namespace App;

use Chevereto\Core\Controller;

return new class() extends Controller {
    const DESCRIPTION = 'Obtiene un usuario.';

    public function __construct(User $user)
    {
    }

    public function __invoke()
    {
        dd('user', $this->user);
    }
};
