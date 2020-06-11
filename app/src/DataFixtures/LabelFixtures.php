<?php
/**
 * Label fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Label;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class LabelFixtures.
 */
class LabelFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'labels', function ($i) {
            $label = new Label();
            $label->setName($this->faker->text($maxNbChars = 64));
            //$label->getWallet($this->getRandomReference('wallets'));


            $label->setAuthor($this->getRandomReference('users'));

            return $label;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
