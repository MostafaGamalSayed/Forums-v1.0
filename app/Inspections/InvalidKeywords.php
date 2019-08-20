<?php
namespace App\Inspections;


class InvalidKeywords
{

    /**
    * All the registered invalid keywords
    *
    * @var array
    */
    protected $keywords = [
      'yahoo customer support'
    ];


    /**
    * Detect inavalid keywords spam.
    *
    * @param string $body
    * @throws \Exception
    */
    public function detect($body)
    {
      foreach ($this->keywords as $keyword) {
        if (stripos($body, $keyword) !== false) {
          throw new \Exception("Your Reply contains spam.");
        }
      }
    }
}
