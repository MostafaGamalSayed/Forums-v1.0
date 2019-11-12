<?php
namespace App\Inspections;

class KeyHeldDown
{

  /**
  * Detect helddown spams
  *
  * @param string $body
  * @throws \Exception
  */

  public function detect($body)
  {
    if (preg_match('/(.)\\1{7,}/', $body)) {
      throw new \Exception("Your Reply contains spam.");

    }
  }
}
