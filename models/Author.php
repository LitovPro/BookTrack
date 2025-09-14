<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "author".
 *
 * @property int $id
 * @property string $full_name
 * @property int $created_at
 * @property int $updated_at
 *
 * @property BookAuthor[] $bookAuthors
 * @property Book[] $books
 * @property AuthorSubscription[] $authorSubscriptions
 */
class Author extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%author}}';
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
            [['full_name'], 'required'],
            [['full_name'], 'string', 'max' => 255],
            [['full_name'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Полное имя',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthors()
    {
        return $this->hasMany(BookAuthor::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('{{%book_author}}', ['author_id' => 'id']);
    }

    /**
     * Gets query for [[AuthorSubscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorSubscriptions()
    {
        return $this->hasMany(AuthorSubscription::class, ['author_id' => 'id']);
    }

    /**
     * Get books count for this author
     *
     * @return int
     */
    public function getBooksCount()
    {
        return $this->getBooks()->count();
    }

    /**
     * Get books count for this author in specific year
     *
     * @param int $year
     * @return int
     */
    public function getBooksCountByYear($year)
    {
        return $this->getBooks()->andWhere(['year' => $year])->count();
    }
}

