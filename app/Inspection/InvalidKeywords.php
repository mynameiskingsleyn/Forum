<?php

namespace Forum\Inspection;

use Exception;

class InvalidKeywords
{
    protected $invalidKeywords = [
    'yahoo customer support'
    ];
    public function detect($body)
    {
        foreach ($this->invalidKeywords as $keywords) {
            if (stripos($body, $keywords) !== false) {
                throw new Exception('Scam was detected in your message');
            }
        }
    }
}
