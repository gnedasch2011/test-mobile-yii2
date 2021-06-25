<?php

namespace app\controllers;

use app\models\Repositories;
use app\models\RepositoriesHelper;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        //Идёт запрос на обновление

        $names = Repositories::find()
            ->select('name')
            ->limit(10)
            ->orderBy('updated_at desc')
            ->all();


        $names = ($names) ? ArrayHelper::getColumn($names, 'name') :
            ['mojombo', 'defunkt', 'gnedasch2011', 'ezmobius', 'ivey'];

        $repositories = RepositoriesHelper::getLastRepo($names) ?? [];

        //  Сохранить, но, если ничего не пришло, вытянуть из базы
        if (empty($repositories)) {
            $repositories = Repositories::find()
                ->all();
        }

        return $this->render('index', [
            'repositories' => $repositories,
            'namesArr' => $names,
        ]);
    }

    public function actionGetRepositories()
    {
        $repositoriesNames = explode("\n", trim($_POST['data']));

        $repositories = RepositoriesHelper::getLastRepo($repositoriesNames, $ajax = true);
        return $this->renderAjax('_block/repositories.php', [
                'repositories' => $repositories,
            ]
        );
    }

    /**
     * Обновить кроном 10 репозиториев из базы
     */
    public function actionMethodForCron()
    {
        $repositoriesNames = Repositories::find()
            ->select('name')
            ->limit(10)
            ->orderBy('updated_at desc')
            ->all();

        $repositoriesNames = ArrayHelper::getColumn($repositoriesNames, 'name');

        RepositoriesHelper::getLastRepo($repositoriesNames, $ajax = true);

        return true;
    }


}
