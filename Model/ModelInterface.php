<?php

namespace Tersoal\DynaMapBundle\Model;

interface ModelInterface
{
    /**
     * @return string
     */
    public function getDynaName();

    /**
     * @return string
     */
    public function getDynaSlug();
}
