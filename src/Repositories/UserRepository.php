<?php

namespace App\Repositories;

use Exception;
use App\Entities\User;

class UserNotFoundException extends Exception
{
}
class DuplicatedEmailException extends Exception
{
}

class UserRepository
{
    private $users = [];
    private $lastId = 0;

    public function createUser(array $data): User
    {
        $email = $data["email"];

        // Check for duplicate email
        if ($this->getUserByEmail($email)) {
            throw new DuplicatedEmailException(
                "Error: Email address '$email' already exists. Cannot create a duplicate user."
            );
        }

        $this->lastId++;
        $user = new User(
            $this->lastId,
            $data["name"],
            $data["email"],
            $data["password"]
        );

        $this->users[$this->lastId] = $user;
        return $user;
    }

    public function updateUser(int $userId, array $updateData): User
    {
        if (!isset($this->users[$userId])) {
            throw new UserNotFoundException("User not found with ID: " . $userId);
        }

        // Check for duplicate email if provided in the update data
        if (isset($updateData["email"])) {
            $email = $updateData["email"];
            $existingUser = $this->getUserByEmail($email);
            if ($existingUser && $existingUser->getId() !== $userId) {
                throw new DuplicatedEmailException(
                    "Error: Email address '$email' already exists. Cannot create a duplicate user."
                );
            }
        }

        // Update user properties if they exist in the update data
        $user = $this->users[$userId];
        if (isset($updateData["name"])) {
            $user->setName($updateData["name"]);
        }
        if (isset($updateData["email"])) {
            $user->setEmail($updateData["email"]);
        }
        if (isset($updateData["password"])) {
            $user->setPassword($updateData["password"]);
        }

        return $user;
    }

    public function deleteUser(int $id): void
    {
        $this->getUserByIdOrFail($id);
        unset($this->users[$id]);
    }

    public function getAllUsers()
    {
        return $this->users;
    }

    public function getUserByIdOrFail(int $id)
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException("User not found with ID: " . $id);
        }

        return $this->users[$id];
    }

    private function getUserByEmail(string $email)
    {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        return null;
    }
}
