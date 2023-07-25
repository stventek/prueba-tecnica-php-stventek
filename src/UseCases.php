<?php
namespace App;

use App\Repositories\UserNotFoundException;
use App\Repositories\UserRepository;
use Exception;

class UseCases
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser()
    {
        try {
            $userData = [
                "name" => "John Doe",
                "email" => "john@example.com",
                "password" => "my_secure_password",
            ];

            $newUser = $this->userRepository->createUser($userData);

            echo "New user created successfully! ID: " .
                $newUser->getId() .
                ", Name: " .
                $newUser->getName() .
                ", Email: " .
                $newUser->getEmail() .
                PHP_EOL;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }
}

