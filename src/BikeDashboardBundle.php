<?php

namespace Bike\Dashboard;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BikeDashboardBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new DependencyInjection\BikeDashboardExtension();
        }

        return $this->extension;
    }
}
