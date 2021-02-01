"use strict";

document.querySelector('.form-ajax-update-category').addEventListener('submit', (e) =>{
    e.preventDefault();

    const formData = new FormData(e.target);
    //testFormData(formData)// чтобы протестировать раскомментировать

    let request = new XMLHttpRequest();

    function reqReadyStateChange(){
        if(request.readyState == 4 && request.status == 200)
            showNotification();
    }

    request.open("POST", "/easyadmin-custom/category-update-ajax");
    request.onreadystatechange = reqReadyStateChange;
    request.send(formData);
})

function showNotification(top = 120, right = 20){
    let notification = document.createElement('div');

    let className = "save-notification";

    if (className) {
        notification.classList.add(className);
    }

    notification.style.top = top + 'px';
    notification.style.right = right + 'px';

    let html = "Запись успешно сохранена!";

    notification.innerHTML = html;
    document.body.append(notification);

    setTimeout(() => notification.remove(), 2500);
}