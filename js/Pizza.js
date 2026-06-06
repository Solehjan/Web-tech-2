// Класс Pizza - основная логика
class Pizza {
    static SIZE_PRICES = {
        'Большая': { price: 200, calories: 200 },
        'Маленькая': { price: 100, calories: 100 }
    };

    static AVAILABLE_TOPPINGS = {
        'сливочная моцарелла': { price: 50, calories: 20, hasSizeDependency: false },
        'сырный борт': { price_small: 150, price_large: 300, calories: 50, hasSizeDependency: true },
        'чедер и пармезан': { price_small: 150, price_large: 300, calories: 50, hasSizeDependency: true }
    };

    constructor(type, basePrice, baseCalories) {
        this.type = type;
        this.basePrice = basePrice;
        this.baseCalories = baseCalories;
        this.size = 'Маленькая';
        this.toppings = [];
    }

    setSize(size) {
        if (!Pizza.SIZE_PRICES[size]) {
            throw new Error(`Недопустимый размер: ${size}`);
        }
        this.size = size;
    }

    addTopping(toppingName) {
        if (!Pizza.AVAILABLE_TOPPINGS[toppingName]) {
            throw new Error(`Добавка '${toppingName}' не найдена`);
        }

        if (this.toppings.some(t => t.getName() === toppingName)) {
            throw new Error(`Добавка '${toppingName}' уже есть`);
        }

        const data = Pizza.AVAILABLE_TOPPINGS[toppingName];
        let price, calories;

        if (data.hasSizeDependency) {
            price = this.size === 'Большая' ? data.price_large : data.price_small;
            calories = data.calories;
        } else {
            price = data.price;
            calories = data.calories;
        }

        this.toppings.push(new Topping(toppingName, price, calories));
    }

    removeTopping(toppingName) {
        const index = this.toppings.findIndex(t => t.getName() === toppingName);
        if (index === -1) {
            throw new Error(`Добавка '${toppingName}' не найдена`);
        }
        this.toppings.splice(index, 1);
    }

    getToppings() {
        return this.toppings.map(t => t.getName());
    }

    getType() {
        return this.type;
    }

    getSize() {
        return this.size;
    }

    calculatePrice() {
        let total = this.basePrice + Pizza.SIZE_PRICES[this.size].price;
        this.toppings.forEach(t => total += t.getPrice());
        return total;
    }

    calculateCalories() {
        let total = this.baseCalories + Pizza.SIZE_PRICES[this.size].calories;
        this.toppings.forEach(t => total += t.getCalories());
        return total;
    }

    getInfo() {
        return {
            type: this.type,
            size: this.size,
            toppings: this.getToppings(),
            price: this.calculatePrice(),
            calories: this.calculateCalories()
        };
    }
}