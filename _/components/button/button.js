// @ts-check

const buttons = /** @type {NodeListOf<HTMLButtonElement>} */ (
    document.querySelectorAll('.button')
);

buttons.forEach((button) => {
    const label = button.dataset['label'] || 'Count';

    /** @param {number} value */
    function render(value) {
        button.innerHTML = label + `: ${value}`;
    }

    // 0 を表示
    render(0);

    // クリックごとにカウントするように
    let count = 0;
    button.addEventListener('click', () => render(++count));
});
