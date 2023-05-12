<?php 

declare(strict_types = 1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    private $entityMenager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        DatabasePrimer::prime($kernel);

        $this->entityMenager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    /** @test */
    public function a_user_record_can_be_created_in_the_database(): void
    {
        $user = new User();
        $user->setEmail('stempko@gmail.com');
        $user->setRoles([1,2,3]);
        $user->setPassword('haslo');
        $user->setIsVerified(1);

        $this->entityMenager->persist($user);

        $this->entityMenager->flush();

        $userRepository = $this->entityMenager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email'=>'stempko@gmail.com']);

        $this->assertEquals('haslo', $user->getPassword());
        $this->assertEquals([1,2,3], $user->getRoles());
        $this->assertEquals(1, $user->getIsVerified());
    }
}
