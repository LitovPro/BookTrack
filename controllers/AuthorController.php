<?php

namespace app\controllers;

use Yii;
use app\models\Author;
use app\models\AuthorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AuthorController implements the CRUD actions for Author model.
 */
class AuthorController extends Controller
{
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
                        'actions' => ['index', 'view'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete', 'quick-create'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'quick-create' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Author models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Author model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // Get books by this author
        $books = $model->getBooks()->orderBy(['year' => SORT_DESC])->all();

        return $this->render('view', [
            'model' => $model,
            'books' => $books,
        ]);
    }

    /**
     * Creates a new Author model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Author();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Автор успешно создан.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Author model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Автор успешно обновлен.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Author model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Check if author has books
        if ($model->getBooks()->count() > 0) {
            Yii::$app->session->setFlash('error', 'Нельзя удалить автора, у которого есть книги.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Автор успешно удален.');

        return $this->redirect(['index']);
    }

    /**
     * Quick create author via AJAX
     * @return array JSON response
     */
    public function actionQuickCreate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $fullName = Yii::$app->request->post('full_name');

        if (empty($fullName)) {
            return [
                'success' => false,
                'message' => 'ФИО автора не может быть пустым'
            ];
        }

        // Нормализуем имя автора (убираем лишние пробелы, приводим к стандартному виду)
        $normalizedName = trim(preg_replace('/\s+/', ' ', $fullName));

        // Проверяем, не существует ли уже такой автор (точное совпадение)
        $existingAuthor = Author::findOne(['full_name' => $normalizedName]);
        if ($existingAuthor) {
            return [
                'success' => true,
                'authorId' => $existingAuthor->id,
                'message' => 'Автор уже существует',
                'isExisting' => true
            ];
        }

        // Проверяем похожих авторов (по частичному совпадению)
        $similarAuthors = Author::find()
            ->where(['like', 'full_name', $normalizedName])
            ->orWhere(['like', 'full_name', str_replace(' ', '%', $normalizedName)])
            ->all();

        if (!empty($similarAuthors)) {
            $similarNames = array_map(function($author) {
                return $author->full_name;
            }, $similarAuthors);

            return [
                'success' => false,
                'message' => 'Возможно, вы имели в виду: ' . implode(', ', $similarNames) . '. Проверьте правильность написания.',
                'similarAuthors' => $similarNames
            ];
        }

        // Создаем нового автора
        $author = new Author();
        $author->full_name = $normalizedName;

        if ($author->save()) {
            return [
                'success' => true,
                'authorId' => $author->id,
                'message' => 'Автор успешно создан'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Ошибка при создании автора: ' . implode(', ', $author->getFirstErrors())
            ];
        }
    }

    /**
     * Finds the Author model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Author the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Author::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
    }
}

