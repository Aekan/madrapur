<?php

namespace backend\modules\Modevent\models;

use backend\modules\MadActiveRecord\models\MadActiveRecord;
use common\models\User;
use common\models\UserProfile;
use PhpParser\Node\Expr\AssignOp\Mod;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Default model for the `Modevent` module
 */
class Modevent extends MadActiveRecord{
 
    public static function tableName() {
        return 'modulusModevent';
    }



    public function rules() {
        return [
            [['id'], 'integer'],
            [['title','place','user','status'], 'string', 'max' => 1200],
            [['date'], 'datetime', 'format' => 'yyyy-mm-dd H:i'],
            [['startDate','endDate'], 'date', 'format' => 'yyyy-mm-dd'],
        ];
    }




    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'title'),
            'place' => Yii::t('app', 'place'),
            'user' => Yii::t('app', 'user'),
            'status' => Yii::t('app', 'status'),
            'date' =>Yii::t('app', 'date'),
            'startDate' =>Yii::t('app', 'startDate'),
            'endDate' =>Yii::t('app', 'endDate'),
        ];
    }

    public static function userNextWork(){
        $date=date('Y-m-d',strtotime('today'));

        $next = Modevent::find()->andFilterWhere(['=','user',Yii::$app->user->getIdentity()->username])->andFilterWhere
        (['>=','startDate',$date])->andWhere('`title`=\'arranged\'')->andWhere('not `status`<=>\'worked\'')->orderBy
        (['startDate'=>SORT_ASC])->one();

        Yii::warning($next);
        return $next;
    }
    public static function userNextWorkSpecific($username){
        $date=date('Y-m-d',strtotime('today'));

        $next = Modevent::find()->andFilterWhere(['=','user',$username])->andFilterWhere
        (['>=','startDate',$date])->andWhere('`title`=\'arranged\'')->andWhere('not `status`<=>\'worked\'')->orderBy
        (['startDate'=>SORT_ASC])->one();

        Yii::warning($next);
        return $next;

    }

    public static function userCurrentWorkshift($username = null){
        $username= $username ? $username : Yii::$app->user->getIdentity()->username;


        $currentWs=Modevent::find()->andFilterWhere(['=','user',$username])->andFilterWhere(['=','status','working'])
            ->one();

        return $currentWs;


    }


    public static function userLastWorkSpecific($username){
        $date=date('Y-m-d',strtotime('today'));

        $next = Modevent::find()->andFilterWhere(['=','user',$username])->andFilterWhere
        (['<=','startDate',$date])->andWhere('`title`=\'arranged\'')->andWhere(' `status`=\'worked\'')->orderBy
        (['startDate'=>SORT_ASC])->one();

        Yii::warning($next);
        return $next;

    }
    public static function userLastWork(){
        $date=date('Y-m-d',strtotime('today'));

        $next = Modevent::find()->andFilterWhere(['=','user',Yii::$app->user->getIdentity()->username])->andFilterWhere
        (['<=','startDate',$date])->andWhere('`title`=\'arranged\'')->andWhere('`status`=\'worked\'')->orderBy
        ('startDate')->one();
        return $next;
    }

    public function search($params) {
        $query = Modevent::find()->andFilterWhere(['=','user',Yii::$app->user->getIdentity()->username])->andWhere('`title`=\'subscribe\'');

        $dataProvider = new ActiveDataProvider([
                                                   'query' => $query,
                                                   'pagination' => [
                                                       'pageSize' => 15,
                                                   ],
                                               ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

//        $query->andFilterWhere((['=', 'ticketId', $this->ticketId]));
//        $query->andFilterWhere((['like', 'source', $this->source]));
//        $query->andFilterWhere((['=', 'bookingDate', $this->bookingDate]));

        return $dataProvider;
    }

    



}
