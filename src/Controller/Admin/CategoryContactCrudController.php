<?php

namespace App\Controller\Admin;

use App\Entity\CategoryContact;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CategoryContact::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
