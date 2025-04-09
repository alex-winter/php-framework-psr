<?php

namespace AlexWinter\Framework;

class JsonResponse extends Response
{
    public function __construct(mixed $data, int $status = 200, array $headers = [])
    {
        $jsonData = json_encode($data);
        
        $headers['Content-Type'] = ['application/json'];

        parent::__construct(statusCode: $status, headers: $headers);

        $this->getBody()->write($jsonData);
    }
}