<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
declare(strict_types=1);

namespace App\Model\Entity\Dto;

use OpenApi\Attributes as OA;

/**
 * Description of Location
 *
 * @author Mansur
 */
#[OA\Schema]
class Location
{
    #[OA\Property(description: 'Страна', nullable: false)]
    public string $country;

    #[OA\Property(description: 'Название субъекта РФ', nullable: true)]
    public string $region;

    #[OA\Property(description: 'Название района субъекта РФ', nullable: false)]
    public string $district;

    #[OA\Property(description: 'Название населенного пункта', nullable: false)]
    public string $localityName;

    #[OA\Property(description: 'Район в населенном пункте', nullable: true)]
    public ?string $subLocalityName;

    #[OA\Property(description: 'Адрес объекта — улица и номер здания', nullable: false)]
    public string $address;

    #[OA\Property(description: 'Номер квартиры', nullable: false)]
    public string $apartment;
}
