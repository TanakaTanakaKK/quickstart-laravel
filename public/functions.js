const searchPostal = () =>{
    let postal_code = document.getElementById('postal_code').value;
    postal_code = postal_code.replace(/[！-～]/g,function(postal_code){
        return String.fromCharCode(postal_code.charCodeAt(0)-0xFEE0);
    });
    postal_code = postal_code.replace(/[ー－-]/g,'');
    let prefecture = document.getElementById('prefecture');
    let cities = document.getElementById('cities');
    let url = 'https://zipcloud.ibsnet.co.jp/api/search?zipcode=' + postal_code;
    fetch(url).then(response => response.json())
    .then(data => {
        prefecture.value = data.results[0].prefcode;
        cities.value = data.results[0].address2+data.results[0].address3;
    })
    .catch(error => {
        prefecture.value = "";
        cities.value = "";
        prefecture.placeholder = "郵便番号から住所を特定できません";
        cities.placeholder = "郵便番号から住所を特定できません";
    });
}