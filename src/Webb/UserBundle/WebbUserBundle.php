<?php

namespace Webb\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebbUserBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
