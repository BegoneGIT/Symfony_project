<?php
/**
 * Wallet fixture.
 */

namespace App\DataFixtures;

use App\Entity\Wallet;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class WalletFixtures.
 */
class WalletFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'wallets', function ($i) {
            $wallet = new Wallet();
            $wallet->setAmount($this->faker->randomFloat($nbMaxDecimals = 2));
            $wallet->setPaymentType($this->faker->boolean($chanceOfGettingTrue = 50)); //($this->faker->word);
            $wallet->setTransaction($this->faker->boolean($chanceOfGettingTrue = 60)); //($this->faker->word);
            $wallet->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $wallet->setAuthor($this->getRandomReference('users'));
            $wallet->setLabel($this->getRandomReference('labels'));

            return $wallet;
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
        return [LabelFixtures::class, UserFixtures::class];
    }
}
