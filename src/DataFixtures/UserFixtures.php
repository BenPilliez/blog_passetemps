<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('madeleine.f');
<<<<<<< HEAD
        $user->setEmail('benjamin.pilliez@sfr.fr');
        $user->setPassword($this->encoder->encodePassword($user, 'ClemBen59'));
=======
        $user->setEmail('madeleine.f@live.fr');
        $user->setPassword($this->encoder->encodePassword($user, 'aze'));
>>>>>>> 4a71aabf5aa4643d68b9dbfbca69e0fc936990c5
        $manager->persist($user);
        $manager->flush();
    }
}
