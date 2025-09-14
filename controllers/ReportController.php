<?php

namespace app\controllers;

use Yii;
use app\models\Author;
use app\models\ReportForm;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;

/**
 * ReportController handles reports
 */
class ReportController extends Controller
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
                        'actions' => ['top-authors'],
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Top 10 authors by books count for selected year
     * @param int|null $year
     * @return mixed
     */
    public function actionTopAuthors($year = null)
    {
        // Create form model
        $model = new ReportForm();

        // Handle form submission
        if ($model->load(Yii::$app->request->get()) && $model->validate()) {
            $year = $model->year;
        } elseif ($year === null) {
            // Default to current year if not specified
            $year = date('Y');
        }

        // Validate year
        $year = (int)$year;
        if ($year < 1000 || $year > date('Y') + 1) {
            $year = date('Y');
        }

        // Try to get from cache first
        $cacheKey = "top-authors-{$year}";
        $data = Yii::$app->cache->get($cacheKey);

        if ($data === false) {
            // Generate report data
            $data = $this->generateTopAuthorsReport($year);

            // Cache for 5 minutes
            Yii::$app->cache->set($cacheKey, $data, 300);
        }

        // Create data provider
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);

        // Get available years for dropdown
        $availableYears = $this->getAvailableYears();

        // Set the selected year in the model
        $model->year = $year;

        return $this->render('top-authors', [
            'dataProvider' => $dataProvider,
            'selectedYear' => $year,
            'availableYears' => $availableYears,
            'model' => $model,
        ]);
    }

    /**
     * Generate top authors report data
     *
     * @param int $year
     * @return array
     */
    private function generateTopAuthorsReport(int $year): array
    {
        $sql = "
            SELECT
                a.id,
                a.full_name,
                COUNT(*) AS books_count
            FROM {{%author}} a
            JOIN {{%book_author}} ba ON ba.author_id = a.id
            JOIN {{%book}} b ON b.id = ba.book_id
            WHERE b.year = :year
            GROUP BY a.id, a.full_name
            ORDER BY books_count DESC, a.full_name ASC
            LIMIT 10
        ";

        $results = Yii::$app->db->createCommand($sql, [':year' => $year])->queryAll();

        // Add rank
        $rank = 1;
        foreach ($results as &$result) {
            $result['rank'] = $rank++;
        }

        return $results;
    }

    /**
     * Get available years for dropdown
     *
     * @return array
     */
    private function getAvailableYears(): array
    {
        $sql = "SELECT DISTINCT year FROM {{%book}} ORDER BY year DESC";
        $years = Yii::$app->db->createCommand($sql)->queryColumn();

        // Add current year if not in database
        $currentYear = date('Y');
        if (!in_array($currentYear, $years)) {
            array_unshift($years, $currentYear);
        }

        return array_combine($years, $years);
    }
}
