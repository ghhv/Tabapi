<?php

namespace Mrgla55\Tabapi\Interfaces;

interface EventInterface
{
    /**
     * Fire an event and call the listeners.
     *
     * @param string $event
     * @param mixed  $payload
     * @param bool   $halt
     *
     * @return array|null
     */
    public function fire($event, $payload = [], $halt = false);
}
