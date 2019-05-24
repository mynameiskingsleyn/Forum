<?php

namespace Forum\Inspection;

use Exception;

class KeyHeldDown
{
    public function detect($body)
    {
        if (preg_match('/(.)\1{4,}/', $body)) {
            throw new \Exception('key held down');
        }
    }
}
