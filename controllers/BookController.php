<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\BookSearch;
use app\models\Author;
use app\services\BookService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    /**
     * @var BookService
     */
    private $bookService;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->bookService = new BookService();
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
                        'actions' => ['index', 'view'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Book models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Book();
        $authors = Author::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            $coverFile = UploadedFile::getInstance($model, 'cover');

            // Проверяем, что файл действительно загружен
            if ($coverFile && (!$coverFile->tempName || !file_exists($coverFile->tempName) || $coverFile->size == 0)) {
                $coverFile = null; // Сбрасываем файл если он некорректный
            }

            $authorIds = Yii::$app->request->post('Book')['authorIds'] ?? [];

            // Преобразуем authorIds в массив, если это строка
            if (is_string($authorIds)) {
                $authorIds = $authorIds ? explode(',', $authorIds) : [];
            }

            // Фильтруем пустые значения
            $authorIds = array_filter($authorIds, function($id) {
                return !empty($id);
            });

            // Проверяем валидность модели перед сохранением
            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', 'Ошибка валидации: ' . implode(', ', $model->getFirstErrors()));
            } else {
                try {
                    $book = $this->bookService->createBook(
                        $model->getAttributes(),
                        $authorIds,
                        $coverFile
                    );

                    Yii::$app->session->setFlash('success', 'Книга успешно создана.');
                    return $this->redirect(['view', 'id' => $book->id]);

                } catch (\Exception $e) {
                    Yii::error('Ошибка при создании книги: ' . $e->getMessage(), __METHOD__);
                    Yii::$app->session->setFlash('error', 'Ошибка при создании книги: ' . $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'authors' => $authors,
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $authors = Author::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            $coverFile = UploadedFile::getInstance($model, 'cover');

            // Проверяем, что файл действительно загружен
            if ($coverFile && (!$coverFile->tempName || !file_exists($coverFile->tempName) || $coverFile->size == 0)) {
                $coverFile = null; // Сбрасываем файл если он некорректный
            }

            $authorIds = Yii::$app->request->post('Book')['authorIds'] ?? [];

            // Преобразуем authorIds в массив, если это строка
            if (is_string($authorIds)) {
                $authorIds = $authorIds ? explode(',', $authorIds) : [];
            }

            // Фильтруем пустые значения
            $authorIds = array_filter($authorIds, function($id) {
                return !empty($id);
            });

            // Проверяем валидность модели перед сохранением
            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', 'Ошибка валидации: ' . implode(', ', $model->getFirstErrors()));
            } else {
                try {
                    $book = $this->bookService->updateBook(
                        $model,
                        $model->getAttributes(),
                        $authorIds,
                        $coverFile
                    );

                    Yii::$app->session->setFlash('success', 'Книга успешно обновлена.');
                    return $this->redirect(['view', 'id' => $book->id]);

                } catch (\Exception $e) {
                    Yii::error('Ошибка при обновлении книги: ' . $e->getMessage(), __METHOD__);
                    Yii::$app->session->setFlash('error', 'Ошибка при обновлении книги: ' . $e->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'authors' => $authors,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            $this->bookService->deleteBook($model);
            Yii::$app->session->setFlash('success', 'Книга успешно удалена.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
    }
}

