const searchPostal = () =>{
    let postalCode = document.getElementById('postalcode').value;
    postalCode = postalCode.replace(/[ー－-]/g,'');
    postalCode = postalCode.replace(/[！-～]/g,function(postalCode){
        return String.fromCharCode(postalCode.charCodeAt(0)-0xFEE0);
    })
    if(postalCode.length != 7){
        return;
    }
    let url = 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + postalCode;
    let prefecture = document.getElementById('prefecture');
    let city = document.getElementById('city');
    let town = document.getElementById('town');

    list = fetch(url).then(response => response.json())
    .then(data => {
    prefecture.value = data.results[0].address1;
    city.value = data.results[0].address2;
    town.value = data.results[0].address3;
        });
    
}