<?php

namespace Mrgla55\Tabapi\Providers\Laravel;

use Illuminate\Http\Request;
use Mrgla55\Tabapi\Interfaces\InputInterface;

class LaravelInput implements InputInterface
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Get input from response.
     *
     * @param string $parameter
     *
     * @return mixed
     */
    public function get($parameter)
    {
        return $this->request->input($parameter);
    }
}
