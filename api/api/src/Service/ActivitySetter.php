<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\Note;
use App\Interfaces\ActivityLoggableInterface;
use App\Model\RequestMethodEnum;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use ApiPlatform\Metadata\Operation;

class ActivitySetter
{


    public function __construct()
    {
    }

    public function setActivity(Operation $operation, string $username, ActivityLoggableInterface $data): Activity
    {
        $method = strtolower($operation->getMethod());
        $shortName = strtolower($operation->getShortName());

        $activity = new Activity();
        $activity->setUsername($username);
        $activity->setType("{$shortName}.{$method}");
        $activity->setMessage($data->getActivityMessage($method, $shortName));
        $activity->setData($data->getActivityPayload($shortName));

        return $activity;
    }


}
