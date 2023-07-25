<?php
    namespace App;
    
    require 'vendor/autoload.php';
    
    use App\Repositories\UserRepository;
    use App\UseCases;
    

    // Usage example:
    $userRepository = new UserRepository();
    $userController = new UseCases($userRepository);

    $userController->createUser();

