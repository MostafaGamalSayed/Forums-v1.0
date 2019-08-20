<?php
namespace App\Inspections;
use App\Inspections\InvalidKeywords;
use App\Inspections\KeyHeldDown;

class Spam
{
  /**
  * Registered classes of spam detections
  *
  * @var array
  */
  protected $inspections = [
    InvalidKeywords::class,
    KeyHeldDown::class
  ];

  /**
  * Detect spams
  *
  * @param string $body
  */
  public function detect($body) {    
    foreach ($this->inspections as $inspection) {
      app($inspection)->detect($body);
    }

    return false;
  }
}
