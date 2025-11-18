<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Entity\Response\HttpCode;
use Cake\Controller\Controller;
use Cake\I18n\I18n;
use OpenApi\Attributes as OA;
use Override;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
#[OA\OpenApi('3.1.0')]
#[OA\Info(title: 'CB1 Offers API', version: '0.1', contact: new OA\Contact(email: 'gho-mansur@yandex.ru'))]
#[OA\Server('http://cb1.local')]
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    /**
     * Рендер JSON объекта
     *
     * @param object|array<mixed> $response
     * @return void
     */
    public function json(array|object $response): void
    {
        if (is_array($response) && isset($response['code']) && is_scalar($response['code'])) {
            $code = $response['code'] > 0 ? (int)$response['code'] : HttpCode::OK;
            unset($response['code']);
        } elseif (is_object($response) && isset($response->code) && is_scalar($response->code)) {
            $code = $response->code > 0 ? (int)$response->code : HttpCode::OK;
            unset($response->code);
        } else {
            $code = HttpCode::OK;
        }
        $this->set('response', $response);
        $this->viewBuilder()
            ->setOption('serialize', 'response')
            ->setOption('jsonOptions', JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            ->setClassName('Json');
        $this->setResponse(
            $this->response->withStatus($code)->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Content-Language', I18n::getLocale())
                ->withHeader('Content-Type', 'application/json; charset=utf-8'),
        );
    }
}
