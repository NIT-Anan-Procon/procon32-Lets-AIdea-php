let params = new URLSearchParams();
params.append('username', 'kinoshita');
params.append('password', '3333');

function postman() {
axios.post('http://localhost:81/~kinoshita/procon32_Lets_AIdea_php/API/Login.php', params)
  // axios.post('http://localhost/procon32_Lets_AIdea_php/userInfo/userInfo.php', params)
    .then(response => {
      console.log(response.data);
    }).catch(error => {
      console.log(error);
    });
}

postman();