<?php

namespace app\services;

use Yii;
use app\models\Book;
use app\models\Author;
use app\models\BookAuthor;
use yii\db\Transaction;
use yii\web\UploadedFile;

/**
 * Service for managing books
 */
class BookService
{
    /**
     * Create new book with authors
     *
     * @param array $bookData Book data
     * @param array $authorIds Array of author IDs
     * @param UploadedFile|null $coverFile Cover file
     * @return Book
     * @throws \Exception
     */
    public function createBook(array $bookData, array $authorIds = [], UploadedFile $coverFile = null): Book
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            // Create book
            $book = new Book();
            $book->setAttributes($bookData);

            if (!$book->save()) {
                throw new \Exception('Ошибка при сохранении книги: ' . implode(', ', $book->getFirstErrors()));
            }

            // Handle cover upload
            if ($coverFile && $coverFile->tempName && file_exists($coverFile->tempName) && $coverFile->size > 0) {
                $book->cover = $coverFile;
                if (!$book->uploadCover()) {
                    throw new \Exception('Ошибка при загрузке обложки');
                }
                $book->save();
            }

            // Link authors
            if (!empty($authorIds)) {
                $this->linkAuthors($book->id, $authorIds);
            }

            $transaction->commit();

            // Trigger event for SMS notifications
            $this->triggerBookCreatedEvent($book->id, $authorIds);

            return $book;

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Update book with authors
     *
     * @param Book $book Book model
     * @param array $bookData Book data
     * @param array $authorIds Array of author IDs
     * @param UploadedFile|null $coverFile Cover file
     * @return Book
     * @throws \Exception
     */
    public function updateBook(Book $book, array $bookData, array $authorIds = [], UploadedFile $coverFile = null): Book
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            // Update book data
            $book->setAttributes($bookData);

            if (!$book->save()) {
                throw new \Exception('Ошибка при обновлении книги: ' . implode(', ', $book->getFirstErrors()));
            }

            // Handle cover upload
            if ($coverFile && $coverFile->tempName && file_exists($coverFile->tempName) && $coverFile->size > 0) {
                // Delete old cover
                $book->deleteCover();

                $book->cover = $coverFile;
                if (!$book->uploadCover()) {
                    throw new \Exception('Ошибка при загрузке обложки');
                }
                $book->save();
            }

            // Update author links
            $this->unlinkAllAuthors($book->id);
            if (!empty($authorIds)) {
                $this->linkAuthors($book->id, $authorIds);
            }

            $transaction->commit();

            return $book;

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Delete book
     *
     * @param Book $book Book model
     * @return bool
     * @throws \Exception
     */
    public function deleteBook(Book $book): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            // Delete cover file
            $book->deleteCover();

            // Delete book
            if (!$book->delete()) {
                throw new \Exception('Ошибка при удалении книги');
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Link authors to book
     *
     * @param int $bookId Book ID
     * @param array $authorIds Array of author IDs
     * @throws \Exception
     */
    private function linkAuthors(int $bookId, array $authorIds): void
    {
        foreach ($authorIds as $authorId) {
            // Check if author exists
            $author = Author::findOne($authorId);
            if (!$author) {
                throw new \Exception("Автор с ID {$authorId} не найден");
            }

            // Create link
            $bookAuthor = new BookAuthor();
            $bookAuthor->book_id = $bookId;
            $bookAuthor->author_id = $authorId;

            if (!$bookAuthor->save()) {
                throw new \Exception('Ошибка при связывании автора с книгой: ' . implode(', ', $bookAuthor->getFirstErrors()));
            }
        }
    }

    /**
     * Unlink all authors from book
     *
     * @param int $bookId Book ID
     */
    private function unlinkAllAuthors(int $bookId): void
    {
        BookAuthor::deleteAll(['book_id' => $bookId]);
    }

    /**
     * Trigger book created event for SMS notifications
     *
     * @param int $bookId Book ID
     * @param array $authorIds Array of author IDs
     */
    private function triggerBookCreatedEvent(int $bookId, array $authorIds): void
    {
        if (!empty($authorIds)) {
            // Trigger event for SMS notifications
            Yii::$app->trigger('book.created', new BookCreatedEvent([
                'bookId' => $bookId,
                'authorIds' => $authorIds,
            ]));
        }
    }
}

