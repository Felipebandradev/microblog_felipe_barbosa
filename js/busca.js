const formBusca = document.querySelector('#form-busca');
const campoBusca = document.querySelector('#campo-busca');
const divResultado = document.querySelector('#resultados');

// escondendo a div antes da digitação
divResultado.classList.add("visually-hidden");

campoBusca.addEventListener("input", async function(){
    if( campoBusca.value !== ""){
        const resposta = await fetch("resultados.php", {
            method: "POST",
            body: new FormData(formBusca)
        });

        const dados = await resposta.text();
        divResultado.classList.remove("visually-hidden");
        divResultado.innerHTML = dados;
    } else{
        divResultado.classList.add("visually-hidden");
        divResultado.innerHTML = " ";
    }
});