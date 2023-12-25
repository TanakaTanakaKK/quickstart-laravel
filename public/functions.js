const searchPostal = () =>{
    let postalCode = document.getElementById('postalcode').value;
    postalCode = postalCode.replace(/[！-～]/g,function(postalCode){
        return String.fromCharCode(postalCode.charCodeAt(0)-0xFEE0);
    });
    postalCode = postalCode.replace(/[ー－-]/g,'');
    // if(postalCode.length != 7){
    //     console.log(postalCode);
    //     return;
    // }

    let prefecture = document.getElementById('prefecture');
    let city = document.getElementById('city');
    let town = document.getElementById('town');
    
        let url = 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + postalCode;
        
        fetch(url).then(response => response.json())
        .then(data => {
            prefecture.value = data.results[0].address1;
            city.value = data.results[0].address2;
            town.value = data.results[0].address3;
        })
        .catch(error => {
                console.log(error.message);
                prefecture.value = "";
                city.value = "";
                town.value = "";
                prefecture.placeholder = "郵便番号から住所を特定できません";
                city.placeholder = "郵便番号から住所を特定できません";
                town.placeholder = "郵便番号から住所を特定できません";
            });
    
}