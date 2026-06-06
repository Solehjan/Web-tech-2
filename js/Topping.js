// Класс Topping  описывает добавку к пицце
class Topping {
    constructor(name, price, calories) {
        this.name = name;
        this.price = price;
        this.calories = calories;
    }

    getName() {
        return this.name;
    }

    getPrice() {
        return this.price;
    }

    getCalories() {
        return this.calories;
    }
}