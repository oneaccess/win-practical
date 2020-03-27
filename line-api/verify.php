<?php 
  $access_token = '5PEITvKLXM9jp/t7iNs+U56eq4tV+kp13AuveYhh9Kj36A41neuftxaScKfrTfFWuY5dCRXrozUlssJQTWxxqpwj9lfXuF/IHWtttqi+HTQt35BfZP3b5LjJGFHK1hxXURsZuv7e+smCmQP6nI0pwgdB04t89/1O/w1cDnyilFU=';
  $url = 'https://api.line.me/v1/oauth/verify';
  $headers = array('Authorization: Bearer ' . $access_token);
  $ch = curl_init($url);curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $result = curl_exec($ch);curl_close($ch);
  echo $result;
?>
