<?php

namespace SpiritDev\Bundle\DBoxUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpiritDevDBoxUserBundle extends Bundle {
    public function getParent() {
        return 'FOSUserBundle';
    }
}
