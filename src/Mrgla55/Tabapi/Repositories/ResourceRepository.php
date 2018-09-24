<?php

namespace Mrgla55\Tabapi\Repositories;

use Mrgla55\Tabapi\Interfaces\ResourceRepositoryInterface;
use Mrgla55\Tabapi\Interfaces\StorageInterface;
use Mrgla55\Tabapi\Exceptions\MissingResourceException;

class ResourceRepository implements ResourceRepositoryInterface
{
    protected $storage;

    public function __construct(StorageInterface $storage) {
        $this->storage  = $storage;
    }

    public function put($resource)
    {
        $this->storage->put('resources', $resource);
    }

    public function has()
    {
        return $this->storage->has('resources');
    }

    public function get($resource) {
        $this->verify();

        return $this->storage->get('resources')[$resource];
    }

    private function verify() {
        if ($this->storage->has('resources')) return;

        throw new MissingResourceException('No resources available');
    }
}
