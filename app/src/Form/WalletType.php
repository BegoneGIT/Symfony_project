<?php
/**
 * Wallet type.
 */

namespace App\Form;

use App\Entity\Label;
use App\Entity\PaymentTypes;
use App\Entity\TransactionTypes;
use App\Entity\Wallet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class WalletType.
 */
class WalletType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'label',
            EntityType::class,
            [
                'class' => Label::class,
                'choice_label' => function ($label) {
                        return $label->getName();
                },
                'label' => 'label_category',
                'placeholder' => 'label_none',
                'required' => true,
            ]
        );

        $builder->add(
            'paymentType',
            EntityType::class,
            [
                'class' => PaymentTypes::class,
                'choice_label' => function ($payment) {
                    return $payment->getCode();
                },
                'label' => 'label_payment',
                'placeholder' => 'label_payment_type',
                'required' => true,
                'expanded' => true,
                'multiple' => false,
            ]
        );

        $builder->add(
            'transactionType',
            EntityType::class,
            [
                'class' => TransactionTypes::class,
                'choice_label' => function ($transaction) {
                    return $transaction->getCode();
                },
                'label' => 'label_transaction',
                'placeholder' => 'label_transaction_type',
                'required' => true,
                'expanded' => true,
                'multiple' => false,
            ]
        );

        $builder->add(
            'amount',
            TextType::class,
            [
                'label' => 'label_amount',
                'required' => true,
                'attr' => ['max_length' => 128],
            ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Wallet::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'category';
    }
}
