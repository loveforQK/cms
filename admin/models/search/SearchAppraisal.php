<?php
namespace app\models\search;

use Yii;
use yii\db\ActiveRecord;
use app\models\SysAppraisal;
use yii\data\ActiveDataProvider;

class SearchAppraisal extends ActiveRecord{
    public function rules(){
        return [];
    }

    public function search(){
        $query = SysAppraisal::find();
        $query->select = SysAppraisal::columns();
        $query = $query->orderBy('sort asc,id desc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => Yii::$app->params['listPageSize'],
            ],
        ]);

        return $dataProvider;
    }
}