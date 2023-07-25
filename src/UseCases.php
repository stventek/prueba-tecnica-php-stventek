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

    public function findUserById()
    {
        try {
            $userIdToFind = 1;
            $user = $this->userRepository->getUserByIdOrFail($userIdToFind);
            echo "User found with ID: " .
                $user->getId() .
                ", Name: " .
                $user->getName() .
                ", Email: " .
                $user->getEmail() .
                PHP_EOL;
        } catch (UserNotFoundException $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }

    public function updateUser()
    {
        try {
            $userIdToUpdate = 1;
            $userToUpdate = [
                "name" => "Updated Name",
                "email" => "updated_email@example.com",
            ];

            $updatedUser = $this->userRepository->updateUser(
                $userIdToUpdate,
                $userToUpdate
            );

            echo "User updated successfully! ID: " .
                $updatedUser->getId() .
                ", Name: " .
                $updatedUser->getName() .
                ", Email: " .
                $updatedUser->getEmail() .
                PHP_EOL;
        } catch (UserNotFoundException $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }

    public function deleteUser()
    {
        try {
            $userIdToDelete = 1;
            $this->userRepository->deleteUser($userIdToDelete);
            echo "User deleted successfully!" . PHP_EOL;
        } catch (UserNotFoundException $e) {
            echo "Error: " . $e->getMessage() . PHP_EOL;
        }
    }
}

