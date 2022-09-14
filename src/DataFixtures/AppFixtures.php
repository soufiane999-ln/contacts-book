<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $customers = [];
        for ($i = 0; $i < 10 ; $i++) {
            $customer = new Customer();
            $customer->setEmail(sprintf("email_%d@email.com", $i));
            $password = $this->hasher->hashPassword($customer, sprintf("password", $i));
            $customer->setPassword($password);

            $manager->persist($customer);
            $manager->flush();

            $customers[] = $customer;
        }
        foreach ($customers as $customer) {
            for ($i = 0; $i < 5; $i++) {
                $contact = Contact::create(
                    $customer,
                    sprintf('fistname%d', $i),
                    sprintf('lastname%d', $i),
                    sprintf('Kaiserallee %d', $i),
                    sprintf('1234%d', $i),
                    sprintf('city %d', $i),
                    sprintf('country %d', $i),
                    sprintf('+49 1590 119900%d', $i),
                    new \DateTime(sprintf('2022-09-1%d', $i)),
                    sprintf('email_%d@mail.com', $i),
                    sprintf('image%d', $i));

                $manager->persist($contact);
                $manager->flush();
            }
        }
    }
}
