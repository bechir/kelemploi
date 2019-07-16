<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Civility;
use App\Entity\City;
use App\Entity\Language;
use App\Entity\Currency;
use App\Utils\CityFinder\Cities;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadCivilities($manager);
        $this->loadCities($manager);
        $this->loadCurrencies($manager);
        $this->loadLanguages($manager);
        $this->loadDistricts($manager);
        $this->loadImmovablesTypes($manager);
        $this->loadImmovablesCategories($manager);
    }

    public function loadUsers(ObjectManager $manager)
    {
        // $userData = [$username, $phoneNumber, $password, $email, $roles, $country, $city];
        foreach ($this->getUserData() as [$username, $phoneNumber, $password, $email, $roles, $country, $city]) {
            $user = new User();
            $user->setUsername($username);
            $user->setPhoneNumber($phoneNumber);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setCountry($country);
            $user->setCity($city);
            $user->setEnabled(true);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    public function loadCivilities(ObjectManager $manager)
    {
        // Loading categories
        $categories = [
          ['M' => 'civility.m'],
          ['Mme' => 'civility.Mme'],
          ['Mlle' => 'civility.Mlle'],
        ];

        foreach ($categories as $category) {
            foreach ($category as $abbr => $name) {
                $civility = new Civility();
                $civility->setName($name);
                $civility->setAbbr($abbr);
                $manager->persist($civility);
            }
        }
        $manager->flush();
    }

    public function loadCities(ObjectManager $manager)
    {
        $cities = Cities::getCities('MR');

        foreach ($cities as $code => $name) {
            $city = new City();
            $city->setName($name)
               ->setCode($code);
            $manager->persist($city);
        }
        $manager->flush();
    }

    public function loadCurrencies(ObjectManager $manager)
    {
        $currencies = ['MRU', 'EUR', 'USD', 'CAD', 'TND', 'MAD', 'XOF'];
        foreach ($currencies as $code) {
            $currency = new Currency();
            $currency->setCode($code);

            $manager->persist($currency);
        }
        $manager->flush();
    }

    public function loadLanguages(ObjectManager $manager)
    {
        $languages = [
            'English' => 'en',
            'Français' => 'fr',
            'العربية' => 'ar',
            '官話' =>  'cmn',
            'Español' => 'es',
        ];
        foreach ($languages as $name => $code) {
            $lang = new Language();
            $lang->setCode($code);
            $lang->setName($name);

            $manager->persist($lang);
        }
        $manager->flush();
    }

    public function loadDistricts(ObjectManager $manager)
    {
        $names = ['Dar Naim', 'El Mina',  'Ksar', 'Riyadh', 'Sebkha', 'Tevragh-Zeina',  'Teyaret', 'Toujounine'];

        foreach ($names as $name) {
            $district = new District();
            $district->setName($name);

            $manager->persist($district);
        }
        $manager->flush();
    }

    public function loadImmovablesTypes(ObjectManager $manager)
    {
        $names = ['terrain', 'maison'];

        foreach ($names as $name) {
            $type = new ImmovableType();
            $type->setName($name);

            $manager->persist($type);
        }
        $manager->flush();
    }

    public function loadImmovablesCategories(ObjectManager $manager)
    {
        $names = ['a-vendre', 'a-louer'];

        foreach ($names as $name) {
            $category = new ImmovableCategory();
            $category->setName($name);

            $manager->persist($category);
        }
        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$username, $phoneNumber, $password, $email, $roles, $country, $city];
            ['Administrateur', '+222 26 74 93 34', '12345678', 'contactwebmaster21@gmail.com', ['ROLE_SUPER_ADMIN'], 'MR', 'NKTT'],
        ];
    }
}
