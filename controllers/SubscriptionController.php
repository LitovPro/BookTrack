<?php

namespace app\controllers;

use Yii;
use app\models\AuthorSubscription;
use app\services\SubscriptionService;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * SubscriptionController handles author subscriptions
 */
class SubscriptionController extends Controller
{
    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->subscriptionService = new SubscriptionService();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create'],
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Displays subscription form
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new AuthorSubscription();
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new subscription
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthorSubscription();

        if ($model->load(Yii::$app->request->post())) {
            // Add honeypot field check
            if (!empty(Yii::$app->request->post('website'))) {
                throw new BadRequestHttpException('Bot detected');
            }

            // Add delay to prevent rapid submissions
            usleep(500000); // 500ms delay

            try {
                $this->subscriptionService->createSubscription(
                    $model->author_id,
                    $model->phone
                );

                Yii::$app->session->setFlash('success', 'Вы успешно подписались на уведомления о новых книгах этого автора!');

            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        // Redirect back to author page
        $authorId = Yii::$app->request->post('AuthorSubscription')['author_id'] ?? null;
        if ($authorId) {
            return $this->redirect(['author/view', 'id' => $authorId]);
        }

        return $this->goHome();
    }
}

