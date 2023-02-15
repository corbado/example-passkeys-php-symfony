<?php
namespace App\Exception;

use Throwable;

class ApiErrorException extends \Exception
{
    private array $apiResponse;

    public function __construct($message, array $apiResponse, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->apiResponse = $apiResponse;
    }

    /**
     * @return array
     */
    public function getApiResponse(): array
    {
        return $this->apiResponse;
    }
}
