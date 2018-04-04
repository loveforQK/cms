<?php
namespace app\modules\admin\components;

use yii\base\Object;
use Yii;
use app\models\SysNews;
use app\models\SysPage;

class Template extends Object{
    public $error = '';
    public $urlData = [];
    public $return = '@app/views/template/_return';
    public $sitepath = '';
    public static $list = [
        'index'=>[
            'temp'=>'@app/views/template/index',
            'path'=>'index.html'
        ],
        'business_list'=>[
            'temp'=>'@app/views/template/news_list',
            'path'=>'business',
        ],
        'business_detail'=>[
            'temp'=>'@app/views/template/news_detail',
            'path'=>'business/detail'
        ],
        'industry_list'=>[
            'temp'=>'@app/views/template/news_list',
            'path'=>'industry',
        ],
        'industry_detail'=>[
            'temp'=>'@app/views/template/news_detail',
            'path'=>'industry/detail'
        ],
        'page'=>[
            'temp'=>'@app/views/template/page',
            'path'=>'page'
        ],
    ];

    public function init(){
        Yii::$app->controller->module->setViewPath('@app/templates');
        Yii::$app->controller->module->layout = false;
        $this->sitepath = dirname(Yii::$app->basePath).DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR;
        $this->urlData = Yii::$app->cache->get('SiteMap');
        parent::init();
    }

    //验证是否正在生成中
    public function isGo(){
        if(!empty($this->urlData)){
            return true;
        }
        $this->urlData = ['news'=>[],'page'=>[]];
        Yii::$app->cache->set('SiteMap',$this->urlData,300);
        return false;
    }

    //生成单页
    public function build(){
        $this->generate(self::$list['index']['temp'],self::$list['index']['path']);
    }

    //生成游戏列表页面
    public function build_news_list(){
        $size = 9;

        //生成机构新闻列表
        $total = SysNews::find()->select('1')->where('status=1 and type='.SysNews::TYPE_BUSINESS)->count();
        $prepath = self::$list['business_list']['path'].'/';
        if($total > 0){
            $pages = ceil($total/$size);
            for($i = 1;$i <= $pages;$i++){
                $list = SysNews::find()->select(SysNews::columns())->where('status=1 and type='.SysNews::TYPE_BUSINESS)->orderBy('pubtime desc')->limit($size)->offset(($i-1)*$size)->all();
                $params = ['list'=>$list,'current'=>$i,'pages'=>$pages,'page_uri'=>'/'.$prepath,'detail_uri'=>'/'.self::$list['business_detail']['path'].'/','type'=>SysNews::TYPE_BUSINESS];
                if($i == 1){
                    $filename = $prepath.'index.html';
                }else{
                    $filename = $prepath.$i.'.html';
                }
                $this->urlData['news'][] = $filename;
                $this->generate(self::$list['business_list']['temp'],$filename,$params);
            }
        }

        //生成行业新闻列表
        $total = SysNews::find()->select('1')->where('status=1 and type='.SysNews::TYPE_BUSINESS)->count();
        $prepath = self::$list['industry_list']['path'].'/';
        if($total > 0){
            $pages = ceil($total/$size);
            for($i = 1;$i <= $pages;$i++){
                $list = SysNews::find()->select(SysNews::columns())->where('status=1 and type='.SysNews::TYPE_INDUSTRY)->orderBy('pubtime desc')->limit($size)->offset(($i-1)*$size)->all();
                $params = ['list'=>$list,'current'=>$i,'pages'=>$pages,'page_uri'=>'/'.$prepath,'detail_uri'=>'/'.self::$list['industry_detail']['path'].'/','type'=>SysNews::TYPE_INDUSTRY];
                if($i == 1){
                    $filename = $prepath.'index.html';
                }else{
                    $filename = $prepath.$i.'.html';
                }
                $this->urlData['news'][] = $filename;
                $this->generate(self::$list['industry_list']['temp'],$filename,$params);
            }
        }

        $this->setSiteMap();
    }

