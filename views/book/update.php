<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $authors app\models\Author[] */

$this->title = 'Редактировать книгу: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="book-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="book-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Основная информация</h5>
                    </div>
                    <div class="card-body">
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'year')->textInput(['type' => 'number', 'min' => 1000, 'max' => date('Y') + 1]) ?>

                        <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Дополнительно</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($model->cover_path): ?>
                            <div class="mb-3">
                                <label class="form-label">Текущая обложка:</label>
                                <div>
                                    <?= Html::img($model->getCoverUrl(), [
                                        'class' => 'book-cover-small',
                                        'alt' => $model->title
                                    ]) ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?= $form->field($model, 'cover')->fileInput(['accept' => 'image/*']) ?>
                        <small class="form-text text-muted">
                            Поддерживаемые форматы: JPG, PNG, GIF. Максимальный размер: 5MB.
                        </small>

                        <div class="mb-3">
                            <label class="form-label">Авторы</label>
                            <input type="text" class="form-control" id="authorSearch" placeholder="Поиск авторов...">
                        </div>

                        <div id="authorsList" style="max-height: 200px; overflow-y: auto;">
                            <?= $form->field($model, 'authorIds')->checkboxList(
                                \yii\helpers\ArrayHelper::map($authors, 'id', 'full_name'),
                                ['separator' => '<br>', 'item' => function ($index, $label, $name, $checked, $value) {
                                    return '<div class="author-item" data-name="' . strtolower($label) . '">
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>
                                            <span class="form-check-label">' . $label . '</span>
                                        </label>
                                    </div>';
                                }]
                            )->label(false) ?>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAuthorModal">
                                <i class="fas fa-plus"></i> Добавить нового автора
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mt-3">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>

<!-- Modal для добавления автора -->
<div class="modal fade" id="addAuthorModal" tabindex="-1" aria-labelledby="addAuthorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAuthorModalLabel">Добавить нового автора</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="quickAuthorForm">
                    <div class="mb-3">
                        <label for="authorFullName" class="form-label">ФИО автора *</label>
                        <input type="text" class="form-control" id="authorFullName" name="full_name" required>
                        <div class="form-text">Например: Джордж Оруэлл</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="saveQuickAuthor">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Поиск авторов
    const authorSearch = document.getElementById('authorSearch');

    function updateAuthorSearch() {
        const authorItems = document.querySelectorAll('#book-authorids .author-item');
        const searchTerm = authorSearch.value.toLowerCase();

        authorItems.forEach(item => {
            const authorName = item.getAttribute('data-name');
            if (authorName.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    authorSearch.addEventListener('input', updateAuthorSearch);

    // Быстрое добавление автора
    const saveBtn = document.getElementById('saveQuickAuthor');
    const modal = document.getElementById('addAuthorModal');
    const authorNameInput = document.getElementById('authorFullName');

    saveBtn.addEventListener('click', function() {
        const fullName = authorNameInput.value.trim();

        if (!fullName) {
            alert('Пожалуйста, введите ФИО автора');
            return;
        }

        // Проверяем, что пользователь авторизован
        if (!document.querySelector('meta[name="csrf-token"]')) {
            alert('Ошибка: Пользователь не авторизован. Пожалуйста, войдите в систему.');
            return;
        }

        // Отправляем AJAX запрос для создания автора
        fetch('<?= \yii\helpers\Url::to(['/author/quick-create']) ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: 'full_name=' + encodeURIComponent(fullName) + '&_csrf=' + encodeURIComponent(document.querySelector('meta[name="csrf-token"]').getAttribute('content'))
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 403) {
                    throw new Error('Доступ запрещен. Пожалуйста, войдите в систему.');
                } else if (response.status === 401) {
                    throw new Error('Необходима авторизация. Пожалуйста, войдите в систему.');
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Проверяем, не существует ли уже такой автор в списке
                const existingCheckbox = document.querySelector(`input[name="Book[authorIds][]"][value="${data.authorId}"]`);
                if (!existingCheckbox) {
                    // Добавляем нового автора в правильный контейнер
                    const authorContainer = document.getElementById('book-authorids');
                    const newCheckbox = document.createElement('div');
                    newCheckbox.className = 'author-item';
                    newCheckbox.setAttribute('data-name', fullName.toLowerCase());
                    newCheckbox.innerHTML = `
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="Book[authorIds][]" value="${data.authorId}" checked>
                            <span class="form-check-label">${fullName}</span>
                        </label>
                    `;
                    // Добавляем <br> для разделения, как у других авторов
                    authorContainer.appendChild(document.createElement('br'));
                    authorContainer.appendChild(newCheckbox);

                    // Обновляем поиск, чтобы новый автор был виден
                    updateAuthorSearch();
                } else {
                    // Просто выбираем существующего автора
                    existingCheckbox.checked = true;
                }

                // Закрываем модальное окно и очищаем поле
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
                authorNameInput.value = '';

                // Показываем уведомление
                if (data.isExisting) {
                    alert('Автор "' + fullName + '" уже существует и выбран!');
                } else {
                    alert('Автор "' + fullName + '" успешно добавлен и выбран!');
                }
            } else {
                alert('Ошибка: ' + (data.message || 'Не удалось создать автора'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при создании автора: ' + error.message);
        });
    });
});
</script>

