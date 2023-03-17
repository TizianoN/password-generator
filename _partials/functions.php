<?php 
function generate_password($allowed_chars, $length, $allow_repetitions) {
  $password = "";

  // finché la password non è abbastanza lunga
  while(strlen($password) < $length) {

    // recupero un indice casuale dei caratteri permessi
    $rand_char_index = rand(0, strlen($allowed_chars) - 1);

    // recupero il carattere corrispondente
    $rand_char = $allowed_chars[$rand_char_index];

    // se sono permesse le ripetizioni o se non è una ripetizione lo aggiungo alla password
    if($allow_repetitions || !str_contains($password, $rand_char)) {
      $password .= $rand_char;
    }
  }

  // restituisco la password generata
  return $password;
};