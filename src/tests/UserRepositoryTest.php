<?php
use App\Repositories\DuplicatedEmailException;
use App\Repositories\UserRepository;
use App\Repositories\UserNotFoundException;
use App\Entities\User;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository();
    }

    public function testCreateUser()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => "my_secure_password",
        ];

        $user = $this->userRepository->createUser($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("John Doe", $user->getName());
        $this->assertEquals("john@example.com", $user->getEmail());
    }

    public function testUpdateUser()
    {
        // Create a user to update
        $userData = [
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => "my_secure_password",
        ];
        $user = $this->userRepository->createUser($userData);

        // Update user information
        $updateData = [
            "name" => "Updated Name",
            "email" => "updated_email@example.com",
        ];

        $updatedUser = $this->userRepository->updateUser($user->getId(), $updateData);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals("Updated Name", $updatedUser->getName());
        $this->assertEquals("updated_email@example.com", $updatedUser->getEmail());
    }

    public function testUpdateUserWithDuplicateEmail()
    {
        // Create two users with different emails
        $user1Data = [
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => "my_secure_password",
        ];
        $user2Data = [
            "name" => "Jane Doe",
            "email" => "jane@example.com",
            "password" => "another_password",
        ];
        $this->userRepository->createUser($user1Data);
        $user2 = $this->userRepository->createUser($user2Data);

        // Try to update user2's email to the same as user1's email
        $updateData = [
            "email" => "john@example.com",
        ];

        $this->expectException(DuplicatedEmailException::class);
        $this->userRepository->updateUser($user2->getId(), $updateData);
    }

    public function testDeleteUser()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => "my_secure_password",
        ];
        $user = $this->userRepository->createUser($userData);
        $userId = $user->getId();

        $this->userRepository->deleteUser($userId);

        $this->expectException(UserNotFoundException::class);
        $this->userRepository->getUserByIdOrFail($userId);
    }

    public function testGetUserById()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => "my_secure_password",
        ];
        $user = $this->userRepository->createUser($userData);
        $userId = $user->getId();

        $retrievedUser = $this->userRepository->getUserByIdOrFail($userId);

        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals($userId, $retrievedUser->getId());
    }

    public function testGetUserByIdNotFound()
    {
        $this->expectException(UserNotFoundException::class);
        $this->userRepository->getUserByIdOrFail(999);
    }
}
