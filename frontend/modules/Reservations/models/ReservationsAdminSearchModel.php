<?php

namespace backend\modules\Reservations\models;

use backend\modules\Product\models\Product;
use backend\modules\Products\models\Graylinepartners;
use backend\modules\Products\models\Products;
use backend\modules\Products\models\Productscities;
use backend\modules\Products\models\Productscountires;
use backend\modules\Tickets\models\TicketBlock;
use yii\data\ActiveDataProvider;

class ReservationsAdminSearchModel extends Reservations {

    public function rules() {
        return [
            [['bookingId'], 'integer'],
            [['source'], 'string', 'max' => 255],
            /*   [['fname'], 'string', 'max' => 255],
               [['lname'], 'string', 'max' => 255],*/
            [['data'], 'string', 'max' => 1000],
            [['invoiceDate'], 'date', 'format' => 'yyyy-mm-dd'],
            [['bookingDate'], 'date', 'format' => 'yyyy-mm-dd'],
        ];
    }

    public function search($params) {
        $invoiceDate = '2016-02-05';
        $bookingDate = '2020-08-20';

        $what = ['*'];
        $from = self::tableName();
        $where = self::andWhereFilter([
            ['invoiceDate', '>=', $invoiceDate],
            ['bookingDate', '<=', $bookingDate],
            # ['source', 'LIKE', 'utca']
        ]);

        $rows = self::aSelect(ReservationsAdminSearchModel::class, $what, $from, $where, ['invoiceMonth' => SORT_ASC], ['invoiceMonth']);
        $rows = $query = ReservationsAdminSearchModel::find()->indexBy('id');;
        $dataProvider = new ActiveDataProvider([
            'query' => $rows,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        return $dataProvider;
    }

    public function searchMyreservations($params) {


        $currentUserId = \Yii::$app->user->getId();


        $query=Reservations::find()->andFilterWhere(['=','sellerId',strval($currentUserId)]);



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        if (!($this->load($params))) {
            \Yii::error($this->invoiceDate);
            return $dataProvider;
        }

        $query->andFilterWhere((['=', 'invoiceDate', $this->invoiceDate]));


        return $dataProvider;

    }

    public function searchAllreservations($params) {

        $what = ['*'];
        $from = self::tableName();

        $searchParams = isset($params['ReservationsAdminSearchModel']) ? $params['ReservationsAdminSearchModel'] : [];
        $filters = [];
        $filters[] = ['source', 'IN', ['Street', 'Hotel']];

        foreach ($searchParams as $paramName => $paramValue) {
            if ($paramValue) {
                $filters[] = [$paramName, 'LIKE', $paramValue];
            }
        }

        $where = self::andWhereFilter($filters);

        $rows = self::aSelect(ReservationsAdminSearchModel::class, $what, $from, $where, ['invoiceMonth' => SORT_ASC, 'source' => SORT_ASC, 'sellerName' => SORT_ASC, 'bookingId' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $rows,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        return $dataProvider;
    }

    public function searchMonthlyStatistics($params) {
        $what = ['*'];
        $from = self::tableName();

        $searchParams = isset($params['ReservationsAdminSearchModel']) ? $params['ReservationsAdminSearchModel'] : [];
        $filters = [];

        foreach ($searchParams as $paramName => $paramValue) {
            if ($paramValue) {
                $filters[] = [$paramName, 'LIKE', $paramValue];
            }
        }

        $filters[] = ['order_currency', '=', 'EUR'];

        $where = self::andWhereFilter($filters);

        $orderBy = ['invoiceMonth' => SORT_ASC, 'source' => SORT_ASC, 'sellerName' => SORT_ASC, 'bookingId' => SORT_ASC];

        $rows = self::aSelect(
            ReservationsAdminSearchModel::class,
            $what, $from, $where, $orderBy
        );

        $dataProvider = new ActiveDataProvider([
            'query' => $rows,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        return $dataProvider;
    }

    public function getMonthlyBySeller($sellerName) {
        $what = ['booking_cost'];
        $from = self::tableName();

        $filters = [];
        $filters[] = ['sellerName', '=', $sellerName];
        $filters[] = ['invoiceMonth', '=', date('m')];

        $where = self::andWhereFilter($filters);

        $rows = self::aSelect(
            ReservationsAdminSearchModel::class,
            $what, $from, $where
        )->all();

        $cost = 0;
        foreach ($rows as $row) {
            $cost += $row->booking_cost;
        }

        return $cost;
    }

    public function getTodayBySeller($sellerName) {
        $what = ['booking_cost'];
        $from = self::tableName();

        $filters = [];
        $filters[] = ['sellerName', '=', $sellerName];
        $filters[] = ['invoiceDate', '=', date('Y-m-d')];

        $where = self::andWhereFilter($filters);

        $rows = self::aSelect(
            ReservationsAdminSearchModel::class,
            $what, $from, $where
        )->all();

        $cost = 0;
        foreach ($rows as $row) {
            $cost += $row->booking_cost;
        }

        return $cost;
    }

    public function getTicketBookBySeller() {
        $what = ['startId'];
        $from = TicketBlock::tableName();

        $filters = [];
        $filters[] = ['assignedTo', '=', \Yii::$app->user->id];
        $filters[] = ['frozen', '=', 0];

        $where = self::andWhereFilter($filters);

        $row = self::aSelect(
            TicketBlock::class,
            $what, $from, $where
        )->one();

        return $row;
    }

    public function searchDay($params, $selectedDate, $sources, $prodId) {

        $what = ['*'];
        $from = self::tableName();
        $wheres = [];
        $wheres[] = ['bookingDate', '=', $selectedDate];
        $wheres[] = ['productId', 'IN', $sources];
        $where = self::andWhereFilter($wheres);
        $rows = self::aSelect(ReservationsAdminSearchModel::class, $what, $from, $where);
        $dataProvider = new ActiveDataProvider([
            'query' => $rows,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
        $this->load($params);
        return $dataProvider;
    }

    public function availableChairsOnDay($params, $selectedDate, $sources, $prodId) {

        $what = ['*'];
        $from = self::tableName();
        $wheres = [];
        $wheres[] = ['bookingDate', '=', $selectedDate];
        $wheres[] = ['productId', 'IN', $sources];
        $where = self::andWhereFilter($wheres);
        $rows = self::aSelect(ReservationsAdminSearchModel::class, $what, $from, $where);
        $bookigsFromThatDay = $rows->all();
        $counter = 0;
        foreach ($bookigsFromThatDay as $reservation) {
            if (isset($reservation->bookedChairsCount)) {
                $counter = $counter + $reservation->bookedChairsCount;
            }
        }
        $currentProduct = Product::getProdById($prodId);
        $placesleft = $currentProduct->capacity - $counter;
        if ($placesleft % 2 != 0) {
            $placesleft -= 1;
        }
        return $placesleft;
    }

    public function searchChart($params) {
        $invoiceDate = '2016-02-05';
        $bookingDate = '2020-08-20';

        $what = ['*'];
        $from = self::tableName();
        $where = self::andWhereFilter([
            ['invoiceDate', '>=', $invoiceDate],
            ['bookingDate', '<=', $bookingDate]
            # ['source', 'LIKE', 'utca']
        ]);

        $rows = self::aSelect(ReservationsAdminSearchModel::class, $what, $from, $where);

        $dataProvider = new ActiveDataProvider([
            'query' => $rows,
        ]);

        $this->load($params);

        return $dataProvider;
    }

    public function searchChartForStats($startDate = null, $endDate = null, $source = null) {
        $what = ['*'];
        $from = self::tableName();
        $where = self::andWhereFilter([
            ['invoiceDate', '>=', $startDate],
            ['invoiceDate', '<=', $endDate],
            # ['source', 'LIKE', 'utca']
        ]);

        $rows = self::aSelect(ReservationsAdminSearchModel::class, $what, $from, $where);

        $dataProvider = new ActiveDataProvider([
            'query' => $rows,
        ]);

        return $dataProvider;
    }

    public function returnBookingId() {
        return $this['bookingId'];
    }

    public function returnId() {
        return $this['id'];
    }

}

