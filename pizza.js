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
        if (!Pizza.TYPES[type]) throw new Error(`Неизвестный вид пиццы: "${type}"`);
        if (!Pizza.SIZES[size]) throw new Error(`Неизвестный размер пиццы: "${size}"`);
        this.type = type;
        this.size = size;
        this.toppings = [];
    }

    setType(type) {
        if (!Pizza.TYPES[type]) throw new Error(`Неизвестный вид: ${type}`);
        this.type = type;
        return this;
    }

    setSize(size) {
        if (!Pizza.SIZES[size]) throw new Error(`Неизвестный размер: ${size}`);
        this.size = size;
        return this;
    }

    addTopping(topping) {
        if (!Pizza.TOPPINGS[topping]) throw new Error(`Неизвестная добавка: ${topping}`);
        if (!this.toppings.includes(topping)) this.toppings.push(topping);
        return this;
    }

    removeTopping(topping) {
        const i = this.toppings.indexOf(topping);
        if (i !== -1) this.toppings.splice(i, 1);
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
        for (const t of this.toppings) price += Pizza.TOPPINGS[t][this.size].price;
        return price;
    }

    calculateCalories() {
        let cal = Pizza.TYPES[this.type].calories + Pizza.SIZES[this.size].calories;
        for (const t of this.toppings) cal += Pizza.TOPPINGS[t][this.size].calories;
        return cal;
    }
}
