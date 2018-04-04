<?php
namespace app\models\search;

use Yii;
use yii\db\ActiveRecord;
use app\models\SysLog;
use yii\data\ActiveDataProvider;

class SearchLog extends ActiveRecord{
    public $name;
    public $type;
    public $nickname;

    public function rules(){
        return [
            [['name', 'nickname','type'], 'safe'],
        ];
    }

    public function search(){
        $query = SysLog::find();
        $query->select = SysLog::columns();
        $query = $query->orderBy('addtime desc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => Yii::$app->params['listPageSize'],
            ],
        ]);

        if(!($this->load(Yii::$app->request->getQueryParams()) && $this->validate())) {
            return $dataProvider;
        }

        if(!empty($this->type)){
            $query->andWhere(['=', 'type', $this->type]);
        }

        if(!empty($this->name)){
            $query->andWhere(['like', 'name', $this->name]);
        }

        if(!empty($this->nickname)){
            $query->andWhere(['like', 'nickname', $this->nickname]);
        }

        return $dataProvider;
    }
}