<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MyDashboardFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des catégories
        $categories = [];
        $categoryNames = ['Électronique', 'Vêtements', 'Alimentation', 'Meubles'];

        foreach ($categoryNames as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription('Description de la catégorie ' . $name);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Création des produits
        $productData = [
            ['Smartphone', 'Un téléphone intelligent', 0],
            ['Ordinateur portable', 'Un ordinateur portable puissant', 0],
            ['T-shirt', 'Un t-shirt confortable', 1],
            ['Pantalon', 'Un pantalon élégant', 1],
            ['Pain', 'Pain frais', 2],
            ['Fruits', 'Fruits de saison', 2],
            ['Table', 'Une belle table', 3],
            ['Chaise', 'Une chaise confortable', 3],
        ];

        foreach ($productData as [$name, $description, $categoryIndex]) {
            $product = new Product();
            $product->setName($name);
            $product->setDescription($description);
            $product->setCategory($categories[$categoryIndex]);
            $manager->persist($product);
        }

        // Création des utilisateurs
        $userNames = ['Alice', 'Bob', 'Charlie', 'David', 'Eve'];

        foreach ($userNames as $name) {
            $user = new User();
            $user->setName($name);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
