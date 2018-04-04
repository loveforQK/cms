<?php

namespace yii\ueditor;

use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\models\SysAttachment;

class UEditorAction extends Action
{
    /**
     * @var array
     */
    public $config = [];


    public function init()
    {
        //close csrf
        Yii::$app->request->enableCsrfValidation = false;
        //默认设置
        $_config = require(__DIR__ . '/config.php');
        //load config file
        $this->config = ArrayHelper::merge($_config, $this->config);
        parent::init();
    }

    public function run()
    {
        $this->handleAction();
    }

    /**
     * 处理action
     */
    protected function handleAction()
    {
        $action = Yii::$app->request->get('action');
        switch ($action) {
            case 'config':
                $result = json_encode($this->config);
                break;

            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = $this->actionUpload();
                break;

            /* 列出图片 */
            case 'listimage':
                /* 列出文件 */
            case 'listfile':
                $result = $this->actionList();
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->actionCrawler();
                break;

            default:
                $result = json_encode([
                    'state' => '请求地址出错'
                ]);
                break;
        }
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode([
                    'state' => 'callback参数不合法'
                ]);
            }
        } else if(isset($_GET["parent"])) {
            if (!preg_match("/^[\w_]+$/", $_GET["parent"])) {
                $result = json_encode(['state'=>'parent参数不合法']);
            }
            echo '<script type="text/javascript">window.parent.'.htmlspecialchars($_GET["parent"]) . '(' . $result . ');</script>';
        } else {
            echo $result;
        }
    }

    /**
     * 上传
     * @return string
     */
    protected function actionUpload()
    {
        try{
            $base64 = "upload";
            switch (htmlspecialchars($_GET['action'])) {
                case 'uploadimage':
                    $type = SysAttachment::TYPE_IMAGE;
                    $config = [
                        "pathRoot"   => ArrayHelper::getValue($this->config, "imageRoot", dirname(Yii::$app->basePath).DIRECTORY_SEPARATOR.'html'),
                        "pathFormat" => $this->config['imagePathFormat'],
                        "maxSize"    => $this->config['imageMaxSize'],
                        "allowFiles" => $this->config['imageAllowFiles']
                    ];
                    $fieldName = $this->config['imageFieldName'];
                    break;
                case 'uploadvideo':
                    $type = SysAttachment::TYPE_VIDEO;
                    $config = [
                        "pathRoot"   => ArrayHelper::getValue($this->config, "videoRoot", dirname(Yii::$app->basePath).DIRECTORY_SEPARATOR.'html'),
                        "pathFormat" => $this->config['videoPathFormat'],
                        "maxSize"    => $this->config['videoMaxSize'],
                        "allowFiles" => $this->config['videoAllowFiles']
                    ];
                    $fieldName = $this->config['videoFieldName'];
                    break;
                case 'uploadfile':
                default:
                    $type = SysAttachment::TYPE_DOC;
                    $config = [
                        "pathRoot"   => ArrayHelper::getValue($this->config, "fileRoot", dirname(Yii::$app->basePath).DIRECTORY_SEPARATOR.'html'),
                        "pathFormat" => $this->config['filePathFormat'],
                        "maxSize"    => $this->config['fileMaxSize'],
                        "allowFiles" => $this->config['fileAllowFiles']
                    ];
                    $fieldName = $this->config['fileFieldName'];
                    break;
            }
            /* 生成上传实例对象并完成上传 */

            $up = new Uploader($fieldName, $config, $base64);
            /**
             * 得到上传文件所对应的各个参数,数组结构
             * array(
             *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
             *     "url" => "",            //返回的地址
             *     "title" => "",          //新文件名
             *     "original" => "",       //原始文件名
             *     "type" => ""            //文件类型
             *     "size" => "",           //文件大小
             * )
             */

            $data = $up->getFileInfo();
            //添加上传素材记录
            if($data['state'] == 'SUCCESS'){
                SysAttachment::add($data['url'],$data['size'],$type);
            }
            /* 返回数据 */
            return json_encode($data);
        }catch(\Exception $e){
            return json_encode(['state'=>$e->getMessage()]);
        }
    }

    /**
     * 获取已上传的文件列表
     * @return string
     */
    protected function actionList()
    {
        /* 判断类型 */
        switch ($_GET['action']) {
            /* 列出文件 */
            case 'listfile':
                $type = SysAttachment::TYPE_DOC;
                $listSize = $this->config['fileManagerListSize'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $type = SysAttachment::TYPE_IMAGE;
                $listSize = $this->config['imageManagerListSize'];
        }

        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;

        $result = SysAttachment::page($type,$start,$size);

        /* 返回数据 */
        $result = json_encode([
            "state" => "SUCCESS",
            "list"  => $result['list'],
            "start" => $start,
            "total" => $result['list']
        ]);

        return $result;
    }

    /**
     * 抓取远程图片
     * @return string
     */
    protected function actionCrawler()
    {
        /* 上传配置 */
        $config = [
            "pathFormat" => $this->config['catcherPathFormat'],
            "maxSize"    => $this->config['catcherMaxSize'],
            "allowFiles" => $this->config['catcherAllowFiles'],
            "oriName"    => "remote.png"
        ];
        $fieldName = $this->config['catcherFieldName'];

        /* 抓取远程图片 */
        $list = [];
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, [
                "state"    => $info["state"],
                "url"      => $info["url"],
                "size"     => $info["size"],
                "title"    => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source"   => htmlspecialchars($imgUrl)
            ]);
        }

        /* 返回抓取数据 */
        return json_encode([
            'state' => count($list) ? 'SUCCESS' : 'ERROR',
            'list'  => $list
        ]);
    }
}