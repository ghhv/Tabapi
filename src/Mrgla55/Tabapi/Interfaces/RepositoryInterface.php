<?php

namespace Mrgla55\Tabapi\Interfaces;

interface RepositoryInterface
{
    public function get();
    public function has();
    public function put($item);
}
