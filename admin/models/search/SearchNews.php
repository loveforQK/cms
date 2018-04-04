<?php
namespace app\models\search;

use Yii;
use yii\db\ActiveRecord;
use app\models\SysNews;
use yii\data\ActiveDataProvider;

class SearchNews extends ActiveRecord{
    public $title;
    public $status;
    public $type;
    public $pubtime;

    public function rules(){
        return [
            [['title', 'status', 'type', 'pubtime'], 'safe'],
        ];
    }

    public function search(){
        $query = SysNews::find();
        $query->select = SysNews::columns();
        $query = $query->orderBy('id desc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => Yii::$app->params['listPageSize'],
            ],
        ]);

        $query->andWhere(['=', 'type', $this->type]);

        if(!($this->load(Yii::$app->request->getQueryParams()) && $this->validate())) {
            return $dataProvider;
        }

        if(!empty($this->title)){
            $query->andWhere(['like', 'title', $this->title]);
        }

        if($this->status !== ''){
            $query->andWhere(['=', 'status', $this->status]);
        }

        if(!empty($this->pubtime)){
            $query->andWhere(['like', 'pubtime', $this->pubtime]);
        }

        return $dataProvider;
    }
}