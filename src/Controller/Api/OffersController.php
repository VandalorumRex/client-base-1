<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/CakePHP3 Framework/Controller.php to edit this template
 */
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Model\Entity\Response\HttpCode;
use Cake\Utility\Xml;
use OpenApi\Attributes as OA;

/**
 * CakePHP OffersController
 *
 * @author Mansur
 */
class OffersController extends AppController
{
    /**
     * Добавление оффера
     *
     * @return void
     */
    public function add(): void
    {
        // @todo
    }

    /**
     * Редактирование оффера
     *
     * @param string $id
     * @return void
     */
    public function edit(string $id): void
    {
        // @todo
    }

    /**
     * Удаление оффера
     *
     * @param string $id
     * @return void
     */
    public function delete(string $id): void
    {
        // @todo
    }

    /**
     * Просмотр одного оффера
     *
     * @param string $id
     * @return void
     */
    public function view(string $id): void
    {
        // @todo
    }

    /**
     * Просмотр всех офферов
     *
     * @return void
     */
    #[OA\Get(
        path: '/api/offers',
        tags: ['offers'],
        operationId: 'get-offers-all',
        description: 'Просмотр всех офферов',
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Просмотр всех офферов',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Offer')),
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Нет данных',
        content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse'),
    )]
    public function index(): void
    {
        $path = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/../xml/offers.xml';
        if (!file_exists($path)) {
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Данные не найдены'];
        } else {
            $xmlString = (string)file_get_contents($path);
            $xml = Xml::build($xmlString);
            $response = Xml::toArray($xml);
        }
        $this->json($response);
    }
}
