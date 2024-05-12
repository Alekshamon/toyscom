<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {

        $required = true;

        if ($pageName == 'edit') {
            $required = false;
        }
        return [
            TextField::new('name')->setLabel('Nom')->setHelp('Le nom du produit doit être unique'),
            SlugField::new('slug')->setTargetFieldName('name')->setLabel('URL')->setHelp('Laisser vide pour auto-générer'),
            TextareaField::new('description')->setLabel('Description')->setHelp('La description doit contenir au moins 20 caractères'),
            ImageField::new('illustration')
                ->setLabel('Image')
                ->setHelp('L\'image doit être au format JPG ou PNG du format 600x600 pixels')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[year].[month].[day].[timestamp].[rand(1,999)].[extension]')
                ->setRequired($required),
            MoneyField::new('price')->setCurrency('EUR')->setLabel('Prix')->setHelp('Le prix doit être en euros'),
            ChoiceField::new('tva')->setChoices([5.5 => '5.5%', 10 => '10%', 20 => '20%'])->setLabel('TVA')->setHelp('Le taux de TVA doit être de 5.5%, 10% ou 20%'),

            AssociationField::new('category', 'Catégorie'),
        ];
    }
}
