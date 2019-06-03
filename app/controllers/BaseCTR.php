<?php
  namespace App\Controllers;

  class BaseCTR {

    protected $tempEngine;

    public function __construct(){
      //Ruta de donde estan las vistas
      $loader = new \Twig_Loader_Filesystem('../views');
      $this->tempEngine = new \Twig_Environment($loader, [
        'debug' => true,
        'cache' => false
      ]);

      $this->tempEngine->addFilter(new \Twig_SimpleFilter('url',
      function ($path){
        return BASE_URL.$path;
      }));
    }

    //Para renderizar las vistas
    public function render($fileName, $data = []) {
      return $this->tempEngine->render($fileName, $data);
    }
  }

 ?>
