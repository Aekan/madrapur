<?php
/**
 * Created by PhpStorm.
 * User: ROG
 * Date: 2019. 02. 05.
 * Time: 10:57
 */

namespace backend\controllers;


class Controller extends \yii\web\Controller {

    /**
     * List of allowed domains.
     * Note: Restriction works only for AJAX (using CORS, is not secure).
     *
     * @return array List of domains, that can access to this API
     */
    public static function allowedDomains() {
        return [
            // '*',                        // star allows all domains
            'localhost',
            'http://localhost',
            'http://localhost:8080',
            'http://localhost:80',
            'https://localhost',
            'localhost:45678',
            'http://localhost:45678',
            'https://localhost:45678',
            'http://api.modulus.hu',
            'http://backend.modulus.hu',
            'http://frontend.modulus.hu',
            'http://budapestrivercruise.co.uk',
            'http://api.budapestrivercruise.co.uk',
            'http://backend.budapestrivercruise.co.uk',
            'http://frontend.budapestrivercruise.co.uk',
            'http://storage.budapestrivercruise.co.uk',
            'https://budapestrivercruise.co.uk',
            'https://api.budapestrivercruise.co.uk',
            'https://backend.budapestrivercruise.co.uk',
            'https://frontend.budapestrivercruise.co.uk',
            'https://storage.budapestrivercruise.co.uk',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [

            // For cross-domain AJAX request
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin'                           => static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['GET', 'POST'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 0,                 // Cache (seconds)
                ],
            ],

        ]);
    }

    public function redirectToAdmin() {
        if(!false)
            //return $this->redirect(\yii\helpers\Url::current());
            return true;
        else return true;
    }
}