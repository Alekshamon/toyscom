<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégorie')
            // ...
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name')->setLabel('Nom de la catégorie')->setHelp('Saisir le nom de la catégorie'),
            SlugField::new('slug')->setLabel('URL de la catégorie')->setTargetFieldName('name')->setHelp('URL de la catégorie'),
        ];
    }
}
