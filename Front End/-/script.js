var flag_index = 0;

function IndexFunction(){


    if(flag_index == 0){
        document.getElementById('A-Login').style.display = "none";
        document.getElementById('A-Register').style.display = "block";
        flag_index ++;
        
    }else{
        document.getElementById('A-Login').style.display = "block";
        document.getElementById('A-Register').style.display = "none";
        flag_index --;
        
    }

}