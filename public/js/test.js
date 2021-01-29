"use strict";

function testFormData(formData){
     for(var pair of formData.entries()){
        console.log(pair[0]+', '+pair[1])
     }
}