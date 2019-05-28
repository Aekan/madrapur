<?php

use yii\db\Schema;
use yii\db\Migration;

class m190528_113100_modulusAddBookkeppeRbac extends Migration {

    public function safeUp() {
        $auth = Yii::$app->authManager;


        $bookKeeper = $auth->createRole('bookKeeper');
        $hotelSeller = $auth->createRole('hotelSeller');
        $auth->add($bookKeeper);
        $auth->add($hotelSeller);





    }

    public function safeDown() {
        $auth = Yii::$app->authManager;



    }
}
