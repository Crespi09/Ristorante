
var flags = [0, 0, 0];

    // 1 index - A-login / A-register

    // 2 home - profilo
    // 3 home - filtri

function showNhidden(chiamante, numero){


    if(numero == 0){ // ========================================================================================== SWITCH NON GENERICO


    if(flags[numero] == 0){

        document.getElementById('A-Login').style.display = "none";
        document.getElementById('A-Register').style.display = "block";
        flags[numero]++;

    }else{

        document.getElementById('A-Login').style.display = "block";
        document.getElementById('A-Register').style.display = "none";
        flags[numero]--;
        
    } console.log("swich login / register attivato");
    }
    
    else{ // ===================================================================================================== SWITCH GENERICI

        if(flags[numero] == 0){ document.getElementById(chiamante).style.display = "block"; flags[numero]++ ;
        }else{                  document.getElementById(chiamante).style.display = "none"; flags[numero]--  ; }

        console.log("swich" + chiamante +" attivato");

    }

}