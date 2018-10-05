<?php
namespace App\Traits;

trait EmptyStringToNull
{
  /**
   * return null value if the string is empty otherwise it returns what every the value is
   *
  */
  public function setAttribute($key, $value)
  {
    if (is_scalar($value) && ! is_bool($value)) {
      if (trim($value) === '') $value = null;
    }

    return parent::setAttribute($key, $value);
  }

  private function emptyStringToNull($value)
  {
    if (is_scalar($value) && ! is_bool($value)) {
      //trim every value
      $value = trim($value);

      if ($value === ''){
        return null;
      }
    }

    return $value;
  }
}