<?php
namespace app\models\search;

use Yii;
use yii\db\ActiveRecord;
use app\models\SysZhaopin;
use yii\data\ActiveDataProvider;

class SearchZhaopin extends ActiveRecord{
    public function rules(){
        return [];
    }

    public function search(){
        $query = SysZhaopin::find();
        $query->select = SysZhaopin::columns();
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