const hora= document.getElementById("hora");
const fecha= document.getElementById("fecha");
const long= document.getElementById("long");
const lat= document.getElementById("lat");
const submit= document.getElementById("submit");

function set_time_sql(miliseconds) {
    let date = new Date(miliseconds);
    let hour, minute, second;
    
    hour = date.getHours();
    minute = date.getMinutes();
    second = date.getSeconds();
    hour < 10 ? hour = '0' + hour : hour = hour;
    minute < 10 ? minute = '0' + minute : minute = minute;
    second < 10 ? second = '0' + second : second = second;
    let set_time= hour + ':' +  minute + ':' + second + '.000000';
    return set_time;
}

function set_date_sql(miliseconds) {
    let date = new Date(miliseconds);
    let day, month, year;
    date.getDate() < 10 ? day = '0' + date.getDate() : day = date.getDate();
    month = parseInt(date.getMonth())+1;
    date.getMonth() < 10 ? month = '0' + month : month = month;
    year = date.getFullYear();
    let set_date = year + '-' + month +'-'+ day;
    return set_date;
}

function obtenerDatos(){
    navigator.geolocation.getCurrentPosition((ubicacion)=>{
    fecha.value=set_date_sql(ubicacion.timestamp);
    hora.value=set_time_sql(ubicacion.timestamp);
    long.value=ubicacion.coords.longitude
    lat.value=ubicacion.coords.latitude
    habilitarBoton();
    })
}

function habilitarBoton(){
    hora.value==''? submit.disabled= true: submit.disabled= false;
}

function compruebaHora(){
    if(hora.value==''){
        setTimeout(compruebaHora,3000);
        habilitarBoton();
        console.log("Hola");
    }
    else{
        habilitarBoton();
    }
} 

function borrar_banner(){
    let divs= document.querySelectorAll("div");
    if(divs[divs.length-1].className == ""){
        divs[divs.length-1].style= "display: none;"
    }
}


setTimeout(borrar_banner,1000);
obtenerDatos();
compruebaHora();

if(localStorage.getItem("aviso_hs_extra")!="Aceptado"){
    if(window.confirm("A partir del lunes 22/09/2024 no se contarán las horas extras que no hayan sido autorizadas. La aplicación recientemente sufió cambios, en caso de encontrar errores por favor reportarlos.")){
        localStorage.setItem("aviso_hs_extra", "Aceptado");
    }
}