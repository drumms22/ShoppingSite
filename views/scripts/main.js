
document.getElementById("filterIcon").addEventListener("click", closePanel);
document.getElementById("screenIcon").addEventListener("click", closePanel);


document.getElementById("sort").onchange = function(){
let elem = document.getElementsByName("order_by");

for(let i = 0;i < elem.length;i++){
    if(elem[i].disabled === true){
        elem[i].disabled = false;
     }else{
        elem[i].disabled = true;
     }};
     
}

function closePanel(){

    let elem = document.getElementById("windowPanel");
    if(!elem.style.display || elem.style.display === "none"){
        elem.style.display = "flex";
    }else{
        elem.style.display = "none";
    }


}
function elementDisabled(){

    let elem = document.getElementsByName("order_by");
    console.log(elem);
     if(elem.disabled === true){
        elem.disabled = false;
     }else{
        elem.style.display = true;
     }
    
    
}

