<?php

use yii\db\Migration;

/**
 * Creates test data
 */
class m240914_000008_create_test_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create test authors
        $authors = [
            ['full_name' => 'Лев Толстой', 'created_at' => time(), 'updated_at' => time()],
            ['full_name' => 'Фёдор Достоевский', 'created_at' => time(), 'updated_at' => time()],
            ['full_name' => 'Антон Чехов', 'created_at' => time(), 'updated_at' => time()],
            ['full_name' => 'Александр Пушкин', 'created_at' => time(), 'updated_at' => time()],
            ['full_name' => 'Николай Гоголь', 'created_at' => time(), 'updated_at' => time()],
        ];

        foreach ($authors as $author) {
            $this->insert('{{%author}}', $author);
        }

        // Create test books
        $books = [
            ['title' => 'Война и мир', 'year' => 1869, 'isbn' => '978-5-17-123456-7', 'description' => 'Роман-эпопея Льва Толстого', 'created_at' => time(), 'updated_at' => time()],
            ['title' => 'Анна Каренина', 'year' => 1877, 'isbn' => '978-5-17-123457-4', 'description' => 'Роман Льва Толстого', 'created_at' => time(), 'updated_at' => time()],
            ['title' => 'Преступление и наказание', 'year' => 1866, 'isbn' => '978-5-17-123458-1', 'description' => 'Роман Фёдора Достоевского', 'created_at' => time(), 'updated_at' => time()],
            ['title' => 'Братья Карамазовы', 'year' => 1880, 'isbn' => '978-5-17-123459-8', 'description' => 'Роман Фёдора Достоевского', 'created_at' => time(), 'updated_at' => time()],
            ['title' => 'Вишнёвый сад', 'year' => 1904, 'isbn' => '978-5-17-123460-4', 'description' => 'Пьеса Антона Чехова', 'created_at' => time(), 'updated_at' => time()],
            ['title' => 'Евгений Онегин', 'year' => 1833, 'isbn' => '978-5-17-123461-1', 'description' => 'Роман в стихах Александра Пушкина', 'created_at' => time(), 'updated_at' => time()],
            ['title' => 'Мёртвые души', 'year' => 1842, 'isbn' => '978-5-17-123462-8', 'description' => 'Поэма Николая Гоголя', 'created_at' => time(), 'updated_at' => time()],
        ];

        foreach ($books as $book) {
            $this->insert('{{%book}}', $book);
        }

        // Link books to authors
        $bookAuthorLinks = [
            [1, 1], // Война и мир - Лев Толстой
            [2, 1], // Анна Каренина - Лев Толстой
            [3, 2], // Преступление и наказание - Фёдор Достоевский
            [4, 2], // Братья Карамазовы - Фёдор Достоевский
            [5, 3], // Вишнёвый сад - Антон Чехов
            [6, 4], // Евгений Онегин - Александр Пушкин
            [7, 5], // Мёртвые души - Николай Гоголь
        ];

        foreach ($bookAuthorLinks as $link) {
            $this->insert('{{%book_author}}', [
                'book_id' => $link[0],
                'author_id' => $link[1],
            ]);
        }

        // Create test subscriptions
        $subscriptions = [
            ['author_id' => 1, 'phone' => '+7-999-123-45-67', 'created_at' => time()],
            ['author_id' => 2, 'phone' => '+7-999-123-45-68', 'created_at' => time()],
            ['author_id' => 1, 'phone' => '+7-999-123-45-69', 'created_at' => time()],
        ];

        foreach ($subscriptions as $subscription) {
            $this->insert('{{%author_subscription}}', $subscription);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%author_subscription}}');
        $this->delete('{{%book_author}}');
        $this->delete('{{%book}}');
        $this->delete('{{%author}}');
    }
}
