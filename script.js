'use strict';

const pizza = new Pizza('PEPPERONI', 'SMALL');

const pizzaEls   = document.querySelectorAll('.pizza');
const sizeEls    = document.querySelectorAll('.size-toggle__btn');
const toppingEls = document.querySelectorAll('.topping');
const priceEl    = document.querySelector('[data-price]');
const caloriesEl = document.querySelector('[data-calories]');

pizzaEls.forEach(el => el.addEventListener('click', () => {
    pizza.setType(el.dataset.type);
    render();
}));

sizeEls.forEach(el => el.addEventListener('click', () => {
    pizza.setSize(el.dataset.size);
    render();
}));

toppingEls.forEach(el => el.addEventListener('click', () => {
    const key = el.dataset.topping;
    pizza.toppings.includes(key) ? pizza.removeTopping(key) : pizza.addTopping(key);
    render();
}));

function render() {
    pizzaEls.forEach(el => el.classList.toggle('is-selected', el.dataset.type === pizza.type));
    sizeEls.forEach(el => el.classList.toggle('is-active', el.dataset.size === pizza.size));

    toppingEls.forEach(el => {
        const key = el.dataset.topping;
        el.classList.toggle('is-active', pizza.toppings.includes(key));
        el.querySelector('[data-topping-price]').textContent = `${Pizza.TOPPINGS[key][pizza.size].price} ₽`;
    });

    priceEl.textContent    = pizza.calculatePrice();
    caloriesEl.textContent = pizza.calculateCalories();
}

render();
