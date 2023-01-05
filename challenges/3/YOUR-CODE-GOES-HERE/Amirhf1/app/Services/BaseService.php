<?php

namespace App\Services;

class BaseService
{
    /**
     * BaseService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param mixed|null $data
     *
     * @return array
     */
    public function success($data = null)
    {
        return $this->generateResponse(true, $data);
    }

    /**
     * @param mixed|null $data
     *
     * @return array
     */
    public function error($data = null)
    {
        return $this->generateResponse(false, $data);
    }

    /**
     * @param boolean $status
     * @param mixed $data
     *
     * @return array
     */
    private function generateResponse(bool $status, $data): array
    {
        return [
            'status' => $status,
            'data' => $data,
        ];
    }
}
