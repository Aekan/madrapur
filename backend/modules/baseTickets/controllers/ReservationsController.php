<?php
namespace backend\modules\Reservations\controllers;

use backend\controllers\Controller;
use Yii;
use backend\modules\Reservations\models\ReservationsAdminSearchModel;

use yii\filters\AccessControl;

/**
 * ReservationsController implements the CRUD actions for ReservationsModel.
 */
class ReservationsController extends Controller {

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionAdmin() {
        $searchModel = new ReservationsAdminSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex() {
        $searchModel = new ReservationsAdminSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}