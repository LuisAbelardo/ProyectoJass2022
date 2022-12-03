<?php

   use Illuminate\Database\Capsule\Manager as Capsule;

   $capsule = new Capsule;
   
   // La constante 'DATABASE' deve estar definido en el archivo 'env.php' 
   $capsule->addConnection([
      'driver'    => getenv('DB_DRIVER'),
      'host'      => getenv('DB_HOST'),
      'database'  => getenv('DB_NAME'),
      'username'  => getenv('DB_USER_NAME'),
      'password'  => getenv('DB_PASSWORD'),
      'charset'   => getenv('DB_CHARSET'),
      'collation' => getenv('DB_COLLATION'),
      'prefix'    => ''
   ]);
   
   
   // Make this Capsule instance available globally via static methods... (optional)
   $capsule->setAsGlobal();
   
   // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
   $capsule->bootEloquent();