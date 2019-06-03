<?php
  namespace App\Controllers\Admin;
  use App\Controllers\BaseCTR;
  use App\Models\Post;
  use Sirius\Validation\Validator;

  class PostCTR extends BaseCTR {

    //any como prefijo para cualquiera
    public function getIndex(){
      //admin/posts or admin/posts/index
      $posts = Post::all();
      return $this->render('admin/posts.twig',
      [
        'posts' => $posts
      ]);
    }

    public function getCreate(){
      // admin/posts/create
      return $this->render('admin/ingresar-post.twig');
    }

    public function postCreate(){
      //Vamos a validar
      $errores = [];
      $r = false;
      $validador = new Validator();
      $validador->add('titulo', 'required');
      $validador->add('contenido', 'required');

      if ($validador->validate($_POST)) {
        $post = new Post([
          'titulo' => $_POST['titulo'],
          'contenido' => $_POST['contenido']
        ]);

        if ($_POST['img']) {
          $post->img_url = $_POST['img'];
        }

        $post->save();
        $r = true;
      }else{
        $errores = $validador->getMessages();
      }

      return $this->render('admin/ingresar-post.twig',
      [
        'r' => $r,
        'errores' => $errores
      ]);
    }
  }

 ?>
