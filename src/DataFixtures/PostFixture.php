<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserInfo;

class PostFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user=new UserInfo();
        $user->setUserFirstName("Issam");
        $user->setUserLastName("Ben Ammar");
        $user->setUserGender("male");
        $user->setUserBirthDate(new \DateTime());
        $user->setUserBio("bio");
      

        $user->setId(2);
        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setText("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Sed nisi lacus sed viverra tellus in. Dolor magna eget est lorem ipsum dolor sit amet. Tortor aliquam nulla facilisi cras.")
            ->setUser($user)
            ->setCreatedAt(new \DateTime());

            $manager->persist($post);
        }
        $manager->persist($user);
        $manager->flush();
    }
}
