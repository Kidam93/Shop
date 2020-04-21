<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppFixtures extends Fixture
{
    const TITLE = ['Coombes', 
                    'Keeve Set', 
                    'Nillé', 
                    'Blanko', 
                    'Momo', 
                    'Penemillè', 
                    'Kappu'];

    const DESCRIPTION = ['LOUNGE', 
                            'TABLE & CHAIRS', 
                            'ARMCHAIR', 
                            'SIDE TABLE', 
                            'SHELVES', 
                            'CHAIR', 
                            'SHELVES'];
    
    const PRICE = [2600, 
                    590, 
                    950, 
                    90, 
                    890, 
                    120, 
                    420];

    const QUANTITY = [1, 
                        1, 
                        1, 
                        1, 
                        1, 
                        1, 
                        1];
    
    const IMAGE = ['coobes', 
                    'keeveset', 
                    'nille', 
                    'blanko', 
                    'momo', 
                    'penemille', 
                    'kappu'];

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < sizeof(self::TITLE); $i++){
            $article = new Article();
            $article->setTitle(self::TITLE[$i])
                    ->setDescription(self::DESCRIPTION[$i])
                    ->setPrice(self::PRICE[$i])
                    ->setQuantity(self::QUANTITY[$i])
                    ->setImage(self::IMAGE[$i]);
            $manager->persist($article);
        }
        $manager->flush();
    }
    
}
