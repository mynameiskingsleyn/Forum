<?php
namespace Forum\Inspection;

class Spam
{
    protected $inspections = [
      InvalidKeywords::class,
      KeyHeldDown::class

    ];
    public function detect($body)
    {
        //Detect invalid keywords
        //  $this->detectInvalidKeywords($body);
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }
        //$this->detectKeyHeldDown($body);

        return false;
    }
}
