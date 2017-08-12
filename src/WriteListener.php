<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PommProject\ApiPlatform;

use PommProject\Foundation\Pomm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

final class WriteListener
{
    private $pomm;

    public function __construct(Pomm $pomm)
    {
        $this->pomm = $pomm;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        if ($request->isMethodSafe(false)) {
            return;
        }

        $model = $this->getModel($request);
        $entity = $event->getControllerResult();

        switch ($request->getMethod()) {
            case Request::METHOD_POST:
                $model->insertOne($entity);
                break;

            case Request::METHOD_PUT:
                $fields = array_keys($entity->fields());
                $model->updateOne($entity, $fields);
                break;

            case Request::METHOD_DELETE:
                $model->deleteOne($entity);
                break;
        }
    }

    private function getModel(Request $request)
    {
        $resourceClass = $request->attributes->get('_api_resource_class');
        if (null === $resourceClass) {
            return;
        }

        $sessionName = $request->attributes->get('_pomm_session_name');
        if (null === $sessionName) {
            $session = $this->pomm->getDefaultSession();
        } else {
            $session = $this->pomm->getSession($sessionName);
        }

        $modelName = $request->attributes->get('_pomm_model_name');
        if (null === $modelName) {
            $modelName = "${resourceClass}Model";
        }

        return $session->getModel($modelName);
    }
}
