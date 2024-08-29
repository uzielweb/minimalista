document.querySelectorAll('div').forEach(function(div) {
    if (div.innerHTML.trim() === '') {
        div.classList.add('is-empty');
    }
});
