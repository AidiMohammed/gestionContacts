const btn = document.querySelector('#romove-notification');
const not = document.querySelector('.ncf-container');

btn.addEventListener('click',() =>{
    not.remove();
})
not.addEventListener('click',() => not.remove());