    //生成新闻详细页面
    public function build_news_detail(){
        //分批次获取生成
        $step = 100;
        $i = 0;
        while(true){
            $result = SysNews::find()->select(SysNews::columns())->orderBy('id desc')->limit($step)->offset($i*$step)->all();
            if(empty($result)){
                break;
            }

            foreach($result as $model){
                $prekey = ($model->type == SysNews::TYPE_BUSINESS)?'business_detail':'industry_detail';
                $prepath = self::$list[$prekey]['path'].'/';
                if($model->status == 0){
                    $this->generate($this->return,$prepath.$model->id.'.html');
                }else{
                    if($this->generate(self::$list[$prekey]['temp'],$prepath.$model->id.'.html',['model'=>$model])){
                        $this->urlData['news'][] = $prepath.$model->id.'.html';
                    }
                }
            }

            $i++;
        }

        $this->setSiteMap();
    }

    //生成攻略列表页面
    public function build_page(){
        $list = SysPage::find()->select(SysPage::columns())->orderBy('id desc')->all();
        foreach($list as $model){
            $this->generate(self::$list['page']['temp'],self::$list['page']['path'].'/'.$model->dirname.'/index.html',['model'=>$model]);
            $this->urlData['page'][] = self::$list['page']['path'].'/'.$model->dirname.'/';
        }

        $this->setSiteMap();
    }

    //移除指定页面
    public function delpage($pagecode,$content_id){
        $this->generate($this->return,self::$list[$pagecode]['path'].'/'.$content_id.'.html');
    }

    //将模板内容生成静态页面
    private function generate($temp,$file,$params = []){
        try{
            $html = Yii::$app->view->render($temp,$params);
            $filearray = explode('/',$file);
            if(count($filearray) > 1){
                unset($filearray[count($filearray)-1]);
                $newpath = $this->sitepath.DIRECTORY_SEPARATOR.implode('/',$filearray);
                if(!is_dir($newpath)){
                    @mkdir($newpath,0777,true);
                }
            }
            $file = $this->sitepath.DIRECTORY_SEPARATOR.$file;
            file_put_contents($file,$html);
            return true;
        }catch(\Exception $e){
            unset($html);
            Yii::$app->cache->delete('SiteMap');
            $this->setError($e);
            return false;
        }
    }

    //设置生成异常错误信息
    public function setError($e){
        $this->error = 'Msg: '.$e->getMessage().'<br>File: '.$e->getFile().'<br>Line: '.$e->getLine();
    }

    //判断缓存是否存在
    public function setSiteMap(){
        if(Yii::$app->cache->exists('SiteMap')){
            Yii::$app->cache->set('SiteMap',$this->urlData);
        }
    }

    //更新站点地图文件
    public function sitemap(){
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= "<url>\n";
        $xml .= "<loc>".$host."</loc>\n";
        $xml .= "<lastmod>".date('Y-m-d')."</lastmod>\n";
        $xml .= "<changefreq>".'daily'."</changefreq>\n";
        $xml .= "<priority>".'1.0'."</priority>\n";
        $xml .= "<data>\n";
        $xml .= "<display>\n";
        $xml .= "<html5_url>".$host."</html5_url>\n";
        $xml .= "</display>\n";
        $xml .= "</data>\n";
        $xml .= "</url>\n";

        //游戏详细页面路径
        foreach($this->urlData['news'] as $url){
            $xml .= "<url>\n";
            $xml .= '<loc>' . $host . '/' . $url . "</loc>\n";
            $xml .= '<lastmod>' . date('Y-m-d') . "</lastmod>\n";
            $xml .= '<changefreq>' . 'daily' . "</changefreq>\n";
            $xml .= '<priority>' . '1.0' . "</priority>\n";
            $xml .= "</url>\n";
        }

        //评测详细页面路径
        foreach($this->urlData['page'] as $url){
            $xml .= "<url>\n";
            $xml .= '<loc>' . $host . '/' . $url . "</loc>\n";
            $xml .= '<lastmod>' . date('Y-m-d') . "</lastmod>\n";
            $xml .= '<changefreq>' . 'daily' . "</changefreq>\n";
            $xml .= '<priority>' . '1.0' . "</priority>\n";
            $xml .= "</url>\n";
        }
        $xml .= '</urlset>';

        try{
            $file = $this->sitepath.DIRECTORY_SEPARATOR.'sitemap.xml';
            file_put_contents($file,$xml);
            Yii::$app->cache->delete('SiteMap');
            unset($xml,$sitemaplist);
            return true;
        }catch(\Exception $e){
            unset($xml,$sitemaplist);
            $this->setError($e);
            return false;
        }
    }
}