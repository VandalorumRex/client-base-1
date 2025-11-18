<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/CakePHP3 Framework/Controller.php to edit this template
 */
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Lib\Utils;
use App\Model\Entity\Response\HttpCode;
use Cake\Utility\Inflector;
use Cake\Utility\Xml;
use DOMDocument;
use OpenApi\Attributes as OA;
use Override;
use SimpleXMLElement;

/**
 * CakePHP OffersController
 *
 * @author Mansur
 */
class OffersController extends AppController
{
    private string $path;

    /**
     * Инициализация
     *
     * @return void
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();
        $this->path = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/../xml/offers.xml';
    }

    /**
     * Добавление оффера
     *
     * @return void
     */
    public function add(): void
    {
        /** @var array<string, string|array<string, string>> $offer */
        $offer = $this->request->getData();
        if (!file_exists($this->path)) {
            //$offers = [];
            $xmlString = '<?xml version="1.0" encoding="UTF-8"?><offers></offers>';
        } else {
            $xmlString = (string)file_get_contents($this->path);
        }
        $offers = new SimpleXMLElement($xmlString);
        $child = $offers->addChild('offer');
        foreach ($offer as $field => $item) {
            if (is_string($item)) {
                if ($field === 'creationDate' && !$item) {
                    $item = date('c');
                }
                $child->addChild(Inflector::dasherize($field), $item);
            } else {
                $onyq = $child->addChild($field);
                foreach ($item as $subField => $subItem) {
                    $onyq->addChild(Inflector::dasherize($subField), $subItem);
                }
            }
        }
        $child->addAttribute('internal-id', Utils::GUIDv4());

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML((string)$offers->asXML());
        $xmlPretty = $dom->saveXML();
        file_put_contents($this->path, $xmlPretty);
        $this->json(['code' => HttpCode::CREATED, 'message' => 'Принято']);
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
        if (!file_exists($this->path)) {
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Данные не найдены'];
        } else {
            $xmlString = (string)file_get_contents($this->path);
            $xml = Xml::build($xmlString);
            $response = Xml::toArray($xml);
        }
        $this->json($response);
    }
}
