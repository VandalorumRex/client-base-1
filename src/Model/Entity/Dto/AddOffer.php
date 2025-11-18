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
class AddOffer
{
    #[OA\Property(description: 'Тип сделки', nullable: false, enum: ['продажа', 'аренда'])]
    public string $type;

    #[OA\Property(description: 'Тип недвижимости', nullable: false, enum: ['жилая'])]
    public string $propertyType;

    #[OA\Property(
        description: 'Категория объекта',
        nullable: false,
        enum: ['дача', 'дом', 'дом с участком', 'участок', 'часть дома', 'квартира', 'комната', 'таунхаус', 'участок', 'дуплекс','гараж'],
    )]
    public string $category;

    #[OA\Property(description: 'Категория гаража', nullable: true, enum: ['гараж', 'машиноместо', 'бокс'])]
    public string $garageType;

    #[OA\Property(description: 'Номер лота', nullable: true)]
    public string $lotNumber;

    #[OA\Property(description: 'Кадастровый номер объекта недвижимости', nullable: true)]
    public string $cadastralNumber;

    #[OA\Property(description: 'URL страницы с объявлением на вашем сайте', nullable: true)]
    public string $url;

    #[OA\Property(description: 'Расположение объекта', nullable: false)]
    public Location $location;
}
