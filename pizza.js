'use strict';

class Pizza {
    static TYPES = {
        MARGHERITA: { name: 'Маргарита',  price: 500, calories: 300 },
        PEPPERONI:  { name: 'Пепперони',  price: 800, calories: 400 },
        BAVARIAN:   { name: 'Баварская',  price: 700, calories: 450 },
    };

    static SIZES = {
        SMALL: { name: 'Маленькая', price: 100, calories: 100 },
        LARGE: { name: 'Большая',   price: 200, calories: 200 },
    };

    static TOPPINGS = {
        MOZZARELLA: {
            name: 'Сливочная моцарелла',
            SMALL: { price:  50, calories: 20 },
            LARGE: { price:  50, calories: 20 },
        },
        CHEESE_BORDER: {
            name: 'Сырный борт',
            SMALL: { price: 150, calories: 50 },
            LARGE: { price: 300, calories: 50 },
        },
        CHEDDAR_PARMESAN: {
            name: 'Чедер и пармезан',
            SMALL: { price: 150, calories: 50 },
            LARGE: { price: 300, calories: 50 },
        },
    };

    constructor(type, size) {
        if (!Pizza.TYPES[type]) {
            throw new Error(`Неизвестный вид пиццы: "${type}". Доступные: ${Object.keys(Pizza.TYPES).join(', ')}`);
        }
        if (!Pizza.SIZES[size]) {
            throw new Error(`Неизвестный размер пиццы: "${size}". Доступные: ${Object.keys(Pizza.SIZES).join(', ')}`);
        }
        this.type = type;
        this.size = size;
        this.toppings = [];
    }

    addTopping(topping) {
        if (!Pizza.TOPPINGS[topping]) {
            throw new Error(`Неизвестная добавка: "${topping}"`);
        }
        if (this.toppings.includes(topping)) {
            console.log(`Добавка "${Pizza.TOPPINGS[topping].name}" уже есть в пицце.`);
            return this;
        }
        this.toppings.push(topping);
        return this;
    }

    removeTopping(topping) {
        const index = this.toppings.indexOf(topping);
        if (index === -1) {
            const label = Pizza.TOPPINGS[topping]?.name || topping;
            console.log(`Добавки "${label}" нет в пицце.`);
            return this;
        }
        this.toppings.splice(index, 1);
        return this;
    }

    getToppings() {
        return this.toppings.map(key => Pizza.TOPPINGS[key].name);
    }

    getSize() {
        return Pizza.TYPES[this.type].name;
    }

    getStuffing() {
        return Pizza.SIZES[this.size].name;
    }

    calculatePrice() {
        let price = Pizza.TYPES[this.type].price + Pizza.SIZES[this.size].price;
        for (const topping of this.toppings) {
            price += Pizza.TOPPINGS[topping][this.size].price;
        }
        return price;
    }

    calculateCalories() {
        let calories = Pizza.TYPES[this.type].calories + Pizza.SIZES[this.size].calories;
        for (const topping of this.toppings) {
            calories += Pizza.TOPPINGS[topping][this.size].calories;
        }
        return calories;
    }

    describe() {
        const toppings = this.getToppings();
        console.log('--- Пицца ---');
        console.log(`Вид:          ${this.getSize()}`);
        console.log(`Размер:       ${this.getStuffing()}`);
        console.log(`Добавки:      ${toppings.length ? toppings.join(', ') : 'нет'}`);
        console.log(`Цена:         ${this.calculatePrice()} руб.`);
        console.log(`Калорийность: ${this.calculateCalories()} Ккал`);
    }
}

const pizza1 = new Pizza('PEPPERONI', 'LARGE');
pizza1.addTopping('MOZZARELLA');
pizza1.addTopping('CHEESE_BORDER');
pizza1.describe();

console.log();

const pizza2 = new Pizza('MARGHERITA', 'SMALL');
pizza2.addTopping('CHEDDAR_PARMESAN');
pizza2.addTopping('MOZZARELLA');
pizza2.removeTopping('MOZZARELLA');
pizza2.describe();

console.log();

const pizza3 = new Pizza('BAVARIAN', 'LARGE');
pizza3.describe();
