<?php 
/**
  Descrizione
  Dobbiamo creare una pagina che permetta ai nostri utenti di utilizzare il nostro generatore di password 
  (abbastanza) sicure. L'esercizio è suddiviso in varie milestone ed è molto importante svilupparle 
  in modo ordinato.

  Milestone 1
  Creare un form che invii in GET la lunghezza della password. Una nostra funzione utilizzerà questo 
  dato per generare una password casuale (composta da lettere, lettere maiuscole, numeri e simboli) 
  da restituire all'utente. Scriviamo tutto (logica e layout) in un unico file *index.php*

  Milestone 2
  Verificato il corretto funzionamento del nostro codice, spostiamo la logica in un file *functions.php* 
  che includeremo poi nella pagina principale

  Milestone 3 (BONUS)
  Invece di visualizzare la password nella index, effettuare un redirect ad una pagina dedicata che tramite 
  $_SESSION recupererà la password da mostrare all'utente.

  Milestone 4 (BONUS)
  Gestire ulteriori parametri per la password: quali caratteri usare fra numeri, lettere e simboli. 
  Possono essere scelti singolarmente (es. solo numeri) oppure possono essere combinati fra loro (es. numeri e 
  simboli, oppure tutti e tre insieme).

  Dare all'utente anche la possibilità di permettere o meno la ripetizione di caratteri uguali.
  
*/

require_once(__DIR__ . "/_partials/functions.php");

if(!empty($_GET)) {
  // lista dei caratteri 
  $alphabeth = "abcdefghijklmnopqrstuwxyz";
  $alphabeth_uc = strtoupper($alphabeth);
  $numbers = "1234567890";
  $specials = "!$%&()=[]{}-_";
  
  // dichiaro caratteri permessi 
  $allowed_chars = "";

  // aggiungo la lista dei caratteri permessi
  if(isset($_GET["allow_alphabeth_lc"])) $allowed_chars .= $alphabeth;
  if(isset($_GET["allow_alphabeth_uc"])) $allowed_chars .= $alphabeth_uc;
  if(isset($_GET["allow_numbers"])) $allowed_chars .= $numbers;
  if(isset($_GET["allow_specials"])) $allowed_chars .= $specials;
  
  // se è vuota sono tutti permessi
  if(empty($allowed_chars)) $allowed_chars = $alphabeth . $alphabeth_uc . $numbers . $specials;

  // recupero le altre informazioni inviate
  $password_length = (int) $_GET["password_length"] ?? 5; 
  $allow_repetitions = isset($_GET["allow_repetitions"]) ? true : false;

  // controllo di non finire in un loop infinito se le ripetizioni non sono permesse e non ci sono abbastanza caratteri
  if(
    !$allow_repetitions && 
    ($password_length > strlen($allowed_chars))
  ) {
    // si potrebbe invece stampare un messaggio di errore ma forziamo i permessi delle ripetizioni
    $allow_repetitions = true;
  }
  
  // genero la password e inviamo il risultato alla pagina results
  session_start();
  $_SESSION["generated_password"] = generate_password($allowed_chars, $password_length, $allow_repetitions);
  header("Location: ./result.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password generator</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
  <main>
    <div class="container">

      <div class="row justify-content-center">
        <div class="col-7">

          <div class="card my-5">
            <div class="card-header">
              Password Generator
            </div>

            <div class="card-body">
              <?php if(!isset($generated_password)) : ?>

              <form method="GET" class="row">
                <div class="col-12">

                  <div class="row">
                    <div class="col-4">
                      <div class="mb-3">
                        <label for="password_length" class="form-label">Lunghezza password</label>
                      </div>
                    </div>
                    <div class="col-8">
                      <input type="number" class="form-control" id="password_length" name="password_length" />
                    </div>
                  </div>

                  <hr>

                  <div class="row">
                    <div class="col-4">
                      <div class="mb-3">
                        <label for="password_length" class="form-label">Caratteri permessi</label>
                      </div>
                    </div>
                    <div class="col-8">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="allow_alphabeth_lc"
                          name="allow_alphabeth_lc">
                        <label class="form-check-label" for="allow_alphabeth_lc">
                          Alfabeto minuscolo
                        </label>
                      </div>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="allow_alphabeth_uc"
                          name="allow_alphabeth_uc">
                        <label class="form-check-label" for="allow_alphabeth_uc">
                          Alfabeto maiuscolo
                        </label>
                      </div>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="allow_numbers"
                          name="allow_numbers">
                        <label class="form-check-label" for="allow_numbers">
                          Numeri
                        </label>
                      </div>

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="allow_specials"
                          name="allow_specials">
                        <label class="form-check-label" for="allow_specials">
                          Caratteri speciali
                        </label>
                      </div>
                    </div>
                  </div>

                  <hr>

                  <div class="row">
                    <div class="col-4">
                      <div class="mb-3">
                        <label for="password_length" class="form-label">Ripetizioni</label>
                      </div>
                    </div>
                    <div class="col-8">

                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="allow_repetitions"
                          name="allow_repetitions">
                        <label class="form-check-label" for="allow_repetitions">
                          Permetti
                        </label>
                      </div>

                    </div>
                  </div>

                  <hr>

                  <div class="row">
                    <div class="col-4">
                      <div class="mb-3">
                        <button class="btn btn-primary w-100">Genera password</button>
                      </div>
                    </div>
                  </div>

                </div>
              </form>
              <?php else : ?>

              <div class="alert alert-success" role="alert">
                La password è stata generata correttamente: <br />
                <strong><?= $generated_password ?></strong>
              </div>

              <?php endif; ?>
            </div>
          </div>

        </div>
      </div>


    </div>
  </main>
</body>

</html>