<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use yii\validators\ImageValidator;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string|null $isbn
 * @property string|null $description
 * @property string|null $cover_path
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BookAuthor[] $bookAuthors
 * @property Author[] $authors
 * @property UploadedFile|null $cover
 */
class Book extends ActiveRecord
{
    /**
     * @var UploadedFile|null
     */
    public $cover;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'year'], 'required'],
            [['year'], 'integer', 'min' => 1000, 'max' => date('Y') + 1],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 32],
            [['isbn'], 'match', 'pattern' => '/^[\d\-X]+$/', 'message' => 'ISBN должен содержать только цифры, дефисы и X'],
            [['description'], 'string'],
            [['cover_path'], 'string', 'max' => 255],
            [['cover'], 'app\validators\SafeFileValidator', 'extensions' => 'jpg, jpeg, png, gif', 'maxSize' => 5 * 1024 * 1024],
            [['authorIds'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'year' => 'Год издания',
            'isbn' => 'ISBN',
            'description' => 'Описание',
            'cover_path' => 'Путь к обложке',
            'cover' => 'Обложка',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'authorIds' => 'Авторы',
        ];
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthors()
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id']);
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }

    /**
     * @var array
     */
    private $_authorIds;

    /**
     * Get author IDs
     *
     * @return array
     */
    public function getAuthorIds()
    {
        if ($this->_authorIds === null) {
            $this->_authorIds = $this->getAuthors()->select('id')->column();
        }
        return $this->_authorIds;
    }

    /**
     * Set author IDs
     *
     * @param array $authorIds
     */
    public function setAuthorIds($authorIds)
    {
        $this->_authorIds = $authorIds;
    }

    /**
     * Get cover URL
     *
     * @return string|null
     */
    public function getCoverUrl()
    {
        if ($this->cover_path) {
            return Yii::getAlias('@web/uploads/books/' . $this->id . '/' . $this->cover_path);
        }
        return null;
    }

    /**
     * Upload cover file
     *
     * @return bool
     */
    public function uploadCover()
    {
        if ($this->cover && $this->cover->tempName && file_exists($this->cover->tempName) && $this->cover->size > 0) {
            $uploadDir = Yii::getAlias('@webroot/uploads/books/' . $this->id);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = 'cover.' . $this->cover->extension;
            $filePath = $uploadDir . '/' . $fileName;

            if ($this->cover->saveAs($filePath)) {
                $this->cover_path = $fileName;
                return true;
            }
        }
        return false;
    }

    /**
     * Delete cover file
     *
     * @return bool
     */
    public function deleteCover()
    {
        if ($this->cover_path) {
            $filePath = Yii::getAlias('@webroot/uploads/books/' . $this->id . '/' . $this->cover_path);
            if (file_exists($filePath)) {
                return unlink($filePath);
            }
        }
        return true;
    }
}
