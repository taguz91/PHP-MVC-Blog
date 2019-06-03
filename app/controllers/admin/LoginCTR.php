<?php
  namespace App\Controllers\Admin;
  use App\Controllers\BaseCTR;
  use Sirius\Validation\Validator;
  use App\Models\User;

  class LoginCTR extends BaseCTR {
    public function getLogin(){
      return $this->render('login.twig');
    }

    public function postLogin(){
      $validador = new Validator();
      $validador->add('email', 'required');
      $validador->add('email', 'email');
      $validador->add('pass', 'required');

      if ($validador->validate($_POST)) {
        $user = User::where('email',
        $_POST['email'])->first();
        if ($user) {
          if(password_verify($_POST['pass'], $user->password)){
            // Logramos entrar
            $_SESSION['userID'] = $user->id;
            header('Location: '.BASE_URL.'admin');
            return null;
          }
        }
        // No entramos
        $validador->addMessage('email', 'No existe el usuario o la contraseña es incorrecta.');
      }

      $errores = $validador->getMessages();

      return $this->render('login.twig', [
        'errores' => $errores
      ]);
    }

    public function getLogout() {
      unset($_SESSION['userID']);
      header('Location: '.BASE_URL.'auth/login');
    }

  }

 ?>
