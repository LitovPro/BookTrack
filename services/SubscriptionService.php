<?php

namespace app\services;

use Yii;
use app\models\AuthorSubscription;
use app\models\Author;

/**
 * Service for managing author subscriptions
 */
class SubscriptionService
{
    /**
     * Create subscription for author
     *
     * @param int $authorId Author ID
     * @param string $phone Phone number
     * @return AuthorSubscription|null
     * @throws \Exception
     */
    public function createSubscription(int $authorId, string $phone): ?AuthorSubscription
    {
        // Check if author exists
        $author = Author::findOne($authorId);
        if (!$author) {
            throw new \Exception('Автор не найден');
        }

        // Check if subscription already exists
        $existingSubscription = AuthorSubscription::find()
            ->where(['author_id' => $authorId, 'phone' => $phone])
            ->one();

        if ($existingSubscription) {
            throw new \Exception('Вы уже подписаны на этого автора с данным номером телефона');
        }

        // Create new subscription
        $subscription = new AuthorSubscription();
        $subscription->author_id = $authorId;
        $subscription->phone = $phone;

        if (!$subscription->save()) {
            throw new \Exception('Ошибка при создании подписки: ' . implode(', ', $subscription->getFirstErrors()));
        }

        return $subscription;
    }

    /**
     * Get all subscribers for author
     *
     * @param int $authorId Author ID
     * @return array Array of phone numbers
     */
    public function getSubscribers(int $authorId): array
    {
        return AuthorSubscription::find()
            ->select('phone')
            ->where(['author_id' => $authorId])
            ->column();
    }

    /**
     * Get all subscribers for multiple authors
     *
     * @param array $authorIds Array of author IDs
     * @return array Array of phone numbers
     */
    public function getSubscribersForAuthors(array $authorIds): array
    {
        if (empty($authorIds)) {
            return [];
        }

        return AuthorSubscription::find()
            ->select('phone')
            ->where(['author_id' => $authorIds])
            ->distinct()
            ->column();
    }

    /**
     * Check if subscription exists
     *
     * @param int $authorId Author ID
     * @param string $phone Phone number
     * @return bool
     */
    public function subscriptionExists(int $authorId, string $phone): bool
    {
        return AuthorSubscription::find()
            ->where(['author_id' => $authorId, 'phone' => $phone])
            ->exists();
    }

    /**
     * Get subscription count for author
     *
     * @param int $authorId Author ID
     * @return int
     */
    public function getSubscriptionCount(int $authorId): int
    {
        return AuthorSubscription::find()
            ->where(['author_id' => $authorId])
            ->count();
    }
}

