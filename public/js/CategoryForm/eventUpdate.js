"use strict";

document.querySelector('.form-ajax-update-category').addEventListener('submit', (e) =>{
    e.preventDefault();

    const formData = new FormData(e.target);
    //testFormData(formData)// чтобы протестировать раскомментировать

    let request = new XMLHttpRequest();

    function reqReadyStateChange(){
        if(request.readyState == 4 && request.status == 200)
            document.getElementById("output").innerHTML=request.responseText;
    }

    request.open("POST", "/easyadmin-custom/category-update-ajax");
    request.onreadystatechange = reqReadyStateChange;
    request.send(formData);
})