<?php
  namespace App\Controllers\Admin;
  use App\Controllers\BaseCTR;
  use App\Models\User;
  use Sirius\Validation\Validator;

  class UserCTR extends BaseCTR {
    public function getIndex(){
      $users = User::all();
      return $this->render('admin/users.twig', [
        'users' => $users
      ]);
    }

    public function getCreate() {
      return $this->render('admin/ingresar-user.twig');
    }

    public function postCreate() {
      $errores = [];
      $r = false;

      $validador = new Validator();
      $validador->add('nombre', 'required');
      $validador->add('email', 'required');
      $validador->add('email', 'email');
      $validador->add('pass', 'required');

      if ($validador->validate($_POST)) {
        $user = new User();
        $user->name = $_POST['nombre'];
        $user->email = $_POST['email'];
        $user->password = password_hash($_POST['pass'],
        PASSWORD_DEFAULT);

        $user->save();
        $r = true;
      }else{
        $errores = $validador->getMessages();
      }

      return $this->render('admin/ingresar-user.twig',[
        'r' => $r,
        'errores' => $errores
      ]);
    }
  }

 ?>
