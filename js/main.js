// Состояние нашего интерфейса
let currentPizzaData = { type: 'Пепперони', price: 800, calories: 400 };
let currentSize = 'Маленькая';
let selectedToppings = new Set(); // Используем Set, чтобы добавки не дублировались

document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Обработка кликов по видам пиццы
    const pizzaCards = document.querySelectorAll('.pizza-card');
    pizzaCards.forEach(card => {
        card.addEventListener('click', function() {
            // Убираем выделение со всех, добавляем текущей
            pizzaCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');

            // Обновляем состояние
            currentPizzaData.type = this.dataset.type;
            currentPizzaData.price = parseInt(this.dataset.price);
            currentPizzaData.calories = parseInt(this.dataset.calories);
            
            updateCart();
        });
    });

    // 2. Обработка кликов по кнопкам размера
    const sizeBtns = document.querySelectorAll('.size-btn');
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sizeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            currentSize = this.dataset.size;
            updateCart();
        });
    });

    // 3. Обработка кликов по карточкам добавок
    const toppingCards = document.querySelectorAll('.topping-card');
    toppingCards.forEach(card => {
        card.addEventListener('click', function() {
            this.classList.toggle('selected');
            const toppingName = this.dataset.topping;

            if (this.classList.contains('selected')) {
                selectedToppings.add(toppingName);
            } else {
                selectedToppings.delete(toppingName);
            }
            updateCart();
        });
    });

    // Первичный расчет при загрузке страницы 
    updateCart();
});

// Главная функция, которая связывает классы из Lab 12 с новым UI
function updateCart() {
    try {
        // Создаем объект пиццы из 12 практики
        const pizza = new Pizza(currentPizzaData.type, currentPizzaData.price, currentPizzaData.calories);
        
        // Устанавливаем размер
        pizza.setSize(currentSize);

        // Добавляем все выбранные топпинги
        selectedToppings.forEach(toppingName => {
            pizza.addTopping(toppingName);
        });

        // Получаем итоговые значения
        const finalPrice = pizza.calculatePrice();
        const finalCalories = pizza.calculateCalories();

        // Обновляем кнопку интерфейса 
        const btn = document.getElementById('addToCartBtn');
        btn.innerHTML = `Добавить в корзину за<br>${finalPrice} ₽ (${finalCalories} кКалл)`;
        
    } catch (error) {
        console.error("Ошибка при расчете:", error.message);
    }
}