<?php

namespace Mrgla55\Tabapi\Interfaces;

interface WebServerInterface extends AuthenticationInterface
{
    /**
     * Send callback for Web Server flow.
     * Should return null if flow doesn't need the callback function.
     *
     * @return array
     */
    public function callback();
}
