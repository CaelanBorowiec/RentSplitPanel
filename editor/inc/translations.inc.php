<?php
if(!defined('IncludesAllowed'))
  die('Direct access not permitted');

class Translations
{
  public function get_bill_status($name, $cost, $payment)
  {
    $cost = (float)$cost;
    $payment = (float)$payment;

    if ($cost <= 0)
      return "None required.";

    if ($cost - $payment <= 0)
      return $name . " paid.";

    return $name . " due: $" . number_format($cost - $payment, 2);
  }
}
