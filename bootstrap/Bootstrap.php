<?php

namespace app\bootstrap;

use app\services\Notifier;
use yii\base\BootstrapInterface;
use Yii;

class Bootstrap implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton('app\services\NotifierInterface', function () use ($app) {
            return new Notifier($app->params['adminEmail']);
        });

        $container->setSingleton('app\services\LoggerInterface', 'app\services\Logger');
    }
}