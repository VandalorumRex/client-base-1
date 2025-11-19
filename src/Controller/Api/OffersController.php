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
use DOMXpath;
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
    #[OA\Post(
        path: '/api/offers',
        tags: ['offers'],
        operationId: 'add-offer',
        description: 'Добавление оффера',
    )]
    #[OA\RequestBody(content: new OA\JsonContent(ref: '#/components/schemas/AddOffer'))]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Принято',
        content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse'),
    )]
    public function add(): void
    {
        /** @var array<string, string|array<string, string>> $offer */
        $offer = $this->request->getData();
        if (!file_exists(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/../xml')) {
            mkdir(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/../xml');
        }
        if (!file_exists($this->path)) {
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
                // Превращаем camelCase в camel-case согласно 
                // https://yandex.ru/support/realty/ru/requirements/requirements-sale-housing#in_common
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
     * @param string $guid
     * @return void
     */
    public function edit(string $guid): void
    {
        $offer = $this->request->getData();
        if (!file_exists($this->path)) {
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Данные не найдены'];
        } else {
            $xmlString = (string)file_get_contents($this->path);
            $xml = Xml::build($xmlString);
            $xmlArray = Xml::toArray($xml);
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Оффер на найден'];
            foreach ($xmlArray['offers'] as $item) {
                if ($item['@internal-id'] === $guid) {
                    $found = $item;
                }
                /*$offer = [];
                foreach ($item as $field => $value) {
                    array_push ($offer, [str_replace('@', '', $field) => $value]);
                }
                array_push($response, $offer);*/
            }
        }
        $this->json($response);
    }

    /**
     * Удаление оффера
     *
     * @param string $guid
     * @return void
     */
    #[OA\Delete(
        path: '/api/offers/{guid}',
        tags: ['offers'],
        operationId: 'delete-offer',
        description: 'Удалить оффер по guid',
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Оффер удалён',
        content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse'),
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Оффер на найден',
        content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse'),
    )]
    public function delete(string $guid): void
    {
        if (!file_exists($this->path)) {
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Данные не найдены'];
        } else {
            $xmlString = (string)file_get_contents($this->path);
            $dom = new DomDocument;
            $dom->loadXML($xmlString);

            // Найдем элемент который необходимо удалить
            $xpath = new DOMXpath($dom);
            $nodelist = $xpath->query("/offers/offer[@internal-id='" . $guid . "']");
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Оффер не найден'];
            $oldnode = $nodelist->item(0);
            if ($oldnode) {
                // Удаляем элемент
                $oldnode->parentNode->removeChild($oldnode);
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $savedXml = $dom->saveXML();
                $xmlPretty = str_replace("  \n", '', $savedXml);
                file_put_contents($this->path, $xmlPretty);
                $response = ['message' => 'Оффер успешно удалён'];
            }
        }
        $this->json($response);
    }

    /**
     * Просмотр одного оффера
     *
     * @param string $guid
     * @return void
     */
    #[OA\Get(
        path: '/api/offers/{guid}',
        tags: ['offers'],
        operationId: 'get-offer',
        description: 'Просмотр оффера по guid',
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Просмотр оффера',
        content: new OA\JsonContent(ref: '#/components/schemas/Offer'),
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Оффер на найден',
        content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse'),
    )]
    public function view(string $guid): void
    {
        if (!file_exists($this->path)) {
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Данные не найдены'];
        } else {
            $xmlString = (string)file_get_contents($this->path);
            $xml = Xml::build($xmlString);
            //$xmlArray = Xml::toArray($xml);
            $response = ['code' => HttpCode::NOT_FOUND, 'message' => 'Оффер на найден'];
            $offer = $xml->xpath("//offer[@internal-id='" . $guid . "']");
            if ($offer) {
                $response = $offer;
            }
            /*foreach ($xmlArray['offers'] as $item) {
                if ($item['@internal-id'] === $guid) {
                    $response = $item;
                }*/
                /*$offer = [];
                foreach ($item as $field => $value) {
                    array_push ($offer, [str_replace('@', '', $field) => $value]);
                }
                array_push($response, $offer);*/
            //}
        }
        $this->json($response);
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
            $xmlArray = Xml::toArray($xml);
            $response = $xmlArray['offers']['offer'];//[];
            /*foreach ($xmlArray['offers']['offer'] as $item) {
                $offer = [];
                foreach ($item as $field => $value) {
                    array_push ($offer, [str_replace('@', '', $field) => $value]);
                }
                array_push($response, $offer);
            }*/
        }
        $this->json($response);
    }
}
