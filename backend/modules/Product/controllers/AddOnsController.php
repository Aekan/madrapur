<?php

namespace backend\modules\Product\controllers;

use backend\modules\Product\models\AddOn;
use function Complex\add;
use Yii;
use yii\db\StaleObjectException;
use yii\web\Controller;

/**
 * Default controller for the `product` module
 */
class AddOnsController extends Controller {
    /**
     * Renders the index view for the module
     *
     * @return string
     */

    public function actionUpdate(){
        $id=Yii::$app->request->get('id');
        $model=AddOn::findOne($id);
        if($model->load(Yii::$app->request->post())){

            if($model->save()){
                sessionSetFlashAlert('success','Successful addon update');
            }

        }



        return $this->render('update',['model'=>$model]);

    }

    public function actionAdmin() {
        $searchModel = new AddOn();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($postData = Yii::$app->request->post()) {
            if (isset($postData['create-add-on'])) {
                $addOn = new AddOn();
                $addOn->name = Yii::$app->request->post('name');
                $addOn->type = Yii::$app->request->post('AddOn')['type'];
                $addOn->icon = Yii::$app->request->post('icon');
                $addOn->price = Yii::$app->request->post('price');

                if (!$addOn->save()) {
                    // error message
                    Yii::error('Could not save.', 'addOnCreate');
                }
            }
        }

        if ($getData = Yii::$app->request->get()) {
            if (isset($getData['action']) && $action = $getData['action']) {
                switch ($action) {
                    case 'delete':
                        $addOn = AddOn::findOne(['id' => $getData['id']]);
                        try {
                            $addOn->delete();
                        } catch (StaleObjectException $e) {
                            Yii::error($e->getMessage(), 'addOnDelete');
                        } catch (\Throwable $e) {
                            Yii::error($e->getMessage(), 'addOnDelete');
                        }
                        break;
                    case 'edit':
                        Yii::error($getData, 'addOnEdit');
                        break;
                    default:
                        break;
                }
            }
        }

        return $this->render(
            'admin',
            [
                "searchModel" => $searchModel,
                "dataProvider" => $dataProvider,
            ]
        );
    }
}
