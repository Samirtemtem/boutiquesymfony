<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Categorie;
use App\Entity\Clientdebug;
use App\Entity\ProduitCommande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Date')
            ->add('Etat')
            ->add('IdClientDebug', EntityType::class, ['class' => Clientdebug::class])
            // ->add('ProduitCommande', EntityType::class, ['class' => ProduitCommande::class])
            ->Add("Ajouter", SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
