<?php
  namespace App\Controllers\Admin;
  use App\Controllers\BaseCTR;
  use App\Models\User;

  class IndexCTR extends BaseCTR {

    public function getIndex(){
      //Validando que estemos logeados
      if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];
        $user = User::find($userID);
        if ($user) {
          return $this->render('admin/index.twig', [
            'user' => $user
          ]);
        }
      }
      header('Location: '.BASE_URL.'auth/login');
    }
  }

 ?>
