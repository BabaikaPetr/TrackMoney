// Ожидание полной загрузки DOM перед выполнением скрипта
document.addEventListener("DOMContentLoaded", function () {
  // Вызываем функцию загрузки данных дашборда
  loadDashboardData();

  // Получение элементов модального окна для добавления операции
  const modal = document.getElementById("addTransactionModal");
  const openModalBtn = document.getElementById("openModalBtn");
  const closeModalBtn = document.getElementById("closeModalBtn");

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

  // Проверка наличия всех элементов модального окна
  if (openModalBtn && modal && closeModalBtn) {
    // Обработчик клика по кнопке открытия модального окна
    openModalBtn.onclick = function () {
      modal.classList.add("show"); // Показать модальное окно
      document.body.style.overflow = "hidden"; // Запретить прокрутку фона
    };
    // Обработчик клика по кнопке закрытия модального окна (крестик)
    closeModalBtn.onclick = function () {
      modal.classList.remove("show"); // Скрыть модальное окно
      document.body.style.overflow = ""; // Разрешить прокрутку фона
    };
    // Обработчик клика вне модального окна для его закрытия
    window.onclick = function (event) {
      if (event.target === modal) {
        modal.classList.remove("show"); // Скрыть модальное окно
        document.body.style.overflow = ""; // Разрешить прокрутку фона
      }
    };
  }
});

// Функция для загрузки и отображения данных дашборда (баланс, история, график) - основная функция
function loadDashboardData() {
  // Загружаем данные дашборда с сервера
  fetch("../includes/get_dashboard.php")
    .then((res) => res.json())
    .then((data) => {
      // Проверяем успешность получения данных и авторизации
      if (!data.success) {
        // Если не авторизован, перенаправляем на страницу входа
        window.location.href = "login.html";
        return;
      }

      // Отображаем текущий баланс пользователя
      document.getElementById("balance").textContent = data.balance + " ₽";

      // Отображаем имя пользователя
      document.getElementById("username").textContent = data.username;

      // Заполняем таблицу истории операций
      const tbody = document
        .getElementById("historyTable")
        .querySelector("tbody");
      tbody.innerHTML = ""; // Очищаем текущее содержимое таблицы

      // Перебираем полученные операции и добавляем их в таблицу
      data.history.forEach((item) => {
        const tr = document.createElement("tr");
        // Формируем HTML-строку для операции с кнопкой удаления
        tr.innerHTML = `
          <td>${item.created_at.slice(0, 16).replace("T", " ")}</td>
          <td>${item.category}</td>
          <td>${item.amount} ₽</td>
          <td>${item.type === "income" ? "Доход" : "Расход"}</td>
          <td><button class="delete-transaction-btn" data-transaction-id="${
            item.id
          }">×</button></td>
        `;
        // Добавляем строку в тело таблицы
        tbody.appendChild(tr);
      });

      // обработчик клика для кнопок удаления транзакций (используем делегирование событий)
      tbody.removeEventListener("click", handleTransactionDelete);
      tbody.addEventListener("click", handleTransactionDelete);

      // Построение графика расходов по категориям
      // Находим canvas элемент
      const ctx = document.getElementById("categoryChart");
      // Проверяем, существует ли уже график на этом canvas
      if (window.myCategoryChart) {
        window.myCategoryChart.destroy(); // Уничтожаем предыдущий график
      }
      const chartData = {
        // Данные для графика
        labels: data.chart.map((c) => c.name), // Названия категорий для подписей
        datasets: [
          {
            data: data.chart.map((c) => c.total), // Суммы по категориям
            backgroundColor: [
              // Цвета для секторов графика
              "#34d399",
              "#6ee7b7",
              "#a7f3d0",
              "#fbbf24",
              "#f87171",
              "#60a5fa",
              "#818cf8",
              "#f472b6",
              "#facc15",
              "#fcd34d",
              "#c084fc",
              "#f472b6",
            ],
          },
        ],
      };
      // Создание нового графика типа Doughnut и сохранение его в глобальной переменной для последующего уничтожения
      window.myCategoryChart = new Chart(ctx, {
        type: "doughnut",
        data: chartData,
        options: {
          plugins: {
            legend: { position: "bottom" }, // Расположение легенды графика
          },
        },
      });
    });
}

// Обработчик клика для удаления транзакции
function handleTransactionDelete(e) {
  // Проверяем, был ли клик по кнопке удаления
  if (e.target.classList.contains("delete-transaction-btn")) {
    const transactionId = e.target.dataset.transactionId;
    // Проверяем, что transactionId корректный
    if (!transactionId || transactionId === "0") {
      alert("Ошибка: Не удалось получить ID транзакции.");
      return;
    }
    // Запрос подтверждения удаления у пользователя
    if (confirm("Вы уверены, что хотите удалить эту транзакцию?")) {
      // Отправка запроса на удаление транзакции на сервер
      const formData = new FormData();
      formData.append("transaction_id", transactionId);

      fetch("../includes/delete_transaction.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            // Если удаление успешно, перезагружаем все данные дашборда
            loadDashboardData();
          } else {
            // Если ошибка, показать сообщение с сервера или стандартное
            alert(
              "Ошибка при удалении транзакции: " +
                (data.message || data.error || "Неизвестная ошибка")
            );
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Произошла ошибка при удалении транзакции.");
        });
    }
  }
}
