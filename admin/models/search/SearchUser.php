<?php
namespace app\models\search;

use Yii;
use yii\db\ActiveRecord;
use app\models\SysUser;
use yii\data\ActiveDataProvider;

class SearchUser extends ActiveRecord{
    public $username;
    public $nickname;

    public function rules(){
        return [
            [['username', 'nickname'], 'safe'],
        ];
    }

    public function search(){
        $query = SysUser::find();
        $query->select = SysUser::columns();
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

        if(!empty($this->username)){
            $query->andWhere(['like', 'username', $this->username]);
        }

        if(!empty($this->nickname)){
            $query->andWhere(['like', 'nickname', $this->nickname]);
        }

        return $dataProvider;
    }
}