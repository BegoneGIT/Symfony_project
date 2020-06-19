<?php
/**
 * \App\Entity\PaymentTypes() fixture.
 */

namespace App\DataFixtures;

use App\Entity\PaymentTypes;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PaymentTypes()Fixtures.
 */
class PaymentTypesFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $groupName = 'payment_types';
        $codes = ['label_card', 'label_cash'];
        $names = ['card', 'cash'];
        $count = sizeof($codes);

        for ($i = 0; $i < $count; ++$i) {
            $payment = new PaymentTypes();
            $payment->setCode(array_pop($codes));
            $payment->setName(array_pop($names));

            $entity = $payment;

            if (null === $entity) {
                throw new \LogicException('Did you forget to return the entity object from your callback to BaseFixture::createMany()?');
            }

            $this->manager->persist($entity);

            // store for usage later as groupName_#COUNT#
            $this->addReference(sprintf('%s_%d', $groupName, $i), $entity);
        }

        $manager->flush();
    }
}
