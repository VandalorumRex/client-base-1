<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
declare(strict_types=1);

namespace App\Model\Entity\Dto;

use OpenApi\Attributes as OA;

/**
 * Description of Offer
 *
 * @author Mansur
 */
#[OA\Schema]
class Offer
{
    #[OA\Property(description: 'Тип сделки')]
    public string $type;

    #[OA\Property(description: 'Тип недвижимости')]
    public string $propertyType;

    #[OA\Property(description: 'Категория объекта')]
    public string $category;

    #[OA\Property(description: 'Категория гаража')]
    public string $garageType;

    #[OA\Property(description: 'Номер лота')]
    public string $lotNumber;

    #[OA\Property(description: 'Кадастровый номер объекта недвижимости')]
    public string $cadastralNumber;
}
