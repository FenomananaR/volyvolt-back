<?php

namespace App\Service;

class RandomW
{
    private $customRandom;

    public function __construct(CustomRandom $customRandom)
    {
        $this->customRandom = $customRandom;
    }
    

    public function getNewWCId(array $allClientId)
    {
        return $this->customRandom->getRandom('WCI', $allClientId, 99999, 10000);
    }
    public function getNewWAId(array $allAppareilId)
    {
        return $this->customRandom->getRandom('WA', $allAppareilId, 99999999, 10000000);
    }
  
}
