document.addEventListener("DOMContentLoaded", function () {
  // Проверка админа (по username) путем запроса данных дашборда
  fetch("../includes/get_dashboard.php")
    .then((res) => res.json())
    .then((data) => {
      // Если пользователь не админ или запрос неуспешен, перенаправить на главную
      if (!data.success || data.username !== "admin") {
        window.location.href = "../index.html";
        return;
      }
      // Отобразить имя пользователя (админа)
      document.getElementById("username").textContent = data.username;
      // Загрузить и отобразить список категорий
      loadCategories();
    });

  // Добавление категории - получение элементов формы и сообщения
  const form = document.getElementById("addCategoryForm");
  const msg = document.getElementById("addCategoryMsg");

  // Обработчик отправки формы добавления категории
  form.addEventListener("submit", function (e) {
    e.preventDefault(); // Предотвратить стандартную отправку формы
    msg.textContent = ""; // Очистить предыдущее сообщение

    // Сбор данных формы
    const formData = new FormData(form);

    // Отправка данных на сервер для добавления категории
    fetch("../includes/add_category.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        // Обработка ответа от сервера
        if (data.success) {
          msg.style.color = "#34d399"; // Зеленый цвет для успеха
          msg.textContent = "Категория добавлена!";
          form.reset(); // Сброс формы после успешного добавления
          loadCategories(); // Перезагрузка списка категорий
        } else if (data.error === "exists") {
          msg.style.color = "#f87171"; // Красный цвет для ошибки
          msg.textContent = "Такая категория уже есть.";
        } else if (data.error === "empty") {
          msg.style.color = "#f87171"; // Красный цвет для ошибки
          msg.textContent = "Введите название.";
        } else {
          msg.style.color = "#f87171"; // Красный цвет для общей ошибки
          msg.textContent = "Ошибка!";
        }
      });
  });

  // Функция для загрузки и отображения списка категорий
  function loadCategories() {
    // Запрос списка категорий с сервера
    fetch("../includes/get_categories.php")
      .then((res) => res.json())
      .then((categories) => {
        // Получение элемента списка
        const ul = document.getElementById("categoriesList");
        ul.innerHTML = ""; // Очистка текущего списка

        // Формирование HTML для каждого элемента списка с кнопкой удаления
        ul.innerHTML = categories
          .map(
            (cat) => `
          <li data-category-id="${cat.id}">
            <span>${cat.name}</span>
            <button class="delete-category-btn" data-category-id="${cat.id}">×</button>
          </li>
        `
          )
          .join("");

        // Добавление обработчиков клика для кнопок удаления (используем делегирование событий)
        ul.addEventListener("click", function (e) {
          // Проверяем, был ли клик по кнопке удаления
          if (e.target.classList.contains("delete-category-btn")) {
            const categoryId = e.target.dataset.categoryId;
            // Запрос подтверждения удаления у пользователя
            if (
              confirm(
                'Вы уверены, что хотите удалить категорию "' +
                  e.target.previousElementSibling.textContent +
                  '"?'
              )
            ) {
              // Отправка запроса на удаление категории на сервер
              const formData = new FormData();
              formData.append("category_id", categoryId);

              fetch("../includes/delete_category.php", {
                method: "POST",
                body: formData,
              })
                .then((res) => res.json())
                .then((data) => {
                  if (data.success) {
                    // Если удаление успешно, перезагрузить список категорий
                    loadCategories();
                  } else {
                    // Если ошибка, показать сообщение
                    alert(
                      "Ошибка при удалении категории: " +
                        (data.message || data.error)
                    );
                  }
                })
                .catch((error) => {
                  console.error("Error:", error);
                  alert("Произошла ошибка при удалении категории.");
                });
            }
          }
        });
      });
  }

  // ЛОГАУТ - получение кнопки выхода
  const logoutBtn = document.getElementById("logoutBtn");
  // Проверка наличия кнопки логаута
  if (logoutBtn) {
    // Обработчик клика по кнопке логаута
    logoutBtn.onclick = function () {
      // Отправка запроса на выход
      fetch("../includes/logout.php").then(() => {
        // После выхода перенаправление на главную страницу
        window.location.href = "../index.html";
      });
    };
  }
});
