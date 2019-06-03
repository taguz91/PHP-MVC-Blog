<?php
  namespace App\Controllers;
  use App\Models\Post;

  class IndexCTR extends BaseCTR {
    public function getIndex() {
      //Para importar variables declaradas en otro lado
      $posts = Post::query()->orderBy('id', 'desc')->get();

      return $this->render('index.twig', [
        'posts' => $posts
      ]);
    }
  }
 ?>
