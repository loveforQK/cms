<?php
namespace app\models\search;

use Yii;
use yii\db\ActiveRecord;
use app\models\SysPage;
use yii\data\ActiveDataProvider;

class SearchPage extends ActiveRecord{
    public function rules(){
        return [];
    }

    public function search(){
        $query = SysPage::find();
        $query->select = SysPage::columns();
        $query = $query->orderBy('id desc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => Yii::$app->params['listPageSize'],
            ],
        ]);

        if(!($this->load(Yii::$app->request->getQueryParams()) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}